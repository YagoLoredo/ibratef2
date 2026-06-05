<?php
// app\controller\Home.php

namespace App\Controller;

use Core\Library\ControllerMain;
use Core\Library\Email;
use Core\Library\Redirect;



class Home extends ControllerMain
{
    public function index()
{
    $TipoServicoModel = $this->loadModel("TipoServico");
    $AutomacaoModel   = $this->loadModel("Automacao");

    $dados['dados'] = $TipoServicoModel->lista("nome");        // serviços
    $dados['automacoes'] = $AutomacaoModel->lista("descricao"); // sistemas

    return $this->loadView("home", $dados);
}

        public function sobre()
    {
        $this->loadView("sobre");
           
    }
    public function Contato()
    {
        $this->loadView("contato");
           
    }
    public function politica()
{
    $this->loadView("politica");
}

public function termos()
{
    $this->loadView("termos");
}
    public function AutomacaoDetalhes()
    {
        $this->loadView("automacao_detalhe");
           
    }

    
    public function site()
    {
        $this->loadView("site");
    }

    public function Automacao() {
        
        $AutomacaoModel = $this->loadModel("Automacao");  

        return $this->loadView("Automacoes", $AutomacaoModel->lista("descricao"));
    }
    public function Automacao_Detalhes() {
        $AutomacaoModel = $this->loadModel("Automacao_detalhes");
        return $this->loadView("Automacao_detalhe", $AutomacaoModel->lista("descricao"));

    }
   public function Servicos() {
    
        $TipoServicoModel = $this->loadModel("TipoServico");  
    
        return $this->loadView("Servicos", $TipoServicoModel->lista("nome"));
    }


    public function detalhes($action = null, $id = null, ...$params)
    {
        echo "Detalhes: <br />";
        echo "<br />Ação: " . $action;
        echo "<br />ID: " . $id;
        echo "<br />PARÂMETROS: " . implode(", ", $params);
    }

     public function Cadastro()
    {
        $this->loadHelper('Formulario');
        
        return $this->loadView("login/CriarConta", []);
    }
    
    public function contatoEnviaEmail()
{
    $this->loadHelper("emailHelper"); // Carrega o helper

    $post = $this->request->getPost();

    // Monta o corpo e assunto do e-mail com o helper
    $emailTexto = emailContatoRecebido(
        $post['nome'],
        $post['celular'],
        $post['email'],
        $post['assunto'],
        $post['mensagem']
    );

    // Dispara o e-mail com a função global no padrão do seu projeto
    $lRetMail = Email::enviaEmail(
        $_ENV['MAIL.USER'],      // Remetente
        $_ENV['MAIL.NOME'],      // Nome do Remetente
        $emailTexto['assunto'],  // Assunto
        $emailTexto['corpo'],    // Corpo HTML
        "suporte@ibratef.com.br"   // Destinatário fixo
    );

    if ($lRetMail) {
        return Redirect::page("Home/contato", [
            "msgSucesso" => "E-mail enviado com sucesso, em breve entraremos em contato!"
        ]);
    } else {
        return Redirect::page("Home/contato", [
            "msgError" => "Ocorreu um erro ao enviar o e-mail. Tente novamente."
        ]);
    }
}



    
}