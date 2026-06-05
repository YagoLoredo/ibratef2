<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Email;


class Cliente extends ControllerMain
{
    public function __construct()
    {
        $this->auxiliarconstruct();
        $this->loadHelper('formHelper');
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");

        // Se for usuário comum (nível 21), mostra apenas os clientes dele
        if ($userNivel == 21) {
            $clientes = $this->model->listarTodos(["usuario_id" => $userId]);
        } else {
            $clientes = $this->model->lista("nome");
        }

        return $this->loadView("sistema/listaCliente", $clientes);
    }

    public function form($action, $id = null)
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");

        $data = $this->model->getById($id);

        // Se for edição, verificar permissões
        if ($action === 'update') {
            if (!$data) {
                return Redirect::page($this->controller, ["msgErro" => "Cliente não encontrado."]);
            }

            if ($userNivel == 21 && $data["usuario_id"] != $userId) {
                return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para acessar este cliente."]);
            }
        }

        $dados = [
            "data" => $data,
        ];

        return $this->loadView("sistema/formCliente", $dados);
    }

    /**
     * insert
     *
     * @return void
     */
    public function insert()
{
    $post = $this->request->getPost();

    // 🔐 senha automática
    $senhaGerada = bin2hex(random_bytes(4));
    $senhaHash   = password_hash($senhaGerada, PASSWORD_DEFAULT);

    try {

        // =========================
        // 1. CRIAR USUÁRIO
        // =========================
        $usuarioModel = new \App\Model\UsuarioModel();

        $dadosUsuario = [
            "nome"           => trim($post['nome']),
            "email"          => trim($post['email']),
            "senha"          => $senhaHash,
            "nivel"          => 21,
            "statusRegistro" => 1
        ];

        $usuario_id = $usuarioModel->insert($dadosUsuario);

        if (!$usuario_id) {
            return Redirect::page($this->controller . "/form/insert/0", [
                "msgError" => "Erro ao criar usuário."
            ]);
        }

        // =========================
        // 2. CRIAR CLIENTE
        // =========================
        $post["usuario_id"] = $usuario_id;

        if (!$this->model->insert($post)) {
            return Redirect::page($this->controller . "/form/insert/0", [
                "msgError" => "Erro ao criar cliente."
            ]);
        }

        // =========================
        // 3. ENVIAR EMAIL
        // =========================
        $mensagem = "
        Olá, {$post['nome']}!<br><br>

        Seu acesso ao sistema foi criado com sucesso.<br><br>

        <b>Email:</b> {$post['email']}<br>
        <b>Senha:</b> {$senhaGerada}<br><br>

        Recomendamos que altere sua senha após o primeiro acesso.<br><br>

        Atenciosamente,<br>
        Equipe Ibratef
        ";

        Email::enviaEmail(
            $_ENV['MAIL.USER'],
            "Sistema",
            "Acesso ao sistema",
            $mensagem,
            $post["email"]
        );

        return Redirect::page($this->controller, [
            "msgSucesso" => "Cliente cadastrado com acesso enviado por email."
        ]);

    } catch (\PDOException $e) {

        if ($e->errorInfo[1] == 1062) {
            return Redirect::page($this->controller . "/form/insert/0", [
                "msgError" => "E-mail já cadastrado."
            ]);
        }

        return Redirect::page($this->controller . "/form/insert/0", [
            "msgError" => "Erro: " . $e->getMessage()
        ]);
    }
}


    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");
        $post      = $this->request->getPost();

        $data = $this->model->getById($post["id"]);
        if (!$data) {
            return Redirect::page($this->controller, ["msgErro" => "Cliente não encontrado."]);
        }

        if ($userNivel == 21 && $data["usuario_id"] != $userId) {
            return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para alterar este cliente."]);
        }

        if ($this->model->update($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "Registro alterado com sucesso."]);
        } else {
            return Redirect::page($this->controller . "/form/update/" . $post['id']);
        }
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");
        $post      = $this->request->getPost();

        $data = $this->model->getById($post["id"]);
        if (!$data) {
            return Redirect::page($this->controller, ["msgErro" => "Cliente não encontrado."]);
        }

        if ($userNivel == 21 && $data["usuario_id"] != $userId) {
            return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para excluir este cliente."]);
        }

        if ($this->model->delete($post)) {
            return Redirect::page($this->controller, ["msgSucesso" => "Registro Excluído com sucesso."]);
        } else {
            return Redirect::page($this->controller);
        }
    }
}
