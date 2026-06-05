<?php

namespace App\Controller;

use App\Model\AgendamentoModel;
use App\Model\TipoServicoModel;
use Core\Library\ControllerMain;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;


class Agendamento extends ControllerMain
{
    public function __construct()
    {
        $this->auxiliarConstruct();
        $this->loadHelper('formHelper');
        $this->model = new AgendamentoModel();

        // Bloqueia o acesso para usuários NÃO logados
        $userId = Session::get("userId");
        if (!$userId) {
            Redirect::page("login");  // ajuste a rota para a sua página de login
            exit; // para garantir que o resto não execute
        }
    }

    public function index()
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");

        if ($userNivel == 21) {
            $agendamentos = $this->model->listarTodos(["usuario_id" => $userId]);
        } else {
            $agendamentos = $this->model->listarTodos();
        }

        return $this->loadView("sistema/listaAgendamento", ["agendamentos" => $agendamentos]);
    }

    public function form($action, $id = null)
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");

        $tipoServicoModel = new TipoServicoModel();

        $filtroAnimal = ($userNivel == 21) ? ['usuario_id' => $userId] : [];

        if ($action === 'insert') {
            $dados = [
                'data' => null,
                'aTipoServico' => $tipoServicoModel->lista("nome"),
                'modo' => 'insert'
            ];
            return $this->loadView("sistema/formAgendamento", $dados);
        }

        $data = $this->model->getById($id);
        if (!$data) {
            return Redirect::page($this->controller, ["msgErro" => "Agendamento não encontrado."]);
        }

        // Permissão: usuário comum só pode acessar seus agendamentos
        if ($userNivel == 21 && $data["usuario_id"] != $userId) {
            return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para acessar este agendamento."]);
        }

        $dados = [
            'data' => $data,
            'aTipoServico' => $tipoServicoModel->lista("nome"),
            'modo' => 'update'
        ];

        return $this->loadView("sistema/formAgendamento", $dados);
    }

    public function insert()
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");
        $post      = $this->request->getPost();

        // Se for nível 21 (Cliente), o agendamento é obrigatoriamente para ele mesmo
        if ((int)$userNivel === 21) {
            $post["usuario_id"] = $userId;
        } 
        // Se for Admin, ele usará o 'usuario_id' que escolheu no select do formulário HTML
        elseif (empty($post["usuario_id"])) {
            Session::set('msgErro', 'Selecione um usuário para o agendamento.');
            return Redirect::page($this->controller . "/form/insert/0");
        }

        // Executa a validação antes de tentar inserir no banco (Evita estouro de SQL)
        if (Validator::make($post, $this->model->validationRules)) {
            Session::set('inputs', $post);
            return Redirect::page($this->controller . "/form/insert/0");
        }

        // Tenta inserir no banco de dados
        if ($this->model->insert($post)) {
            
            // 🔔 SISTEMA DE NOTIFICAÇÃO DISPARADO AQUI (PARA ADMINS E SUPER ADMINS)
    // 🔔 SISTEMA DE NOTIFICAÇÃO DISPARADO AQUI (PARA ADMINS E SUPER ADMINS)
if (!empty($post['usuario_id'])) {
    $notificacaoModel = new \App\Model\NotificacaoModel();
    $usuarioModel = new \App\Model\UsuarioModel();
    
    // 1. Busca os dados do serviço e de QUEM está agendando
    $tipoServicoModel = new \App\Model\TipoServicoModel();
    $servico = $tipoServicoModel->getById($post['tipo_servico_id'] ?? 0);
    $usuarioQueAgendou = $usuarioModel->getById($post['usuario_id']);

    $nomeServico = $servico ? $servico['nome'] : 'solicitado';
    $nomeUsuario = $usuarioQueAgendou ? $usuarioQueAgendou['nome'] : 'Um usuário';
    
    // 2. Busca a lista de quem deve receber (níveis 1, 11 e 21)
    $admins = $usuarioModel->buscarPorNiveis([1, 11, 21]); 

    if (!empty($admins)) {
        foreach ($admins as $admin) {
            
            // Se o admin que está recebendo a notificação AGORA for o nível 21 (Superadmin)
            // E ele mesmo foi quem fez o agendamento, ele ganha a mensagem de parabéns.
            if ((int)$admin['nivel'] === 21 && (int)$admin['id'] === (int)$post['usuario_id']) {
                $mensagem = "Parabéns! Você realizou um novo agendamento para o serviço de ({$nomeServico})!";
            } else {
                // Para os outros admins/superadmins ficarem sabendo quem foi que agendou
                $mensagem = "Novo agendamento realizado por {$nomeUsuario} para o serviço de ({$nomeServico})!";
            }
            
            // Grava a notificação personalizada para o ID deste administrador específico
            $notificacaoModel->criarAlerta(
                (int)$admin['id'], 
                $mensagem, 
                'agendamento_admin'
            );
        }
    }
}
            
            return Redirect::page($this->controller, ["msgSucesso" => "Agendamento criado com sucesso."]);
        } else {
            return Redirect::page($this->controller . "/form/insert/0");
        }
    }

    public function update()
{
    $userId    = Session::get("userId");
    $userNivel = Session::get("userNivel");
    $post      = $this->request->getPost();

    $data = $this->model->getById($post["id"]);
    if (!$data) {
        return Redirect::page($this->controller, ["msgErro" => "Agendamento não encontrado."]);
    }

    if ($userNivel == 21 && $data["usuario_id"] != $userId) {
        return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para alterar este agendamento."]);
    }

    if (Validator::make($post, $this->model->validationRules)) {
        Session::set('inputs', $post); // se quiser preservar dados no form
        return Redirect::page($this->controller . "/form/update/" . $post["id"]);
    }

    if ($this->model->update($post)) {
        return Redirect::page($this->controller, ["msgSucesso" => "Agendamento atualizado com sucesso."]);
    } else {
        return Redirect::page($this->controller . "/form/update/" . $post["id"]);
    }
}



    public function delete()
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");
        $post      = $this->request->getPost();

        $data = $this->model->getById($post["id"]);
        if (!$data) {
            return Redirect::page($this->controller, ["msgErro" => "Agendamento não encontrado."]);
        }

        if ($userNivel == 21 && $data["usuario_id"] != $userId) {
            return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para excluir este agendamento."]);
        }

        if ($this->model->delete($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "Agendamento excluído com sucesso."]);
        } else {
            return Redirect::page($this->controller, ["msgErro" => "Erro ao excluir o agendamento."]);
        }
    }
    /**
 * Rota para exclusão dinâmica via AJAX (Fetch API)
 * Caminho mapeado: Agendamento/excluirDinamico/{id}
 */

}
