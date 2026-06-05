<?php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Files;
use Core\Library\Redirect;
use Core\Library\Session;
use Core\Library\Validator;

class TipoServico extends ControllerMain
{   
        protected $files;


    public function __construct()
    {
        $this->auxiliarconstruct();
        $this->loadHelper('formHelper');
        $this->files = new Files();

    }

    public function index()
    {
        return $this->loadView("sistema/listaTipoServico", $this->model->lista());
    }

    public function form($action, $id)
    {
        $dados = [
            'data' => $this->model->getById($id)
        ];

        return $this->loadView("sistema/formTipoServico", $dados);
    }

    public function insert()
    {
        $post = $this->request->getPost();

        
        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page($this->controller . "/form/insert/0");
        } else {

            // faz upload da imagem

            if (!empty($_FILES['imgservico']['name'])) {
                
                // Faz upload da imagem
                $nomeRetornado = $this->files->upload($_FILES, 'tipo_servico');

                // se for boolean, significa que o upload falhou
                if (is_bool($nomeRetornado)) {
                    Session::set('inputs', $post);
                    return Redirect::page($this->controller . "/form/insert/" . $post['id']);
                } else {
                    $post['imgservico'] = $nomeRetornado[0];
                }
            } else {
                $post['imgservico'] = $post['nomeImagem'];
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

    public function update()
    {
        $post = $this->request->getPost();

        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page($this->controller . "/form/update/" . $post['id']);    // error
        } else {

            if (!empty($_FILES['imgservico']['name'])) {

                // Faz uploado da imagem
                $nomeRetornado = $this->files->upload($_FILES, 'tipo_servico');

                // se for boolean, significa que o upload falhou
                if (is_bool($nomeRetornado)) {
                    Session::set( 'inputs', $post);
                    return Redirect::page($this->controller . "/form/update/" . $post['id']);
                } else {
                    $post['imgservico'] = $nomeRetornado[0];
                }
                
                if (isset($post['nomeImagem'])) {
                    $this->files->delete($post['nomeImagem'], 'tipo_servico');
                }
                
            } else {
                $post['imgservico'] = $post['nomeImagem'];
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

    public function delete()
    {
        $post = $this->request->getPost();

        if ($this->model->delete($post)) {
        $this->files->delete($post['nomeImagem'], "tipo_servico");

            return Redirect::page($this->controller, ["msgSucesso" => "Registro Excluído com sucesso."]);
        } else {
            return Redirect::page($this->controller);
        }
    }
}
