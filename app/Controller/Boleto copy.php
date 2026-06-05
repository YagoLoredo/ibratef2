<?php

namespace App\Controller;

use App\Model\BoletoModel;
use App\Model\UsuarioModel;
use Core\Library\ControllerMain;
use Core\Library\Files;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;

class Boleto extends ControllerMain
{
    protected $files;

    public function __construct()
    {
        $this->auxiliarConstruct();
        $this->loadHelper('formHelper');

        $this->model = new BoletoModel();
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

    if ((int)$userNivel === 21) {
        $filtros["usuario_id"] = $userId;
    }

    $boletos = $this->model->listaBoleto($filtros);

    return $this->loadView("sistema/listaBoleto", [
        "boletos" => $boletos,
        "filtros" => $filtros
    ]);
}
    

    // 📄 FORM
    public function form($action, $id = null)
{
    $UsuarioModel = new UsuarioModel();

    $dados = [
        "data" => $this->model->getById($id),
        "aUsuario" => $UsuarioModel->lista2()
    ];

    return $this->loadView("sistema/formBoleto", $dados);
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
        $retorno = $this->files->upload($_FILES, 'boletos');

        if (is_bool($retorno)) {
            Session::set('inputs', $post);
            return Redirect::page($this->controller . "/form/insert/0");
        }

        $post['arquivo'] = $retorno[0];
    }

    if ($this->model->insert($post)) {
        return Redirect::page($this->controller, ["msgSucesso" => "Boleto criado com sucesso"]);
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

            $retorno = $this->files->upload($_FILES, 'boletos');

            if (is_bool($retorno)) {
                Session::set('inputs', $post);
                return Redirect::page($this->controller . "/form/update/" . $post['id']);
            }

            $post['arquivo'] = $retorno[0];

            // 🔥 remove antigo
            if (!empty($post['nomeArquivo'])) {
                $this->files->delete($post['nomeArquivo'], 'boletos');
            }

        } else {
            $post['arquivo'] = $post['nomeArquivo'];
        }

        unset($post['nomeArquivo']);

        if ($this->model->update($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "Boleto atualizado com sucesso"]);
        }

        return Redirect::page($this->controller . "/form/update/" . $post['id']);
    }

    // 📥 DOWNLOAD
    public function download($id)
{
    $boleto = $this->model->getById($id);

    if (!$boleto || empty($boleto['arquivo'])) {
        die("Arquivo não encontrado");
    }

    // 🔥 caminho correto usando raiz do projeto
    $basePath = realpath(__DIR__ . "/../../");
    $caminho  = $basePath . "/uploads/boletos/" . $boleto['arquivo'];

    if (!file_exists($caminho)) {
        die("Arquivo não existe");
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="boleto.pdf"'); // 👈 abre no navegador
    readfile($caminho);
    exit;
}

    // 🗑 DELETE
    public function delete()
    {
        $post = $this->request->getPost();

        if (!empty($post['nomeArquivo'])) {
            $this->files->delete($post['nomeArquivo'], 'boletos');
        }

        if ($this->model->delete($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "Boleto excluído"]);
        }

        return Redirect::page($this->controller);
    }
}