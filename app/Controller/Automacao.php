<?php

namespace App\Controller;

use App\Model\CategoriaModel;
use App\Model\AutomacaoModel;
use App\Model\ComentarioModel;
use Core\Library\ControllerMain;
use Core\Library\Files;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;

class Automacao extends ControllerMain
{
    protected $files;

    public function __construct()
    {
        $this->auxiliarconstruct();
        $this->loadHelper('formHelper');
                $this->files = new Files();

    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return $this->loadView("sistema\listaAutomacao", $this->model->listaAutomacao('nome'));
    }

    /**
     * form
     *
     * @param string $action 
     * @param int $id 
     * @return void
     */
    public function form($action, $id)
    {
        $CategoriaModel = new CategoriaModel();

        $dados = [
            "data" => $this->model->getById($id),
            "aCategoria" => $CategoriaModel->lista()
        ];

        return $this->loadView("sistema/formAutomacao", $dados);
    }

    /**
     * insert
     *
     * @return void
     */
    public function insert()
    {
        $post = $this->request->getPost();

        
        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page($this->controller . "/form/insert/0");
        } else {

            // faz upload da imagem

            if (!empty($_FILES['imgautomacao']['name'])) {
                
                // Faz upload da imagem
                $nomeRetornado = $this->files->upload($_FILES, 'automacao');

                // se for boolean, significa que o upload falhou
                if (is_bool($nomeRetornado)) {
                    Session::set('inputs', $post);
                    return Redirect::page($this->controller . "/form/insert/" . $post['id']);
                } else {
                    $post['imgautomacao'] = $nomeRetornado[0];
                }
            } else {
                $post['imgautomacao'] = $post['nomeImagem'];
            }
            //

            if ($this->model->insert($post)) {
                return Redirect::page($this->controller, ["msgSucesso" => "Registro inserido com sucesso."]);
            } else {
                return Redirect::page($this->controller . "/form/insert/0");
            }

            //

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

        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page($this->controller . "/form/update/" . $post['id']);    // error
        } else {

            if (!empty($_FILES['imgautomacao']['name'])) {

                // Faz uploado da imagem
                $nomeRetornado = $this->files->upload($_FILES, 'automacao');

                // se for boolean, significa que o upload falhou
                if (is_bool($nomeRetornado)) {
                    Session::set( 'inputs', $post);
                    return Redirect::page($this->controller . "/form/update/" . $post['id']);
                } else {
                    $post['imgautomacao'] = $nomeRetornado[0];
                }
                
                if (isset($post['nomeImagem'])) {
                    $this->files->delete($post['nomeImagem'], 'automacao');
                }
                
            } else {
                $post['imgautomacao'] = $post['nomeImagem'];
            }

            //
            unset($post['nomeImagem']);


            if ($this->model->update($post)) {
                return Redirect::page($this->controller, ["msgSucesso" => "Registro alterado com sucesso."]);
            } else {
                return Redirect::page($this->controller . "/form/update/" . $post['id']);
            }
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

        if ($this->model->delete($post)) {
                        $this->files->delete($post['nomeImagem'], "automacao");

            return Redirect::page($this->controller, ["msgSucesso" => "Registro Excluído com sucesso."]);
        } else {
            return Redirect::page($this->controller);
        }
    }
        public function detalhe($action = null)
{
    $id = $action;

    $automacao = $this->model->getById($id);

    if (!$automacao) {
        die("Sistema não encontrado.");
    }

    // 🔥 AQUI você busca comentários
    $comentarioModel = new \App\Model\ComentarioModel();
    $comentarios = $comentarioModel->listarPorAutomacao($id);    

    return $this->loadView("automacao_detalhe", [
        'automacao' => $automacao,
        'comentarios' => $comentarios
    ]);
}


       public function comentar()
{
    // pega dados do form
    $post = $_POST;

    // 🔒 segurança: garante que existe
    if (!isset($post['automacao_id']) || !isset($post['comentario'])) {
        die("POST NÃO VEIO CERTO");
    }

    // verifica login
    if (!\Core\Library\Session::get('userId')) {
        return Redirect::page('login');
    }

    // valida comentário vazio
    if (empty($post['comentario'])) {
        return Redirect::page("automacao/detalhe/" . $post['automacao_id']);
    }

    $dados = [
        'automacao_id' => $post['automacao_id'],
        'usuario_id'   => \Core\Library\Session::get('userId'),
        'comentario'   => $post['comentario']
    ];

    $model = new \App\Model\ComentarioModel();

    if ($model->insert($dados)) {
        \Core\Library\Session::set('msgSucesso', 'Comentário enviado!');
    } else {
        \Core\Library\Session::set('msgErro', 'Erro ao comentar!');
    }

    return Redirect::page("automacao/detalhe/" . $post['automacao_id']);
}
public function excluirComentario()
{
    $post = $this->request->getPost();

    $comentarioModel = new \App\Model\ComentarioModel();
    $comentario = $comentarioModel->getById($post['id']);

    $usuarioId = \Core\Library\Session::get("userId");
    $nivel = \Core\Library\Session::get("userNivel");

    // 🔒 SEGURANÇA
    if ($comentario['usuario_id'] != $usuarioId && $nivel != 1) {
        \Core\Library\Session::set('msgErro', 'Você não tem permissão.');
        return \Core\Library\Redirect::page("automacao/detalhe/" . $comentario['automacao_id']);
    }

    $comentarioModel->delete(['id' => $post['id']]);

    \Core\Library\Session::set('msgSucesso', 'Comentário excluído!');
    return \Core\Library\Redirect::page("automacao/detalhe/" . $comentario['automacao_id']);
}
        
}