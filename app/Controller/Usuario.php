<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;

class Usuario extends ControllerMain
{
    /**
     * construct
     */
    public function __construct()
    {
        $this->auxiliarConstruct();
        $this->loadHelper(['formHelper', 'tabela']);
    }
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->loadView("sistema/listaUsuario", $this->model->lista("nome"));
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
        $userId    = Session::get("userId");
        $userNivel = Session::get("userNivel");

        $dados = [];

        if ($action === "insert") {
            // Dados padrão para novo usuário
            $dados = [
                "nivel"          => 21,
                "trocarSenha"    => "S",
                "statusRegistro" => 1
            ];
        } else {
            // Busca o usuário existente
            $usuario = $this->model->getById($id);

            if (!$usuario) {
                return Redirect::page($this->controller, ["msgErro" => "Usuário não encontrado."]);
            }

            // Restrição de edição para usuários com nível 21
            if ($userNivel == 21 && $userId != $id) {
                return Redirect::page($this->controller, ["msgErro" => "Você não tem permissão para acessar este usuário."]);
            }

            $dados = $usuario;
        }

        // Adiciona a ação do formulário para usar na view (ex: insert, update, view)
        $dados["form_action"] = $action;

        // Passa os dados para a view
        return $this->loadView("sistema/formUsuario", ['data' => $dados]);
    }

    /**
     * save
     *
     * @return void
     */
    public function insert()
    {        
        $post = $this->request->getPost();
        $lError = false;

        // Validação da senha
        if (empty($post['senha'])) {
            $lError = true;
            $errors['senha'] = "O campo <b>Senha</b> deve ser preenchido.";
            Session::set('errors', $errors);
        } else {
            unset($post['confSenha']);
            $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
        }

        if (!$lError) {
            // 1. Insere o Usuário e pega o ID real
            $usuario_id = $this->model->insert($post);

            if ($usuario_id) {
                
                // Dados básicos para as tabelas relacionadas
                $dadosRelacionados = [
                    "nome"       => $post['nome'],
                    "email"      => $post['email'],
                    "usuario_id" => $usuario_id,
                    "statusRegistro" => 1
                ];

                // 2. Lógica de Redirecionamento por Nível
                if ($post['nivel'] == 21) {
                    // Cadastra em CLIENTES
                    $clienteModel = new \App\Model\ClienteModel();
                    $clienteModel->insert($dadosRelacionados);
                } 
                else if ($post['nivel'] == 11) { // Nível de Administrador
                    // Cadastra em FUNCIONARIOS
                    $funcionarioModel = new \App\Model\FuncionarioModel();
                    $funcionarioModel->insert($dadosRelacionados);
                }

                return Redirect::page($this->controller, ["msgSucesso" => "Usuário e perfil criados com sucesso."]);
            } else {
                $lError = true;
            }    
        }

        if ($lError) {
            Session::set("inputs", $post);
            return Redirect::page($this->controller . '/form/' . $post['action'] . '/' . $post['id']);
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
        $userIdLogado = Session::get("userId");
        $lError = false;

        unset($post['confSenha']);

        // Tratamento da senha
        if (empty($post['senha'])) {
            unset($post['senha']);
        } else {
            $post['senha'] = password_hash($post['senha'], PASSWORD_DEFAULT);
        }

        if (!$lError) {
            // 1. Atualiza o Usuário
            if ($this->model->update($post)) {
                
                // ==========================================
                // 2. SINCRONIZAR COM A TABELA DE CLIENTES
                // ==========================================
                $clienteModel = new \App\Model\ClienteModel();
                
                $dadosCliente = [
                    "nome"  => $post['nome'],
                    "email" => $post['email']
                ];

                $clienteModel->db->where("usuario_id", $post['id'])->update($dadosCliente);
                
                // 🔥 SEGURANÇA E CACHE: Só atualiza a sessão se o usuário modificado for ELE MESMO
                if ((int)$userIdLogado === (int)$post['id']) {
                    Session::set('userNome', $post['nome']);
                    // Busca a foto atualizada do banco para garantir consistência
                    $usuarioAtualizado = $this->model->getById($post['id']);
                    if (!empty($usuarioAtualizado['foto'])) {
                        Session::set('userFoto', $usuarioAtualizado['foto']);
                    }
                }
                // ==========================================

                return Redirect::page($this->controller, ["msgSucesso" => "Registro atualizado com sucesso."]);
            } else {
                $lError = true;
            }    
        }

        if ($lError) {
            Session::set("inputs", $post);
            return Redirect::page($this->controller . '/form/' . $post['action'] . '/' . $post['id']);
        }
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
            return Redirect::page($this->controller, ['msgSucesso' => "Registro excluído com sucesso."]);
        } else {
            return Redirect::page($this->controller . "/form/new/0", ["msgError" => "Falha ao excluir os dados na base de dados."]);
        }
    }

    /**
     * Exibe a tela de perfil do usuário logado
     * @return void
     */
    public function perfil()
    {
        $userId = Session::get("userId");
        if (!$userId) {
            return Redirect::page("Login", ["msgError" => "Faça login para acessar o perfil."]);
        }

        // Busca os dados reais e atualizados do banco
        $usuario = $this->model->getById($userId);

        if (!$usuario) {
            return Redirect::page("Home", ["msgError" => "Usuário não localizado."]);
        }

        return $this->loadView('sistema/formPerfil', ['data' => $usuario]);
    }

    
     
    /**
     * Processa a atualização do perfil combinando os dados atuais
     * @return void
     */
    /**
     * Altera o perfil do usuário garantindo isolamento total por ID
     * @return void
     */
    /**
     * Altera o perfil do usuário garantindo isolamento total via SQL Puro (PDO)
     * @return void
     */
    /**
     * Altera o perfil do usuário de forma isolada seguindo o padrão do ModelMain
     * @return void
     */
    public function salvarPerfil()
    {
        // 1. ID estritamente seguro vindo da sessão do usuário logado
        $userId = \Core\Library\Session::get("userId");
        if (!$userId) {
            return \Core\Library\Redirect::page("Login", ["msgError" => "Sessão expirada."]);
        }

        $postData = $this->request->getPost();
        
        // 2. Busca o registro completo atual deste usuário
        $usuarioAtual = $this->model->getById($userId);
        if (!$usuarioAtual) {
            return \Core\Library\Redirect::page("Home", ["msgError" => "Usuário não encontrado."]);
        }

        // Validação usando o método dinâmico de regras que criamos na Model
        if (\Core\Library\Validator::make($postData, $this->model->getRulesPerfil())) {
            return \Core\Library\Redirect::page("Usuario/perfil", ["msgError" => "Dados inválidos."]);
        }

        $nomeFotoSalvar = !empty($usuarioAtual['foto']) ? $usuarioAtual['foto'] : null;

        // Processamento do upload da foto de perfil
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extensao, $extensoesPermitidas)) {
                $nomeFotoSalvar = "perfil_" . $userId . "_" . time() . "." . $extensao;
                $diretorioDestino = dirname(__DIR__, 2) . "/public/assets/img/perfis/";

                if (!is_dir($diretorioDestino)) {
                    mkdir($diretorioDestino, 0755, true);
                }

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $diretorioDestino . $nomeFotoSalvar)) {
                    if (!empty($usuarioAtual['foto']) && file_exists($diretorioDestino . $usuarioAtual['foto'])) {
                        @unlink($diretorioDestino . $usuarioAtual['foto']);
                    }
                }
            }
        }

        // 🔥 A CHAVE DO SUCESSO:
        // Remontamos o array injetando obrigatoriamente a chave primária 'id'.
        // É isso que o ModelMain exige para criar a cláusula WHERE nos bastidores!
        $dadosParaAtualizar = [
            "id"    => $userId, // A chave primária é OBRIGATÓRIA para o update funcionar
            "nome"  => $postData['nome'],
            "email" => $postData['email'],
            "foto"  => $nomeFotoSalvar
        ];

        // Executa o update nativo do framework passando a chave primária mapeada
        if ($this->model->update($dadosParaAtualizar)) {
            
            // Sincronização com a tabela vinculada de clientes
            $clienteModel = $this->loadModel("Cliente");
            if ($clienteModel) {
                $dadosCliente = [
                    "nome"  => $dadosParaAtualizar['nome'],
                    "email" => $dadosParaAtualizar['email'],
                    "foto"  => $dadosParaAtualizar['foto']
                ];
                
                // 🔥 SOLUÇÃO: Usamos o construtor de consultas direto no banco do cliente, 
                // forçando o WHERE no 'usuario_id' sem passar pelo update cego da ModelMain!
                $clienteModel->db->where('usuario_id', $userId)->update($dadosCliente);
            }

            // Atualiza os dados da sessão ativa do usuário logado
            \Core\Library\Session::set('userNome', $dadosParaAtualizar['nome']);
            \Core\Library\Session::set('userFoto', $dadosParaAtualizar['foto']);
            
            return \Core\Library\Redirect::page("Usuario/perfil", ["msgSucesso" => "Perfil atualizado com sucesso."]);
        } else {
            return \Core\Library\Redirect::page("Usuario/perfil", ["msgSucesso" => "Perfil verificado ou atualizado."]);
        }
    }
    /**
     * updateNovaSenha
     *
     * @return void
     */
    public function updateNovaSenha() 
    {
        $post       = $this->request->getPost();
        $userAtual  = $this->model->getById($post["id"]);

        if ($userAtual) {

            if (password_verify(trim($post["senhaAtual"]), $userAtual['senha'])) {

                if (trim($post["novaSenha"]) == trim($post["novaSenha2"])) {

                    $novaSenhaCripyt = password_hash(trim($post["novaSenha"]), PASSWORD_DEFAULT);

                    $lUpdate = $this->model->db->where(['id' => $post['id']])->update([
                        'senha' => $novaSenhaCripyt
                    ]);
                        
                    if ($lUpdate) {
                        // Atualiza sessão de senhas
                        Session::set("userSenha", $novaSenhaCripyt);

                        return Redirect::page("Usuario/formTrocarSenha", [
                            "msgSucesso"    => "Senha alterada com sucesso !"
                        ]);  
                    } else {
                        return Redirect::page("Usuario/formTrocarSenha");    
                    }

                } else {
                    return Redirect::page("Usuario/formTrocarSenha", [
                        "msgError"    => "Nova senha e conferência da senha estão divergentes !"
                    ]);                  
                }

            } else {
                return Redirect::page("Usuario/formTrocarSenha", [
                    "msgError"    => "Senha atual informada não confere!"
                ]);               
            }
        } else {
            return Redirect::page("Usuario/formTrocarSenha", [
                "msgError"    => "Usuário inválido !"
            ]);   
        }
    }

    public function excluirConta()
    {
        $userId = Session::get("userId");

        if (!$userId) {
            return Redirect::page("Login", [
                "msgError" => "Usuário não autenticado."
            ]);
        }

        $post = $this->request->getPost();

        // Buscar usuário atual
        $usuario = $this->model->getById($userId);

        if (!$usuario) {
            return Redirect::page("Home", [
                "msgError" => "Usuário não encontrado."
            ]);
        }

        // validar senha
        if (!password_verify(trim($post['senha']), $usuario['senha'])) {
            return Redirect::page("Usuario/perfil", [
                "msgError" => "Senha incorreta."
            ]);
        }

        // INATIVAR CONTA (AO INVÉS DE DELETAR)
        $update = $this->model->update([
            "id" => $userId,
            "nome"            => $usuario['nome'],
            "email"           => $usuario['email'],
            "nivel"           => $usuario['nivel'],
            "statusRegistro"  => 2
        ]);

        if ($update) {
            // salva mensagem ANTES
            Session::set("msgSucesso", "Conta desativada com sucesso.");

            // remove só dados do usuário
            Session::destroy("userId");
            Session::destroy("userNome");
            Session::destroy("userNivel");
            Session::destroy("userFoto");

            return Redirect::page("Login");
        } else {
            Session::set("msgError", "Erro ao desativar conta.");
            return Redirect::page("Login");
        }
    }

    
        
    public function registraUsuario()
    {
        $post = $this->request->getPost();

        // valida campos obrigatórios
        if (
            empty($post['register-name']) ||
            empty($post['register-email']) ||
            empty($post['register-password']) ||
            empty($post['confirm-register-password'])
        ) {
            return Redirect::page("Home/Cadastro", [
                "msgError" => "Preencha todos os campos.",
                "inputs" => $post
            ]);
        }

        // valida senha
        if ($post['register-password'] !== $post['confirm-register-password']) {
            return Redirect::page("Home/Cadastro", [
                "msgError" => "As senhas não coincidem.",
                "inputs" => $post
            ]);
        }

        // verifica email
        $usuarioExistente = $this->model->getUserEmail($post['register-email']);
        if ($usuarioExistente) {
            return Redirect::page("Home/Cadastro", [
                "msgError" => "Email já cadastrado.",
                "inputs" => $post
            ]);
        }

        // LGPD
        if (!isset($post['lgpd'])) {
            return Redirect::page("Home/Cadastro", [
                "msgError" => "Você precisa aceitar os termos.",
                "inputs" => $post
            ]);
        }

        // USUÁRIO
        $dados = [
            "nome"           => trim($post['register-name']),
            "email"          => trim($post['register-email']),
            "senha"          => password_hash(trim($post['register-password']), PASSWORD_DEFAULT),
            "nivel"          => 21,
            "statusRegistro" => 1,
            "lgpd_aceito"    => 1,
            "lgpd_data"      => date("Y-m-d H:i:s")
        ];

        // INSERT USUÁRIO
        $idUsuario = $this->model->insert($dados);

        if ($idUsuario) {
            $ClienteModel = $this->loadModel("Cliente");

            $ClienteModel->insert([
                "usuario_id" => $idUsuario,
                "nome"       => trim($post['register-name']),
                "email"      => trim($post['register-email']),
                "status"     => 1
            ]);

            return Redirect::page("Login", [
                "msgSucesso" => "Usuário registrado com sucesso! Faça login."
            ]);
        }

        return Redirect::page("Home/Cadastro", [
            "msgError" => "Erro ao registrar usuário.",
            "inputs" => $post
        ]);
    }
}