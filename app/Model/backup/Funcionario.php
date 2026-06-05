<?php

namespace App\Controller;

use App\Model\FuncionarioModel;
use Core\Library\ControllerMain;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Email;


class Funcionario extends ControllerMain
{
    /**
     * construct
     */
    public function __construct()
    {
        $this->auxiliarConstruct();
        $this->loadHelper(['formHelper', 'tabela']);
        $this->model = new FuncionarioModel();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->loadView("sistema/listaFuncionario", $this->model->lista("nome"));
    }

    /**
     * form
     *
     * @param string $action
     * @param integer $id
     * @return void
     */
    public function form($action = null, $id = null)
    {
        $dados = [];

        if ($action === "insert") {
            $dados = [
                "statusRegistro" => 1
            ];
        } else {
            $funcionario = $this->model->getById($id);

            if (!$funcionario) {
                return Redirect::page($this->controller, ["msgErro" => "Funcionário não encontrado."]);
            }

            $dados = $funcionario;
        }

        $dados["form_action"] = $action;

        return $this->loadView("sistema/formFuncionario", ['data' => $dados]);
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
        // 1. CRIAR USUÁRIO (ADMIN)
        // =========================
        $usuarioModel = new \App\Model\UsuarioModel();

        $dadosUsuario = [
            "nome"           => trim($post['nome']),
            "email"          => trim($post['email']),
            "senha"          => $senhaHash,
            "nivel"          => 11,
            "statusRegistro" => 1,
        ];

        $usuario_id = $usuarioModel->insert($dadosUsuario);

        if (!$usuario_id) {
            return Redirect::page($this->controller . "/form/insert/0", [
                "msgError" => "Erro ao criar usuário."
            ]);
        }

        // =========================
        // 2. CRIAR FUNCIONÁRIO
        // =========================
        $post["usuario_id"] = $usuario_id;

        if (!$this->model->insert($post)) {
            return Redirect::page($this->controller . "/form/insert/0", [
                "msgError" => "Erro ao criar funcionário."
            ]);
        }

        // =========================
        // 3. ENVIAR EMAIL
        // =========================
        $mensagem = "
        Olá, {$post['nome']}!<br><br>

        Seu acesso como funcionário foi criado.<br><br>

        <b>Email:</b> {$post['email']}<br>
        <b>Senha:</b> {$senhaGerada}<br><br>

        Recomendamos que altere sua senha no primeiro acesso.<br><br>

        Atenciosamente,<br>
        Equipe Ibratef
        ";

        Email::enviaEmail(
            $_ENV['MAIL.USER'],
            "Sistema",
            "Acesso ao sistema (Funcionário)",
            $mensagem,
            $post["email"]
        );

        return Redirect::page($this->controller, [
            "msgSucesso" => "Funcionário cadastrado com acesso enviado por email."
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
        $post = $this->request->getPost();
        $lError = false;

        if (empty($post['id'])) {
            return Redirect::page($this->controller, ["msgErro" => "ID inválido para atualização."]);
        }

        if (!$lError) {
            if ($this->model->update($post)) {
                return Redirect::page($this->controller, ["msgSucesso" => "Funcionário atualizado com sucesso."]);
            } else {
                $lError = true;
            }
        }

        Session::set("inputs", $post);
        return Redirect::page($this->controller . '/form/update/' . $post['id']);
    }

    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $post = $this->request->getPost();

        if ($this->model->delete(["id" => $post['id']])) {
            return Redirect::page($this->controller, ['msgSucesso' => "Funcionário excluído com sucesso."]);
        } else {
            return Redirect::page($this->controller, ["msgErro" => "Erro ao excluir funcionário."]);
        }
    }
}
