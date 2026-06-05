<?php

namespace App\Controller;

use App\Model\ContraChequeModel;
use App\Model\FuncionarioModel;
use Core\Library\ControllerMain;
use Core\Library\Files;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;

class ContraCheque extends ControllerMain
{
    protected $files;

    public function __construct()
    {
        $this->auxiliarConstruct();
        $this->loadHelper('formHelper');

        $this->model = new ContraChequeModel();
        $this->files = new Files(); // 🔥 igual automação

        if (!Session::get("userId")) {
            Redirect::page("login");
            exit;
        }
    }

    // 📄 LISTA
    public function index()
{
    $userId    = Session::get("userId");
    $userNivel = Session::get("userNivel");

    $filtros = [
        "id"        => isset($_POST['id']) ? (int)$_POST['id'] : null,
        "descricao" => isset($_POST['descricao']) ? trim($_POST['descricao']) : null,
        "busca"     => isset($_POST['busca']) ? trim($_POST['busca']) : null,
    ];

    // 🔒 Se for nível 11 (Funcionário)
    if ((int)$userNivel === 11) {
        // Precisamos instanciar o model de funcionário para achar o vínculo
        $funcionarioModel = new \App\Model\funcionarioModel();
        
        // Buscamos o funcionário que tem o usuario_id igual ao ID da sessão
        $funcionario = $funcionarioModel->db
                        ->where("usuario_id", $userId)
                        ->first();

        if ($funcionario) {
            // Aplicamos o ID real da tabela funcionario
            $filtros["funcionario_id"] = $funcionario['id']; 
        } else {
            // Se não achar o vínculo, forçamos um ID inexistente para não listar nada de outros
            $filtros["funcionario_id"] = -1; 
        }
    }

    // Chama o método no model (certifique-se que o nome é listaContracheque)
    $contracheques = $this->model->listaContracheque($filtros);

    return $this->loadView("sistema/listaContraCheque", [
        "contracheques" => $contracheques,
        "filtros" => $filtros
    ]);
}
    

    // 📄 FORM
    public function form($action, $id = null)
{
    $FuncionarioModel = new FuncionarioModel();

    $dados = [
        "data" => $this->model->getById($id),
        "aFuncionario" => $FuncionarioModel->lista()
    ];

    return $this->loadView("sistema/formContraCheque", $dados);
}

    // 🔥 INSERT
    public function insert()
{
    $post = $this->request->getPost();

    if (!isset($post['arquivo'])) {
        $post['arquivo'] = '';
    }

    if (Validator::make($post, $this->model->validationRules)) {
        return Redirect::page($this->controller . "/form/insert/0");
    }

    // 📄 UPLOAD PDF
    if (!empty($_FILES['arquivo']['name'])) {

        $ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);

        if ($ext != 'pdf') {
            Session::set('msgErro', 'Apenas PDF permitido');
            return Redirect::page($this->controller . "/form/insert/0");
        }

        // 🔥 usa Files
        $retorno = $this->files->upload($_FILES, 'contracheques');

        if (is_bool($retorno)) {
            Session::set('inputs', $post);
            return Redirect::page($this->controller . "/form/insert/0");
        }

        $post['arquivo'] = $retorno[0];
    }

    if ($this->model->insert($post)) {
        // 🔔 SISTEMA DE NOTIFICAÇÃO DISPARADO AQUI (PARA FUNCIONÁRIO)
            if (!empty($post['funcionario_id'])) {
                $funcionarioModel = new \App\Model\FuncionarioModel();
                // Busca os dados do funcionário para descobrir qual o usuario_id dele
                $funcionarioData = $funcionarioModel->getById($post['funcionario_id']);
                
                if ($funcionarioData && !empty($funcionarioData['usuario_id'])) {
                    $notificacaoModel = new \App\Model\NotificacaoModel();
                    
                    // Monta a mensagem (Usa a descrição do contracheque se existir, senão usa um texto padrão)
                    $descContracheque = !empty($post['descricao']) ? $post['descricao'] : 'deste mês';
                    $mensagem = "Seu contra cheque referente a {$descContracheque} já está disponível para visualização.";
                    
                    // Grava na tabela de notificações ligando ao usuario_id do funcionário
                    $notificacaoModel->criarAlerta(
                        (int)$funcionarioData['usuario_id'], 
                        $mensagem, 
                        'contracheque'
                    );
                }
            }
        return Redirect::page($this->controller, ["msgSucesso" => "Contra-Cheque criado com sucesso"]);
    }

    return Redirect::page($this->controller . "/form/insert/0");
}

    // 🔥 UPDATE
    public function update()
    {
        $post = $this->request->getPost();
        // evita erro no validator
        if (!isset($post['arquivo'])) {
            $post['arquivo'] = '';
        }
        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page($this->controller . "/form/update/" . $post['id']);
        }

        // 📄 NOVO PDF
        if (!empty($_FILES['arquivo']['name'])) {

            $ext = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);

            if ($ext != 'pdf') {
                Session::set('msgErro', 'Apenas PDF permitido');
                return Redirect::page($this->controller . "/form/update/" . $post['id']);
            }

            $retorno = $this->files->upload($_FILES, 'contracheques');

            if (is_bool($retorno)) {
                Session::set('inputs', $post);
                return Redirect::page($this->controller . "/form/update/" . $post['id']);
            }

            $post['arquivo'] = $retorno[0];

            // 🔥 remove antigo
            if (!empty($post['nomeArquivo'])) {
                $this->files->delete($post['nomeArquivo'], 'contracheques');
            }

        } else {
            $post['arquivo'] = $post['nomeArquivo'];
        }

        unset($post['nomeArquivo']);

        if ($this->model->update($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "ContraCheque atualizado com sucesso"]);
        }

        return Redirect::page($this->controller . "/form/update/" . $post['id']);
    }

    // 📥 DOWNLOAD
    public function download($id)
{
    $contracheque = $this->model->getById($id);

    if (!$contracheque || empty($contracheque['arquivo'])) {
        die("Arquivo não encontrado");
    }

    // 🔥 caminho correto usando raiz do projeto
    $basePath = realpath(__DIR__ . "/../../");
    $caminho  = $basePath . "/uploads/contracheques/" . $contracheque['arquivo'];

    if (!file_exists($caminho)) {
        die("Arquivo não existe");
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="contracheque.pdf"'); // 👈 abre no navegador
    readfile($caminho);
    exit;
}

    // 🗑 DELETE
    public function delete()
    {
        $post = $this->request->getPost();

        if (!empty($post['nomeArquivo'])) {
            $this->files->delete($post['nomeArquivo'], 'contracheques');
        }

        if ($this->model->delete($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "ContraCheque excluído"]);
        }

        return Redirect::page($this->controller);
    }
}