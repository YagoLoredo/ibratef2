<?php

namespace App\Controller;

use App\Model\UsuarioModel;
use Core\Library\ControllerMain;
use Core\Library\Email;
use Core\Library\Redirect;
use Core\Library\Session;

class Login extends ControllerMain
{
    /**
     * construct
     */
    public function __construct()
    {
        $this->auxiliarConstruct();
        $this->model = new UsuarioModel();
        $this->loadHelper("formHelper");
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->loadView("login/login", []);
    }

    /**
     * signIn
     *
     * @return void
     */
    public function signIn()
{
    $post   = $this->request->getPost();
    $aUser  = $this->model->getUserEmail($post['email']);

    if (count($aUser) > 0) {

        // 1. Validar a senha
        if (!password_verify(trim($post["senha"]), trim($aUser['senha']))) {
            return Redirect::page("login", [
                "msgError" => 'Login ou senha inválido.',
                "inputs" => ["email" => $post["email"]]
            ]);
        }

        // 2. Validar status do usuário
        if ($aUser["statusRegistro"] == 2) {
            return Redirect::page("login", [
                "msgError" => "Usuário Inativo, não será possível prosseguir!",
                "inputs" => ["email" => $post["email"]]
            ]);
        }

        // ============================================================
        // 🔥 LÓGICA CORRIGIDA: CRIAÇÃO AUTOMÁTICA DE CLIENTE NO LOGIN
        // ============================================================
        if ((int)$aUser["nivel"] === 21) {
            $clienteModel = new \App\Model\ClienteModel();
    
        // Tentando buscar o cliente pelo usuario_id
        // Se o seu sistema não aceita o método ->get(), vamos usar o 'find' ou 'lista'
            $clienteExistente = $clienteModel->db->where("usuario_id", $aUser["id"])->first(); 

        // Se o código acima ainda der erro de "method undefined", tente:
        // $clienteExistente = $clienteModel->getByIdUsuario($aUser["id"]); 
        // (Desde que você crie esse método no ClienteModel)

        if (!$clienteExistente) {
            $clienteModel->insert([
                "usuario_id"     => $aUser["id"],
                "nome"           => $aUser["nome"],
                "email"          => $aUser["email"],
                "statusRegistro" => 1
             ]);
            }
        }
// ============================================================
        // ============================================================

        // 3. Criar flags de usuário logado
        Session::set("userId", $aUser["id"]);
        Session::set("userNome", $aUser["nome"]);
        Session::set("userEmail", $aUser["email"]);
        Session::set("userNivel", $aUser["nivel"]);
        Session::set("userSenha", $aUser["senha"]);

        // 4. Verificar nível e redirecionar
        if ((int)$aUser["nivel"] === 1) {
            return Redirect::page("sistema"); 
        } else {
            return Redirect::page("Home/site"); 
        }

    } else {
        return Redirect::page("login", [
            "msgError" => "Login ou senha inválido.",
            "inputs" => ["email" => $post["email"]],
        ]);
    }
}

    /**
     * signOut
     *
     * @return void
     */
    public function signOut()
    {
        Session::destroy('userId');
        Session::destroy('userNome');
        Session::destroy('userEmail');
        Session::destroy('userNivel');
        Session::destroy('userSenha');
        
        return Redirect::Page("home");
    }

    /**
     * formEsqueciASenha
     *
     * @return void
     */
    public function esqueciASenha()
    {
        return $this->loadView("login/esqueciASenha");
    }

    /**
     * esqueciASenhaEnvio
     *
     * @return void
     */
     public function esqueciASenhaEnvio()
    {
        $this->loadHelper("emailHelper");

        $post       = $this->request->getPost();
        $user       = $this->model->getUserEmail($post['email']);

        if (!$user) {

            return Redirect::page("Login/esqueciASenha", [
                "msgError" => "Não foi possivel localizar o e-mail na base de dados !"
            ]);

        } else {

            $created_at = date('Y-m-d H:i:s');
            $chave      = sha1($user['id'] . $user['senha'] . date('YmdHis', strtotime($created_at)));
            $cLink      = baseUrl() . "login/recuperarSenha/" . $chave;
            $emailTexto = emailRecuperacaoSenha($cLink);

            $lRetMail = Email::enviaEmail(
                $_ENV['MAIL.USER'],                         /* Email do Remetente*/
                $_ENV['MAIL.NOME'],                         /* Nome do Remetente */
                $emailTexto['assunto'],                     /* Assunto do e-mail */
                $emailTexto['corpo'],                       /* Corpo do E-mail */
                $user['email']                              /* Destinatário do E-mail */
            );

            if ($lRetMail) {

                // Gravar o link no banco de dados
                $usuarioRecuperaSenhaModel = $this->loadModel("UsuarioRecuperaSenha");

                // Desativando solicitações antigas
                $usuarioRecuperaSenhaModel->desativaChaveAntigas($user["id"]);

                // Inserindo nova solicitação
                $resIns = $usuarioRecuperaSenhaModel->db->table('usuariorecuperasenha')->insert([
                    "usuario_id" => $user["id"], 
                    "chave" => $chave,
                    "created_at" => $created_at
                ]);

                if ($resIns) {
                    return Redirect::page("login", [
                        "msgSucesso" => "Link para recuperação da senha enviado com sucesso! Verifique seu e-mail."
                    ]);   
                } else {
                    return Redirect::page("login/esqueciASenha");   
                }

            } else {
                return Redirect::page("login/esqueciASenha", ["inputs" => $post ]);
            }
        }
    }

    /**
     * recuperarSenha
     *
     * @param string $chave 
     * @return void
     */
    public function recuperarSenha($chave = null)
    {
        $usuarioRecuperaSenhaModel  = $this->loadModel('UsuarioRecuperaSenha');
        $userChave                  = $usuarioRecuperaSenhaModel->getRecuperaSenhaChave($chave);

        if ($userChave) {

            if (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s" , strtotime("+1 hours" , strtotime($userChave['created_at'])))) {

                $usuarioModel = $this->loadModel('Usuario');
                $user           = $usuarioModel->getById($userChave['usuario_id']);

                if ($user) {

                    $chaveRecSenha = sha1($userChave['usuario_id'] . $user['senha'] . date("YmdHis", strtotime($userChave['created_at'])));
                    

                    if ($chaveRecSenha == $userChave['chave']) {

                        $_SESSION['dados_recupera'] = [
                        "id"                      => $user['id'],
                        'nome'                    => $user['nome'],
                        'usuariorecuperasenha_id' => $userChave['id']
                        ];

                        Session::destroy("msgError");

                        // chave válida
                        return $this->loadView("login/recuperarSenha");                        
                        //

                    } else {
                        // Desativa chave
                        $upd = $usuarioRecuperaSenhaModel->desativaChave($userChave['id']);

                        return Redirect::page("Login/esqueciASenha", [
                            "msgError" => "Link de recuperação da senha inválida."
                        ]); 
                    }

                } else {

                    // Desativa chave
                    $upd = $usuarioRecuperaSenhaModel->desativaChave($userChave['id']);

                    return Redirect::page("Login/esqueciASenha", [
                        "msgError" => "Usuário para o link de recuperação da senha não localizado."
                    ]); 

                }
                
            } else {

                // Desativa chave
                $upd = $usuarioRecuperaSenhaModel->desativaChave($userChave['id']);

                return Redirect::page("Login/esqueciASenha", [
                    "msgError" => "Link de recuperação da senha expirada."
                ]); 
            }

        } else {
            return Redirect::page("Login/esqueciASenha", [
                "msgError" => "Link de recuperação da senha não localizada."
            ]);             
        }
    }

    /**
     * atualizaRecuperaSenha
     *
     * @return void
     */
    public function atualizaRecuperaSenha()
    {
        $UsuarioModel = $this->loadModel("Usuario");

        $post       = $this->request->getPost();
        $userAtual  = $UsuarioModel->getById($post["id"]);

        if ($userAtual) {

            if (trim($post["NovaSenha"]) == trim($post["NovaSenha2"])) {

                if ($UsuarioModel->db
                                ->table("usuario")
                                ->where(['id' => $post['id']])
                                ->update([
                                    'senha'      => password_hash(trim($post["NovaSenha"]), PASSWORD_DEFAULT)
                                ])
                    ) {

                    // Desativa chave
                    $usuarioRecuperaSenhaModel = $this->loadModel('UsuarioRecuperaSenha');

                    $upd = $usuarioRecuperaSenhaModel->desativaChave($post['usuariorecuperasenha_id']);

                    Session::destroy("msgError");
                    return Redirect::page("Login", [
                        "msgSuccesso"    => "Senha atualizada com sucesso !"
                    ]);  

                } else {
                    return $this->loadView("login/recuperarSenha", $post);
                }

            } else {
                Session::set("msgError", "Nova senha e conferência da senha estão divergentes !");
                return $this->loadView("login/recuperarSenha", $post);
            }

        } else {
            Session::set("msgError", "Usuário inválido !");
            return $this->loadView("login/recuperarSenha", $post);
        }
    }


    /**
     * criaSuperUser
     *
     * @return void
     */
    public function criaSuperUser()
    {
        $dados = [
            "nivel"             => 1,
            "nome"              => "Yago Lorêdo",
            "email"             => "admin@ibratef.com.br",
            "senha"             => password_hash("ibratef2026", PASSWORD_DEFAULT),
            "statusRegistro"    => 1
        ];

        $aSuperUser = $this->model->getUserEmail($dados['email']);

        if (count($aSuperUser) > 0) {
            return Redirect::Page("login", ["msgError" => "Login já existe."]);
        } else {
            if ($this->model->insert($dados)) {
                return Redirect::Page("login", ["msgSucesso" => "Login criado com sucesso."]);
            } else {
                return Redirect::Page("login");
            }
        }
    }
    /**
 * criaVisitante
 *
 * @return void
 */
public function criaVisitante()
{
    $post = $this->request->getPost();

    // 1. Validações Básicas
    if (empty($post['register_name']) || empty($post['register_email']) || empty($post['register_password'])) {
        return Redirect::page("login", ["msgError" => "Preencha todos os campos."]);
    }

    // 2. Preparar dados para a tabela USUARIO
    $dadosUsuario = [
        "nivel"          => 21, // Define como Nível Cliente
        "nome"           => trim($post["register_name"]),
        "email"          => trim($post["register_email"]),
        "senha"          => password_hash(trim($post["register_password"]), PASSWORD_DEFAULT),
        "statusRegistro" => 1
    ];

    // 3. Inserir Usuário e capturar o ID gerado
    // (Importante: seu ModelMain deve retornar o lastInsertId como ajustamos antes)
    $usuario_id = $this->model->insert($dadosUsuario);

    if ($usuario_id) {
        
        // 4. CRIAR O CLIENTE AO MESMO TEMPO
        $clienteModel = new \App\Model\ClienteModel();
        
        $dadosCliente = [
            "nome"       => $dadosUsuario['nome'],
            "email"      => $dadosUsuario['email'],
            "usuario_id" => $usuario_id, // Aqui cria o vínculo entre as tabelas
            "statusRegistro" => 1
        ];

        if ($clienteModel->insert($dadosCliente)) {
            return Redirect::page("login", [
                "msgSucesso" => "Sua conta foi criada com sucesso! Agora você já pode logar."
            ]);
        } else {
            return Redirect::page("login", ["msgError" => "Usuário criado, mas erro ao gerar perfil de cliente."]);
        }

    } else {
        return Redirect::page("login", ["msgError" => "Erro ao criar conta de usuário."]);
    }
}



}
