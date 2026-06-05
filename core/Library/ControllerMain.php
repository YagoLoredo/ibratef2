<?php

namespace Core\Library;

use Core\Library\Request;

class ControllerMain
{
    protected $controller;
    protected $method;
    protected $action;
    protected $request;
    public $model;

    use RequestTrait;

    /**
     * construct
     */
    public function __construct()
    {
        $this->auxiliarConstruct();
    }

    /**
     * auxiliarconstruct
     *
     * @return void
     */
    public function auxiliarconstruct()
    {
        $aParametros        = Self::getRotaParametros();
        $this->controller   = $aParametros['controller'];
        $this->method       = $aParametros['method'];
        $this->action       = $aParametros['action'];
        $this->model        = $this->loadModel($this->controller);
        $this->request      = new Request();

        // carregar helper padrão
        $this->loadHelper(["formulario", "utilits"]);
    }

    /**
     * loadModel
     *
     * @param string $nomeModel 
     * @return void|object
     */
    public function loadModel($nomeModel)
    {
        $pathModel = "App\Model\\" . $nomeModel . "Model";
        
        if (class_exists($pathModel)) {
            return new $pathModel();
        }
    }

    /**
     * loadHelper
     *
     * @param string|array $nomeHelper 
     * @return void
     */
    public function loadHelper($nomeHelper)
{
    if (gettype($nomeHelper) == "string") {
        $nomeHelper = [$nomeHelper];
    }

    foreach ($nomeHelper as $value) {

        $baseDir = realpath(__DIR__ . "/../../"); // sobe até a raiz do projeto

        $pathHelperAtom = $baseDir . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "Helper" . DIRECTORY_SEPARATOR . "{$value}.php";

        if (file_exists($pathHelperAtom)) {
            require_once $pathHelperAtom;
        } else {
            $pathHelperUser = $baseDir . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "Helper" . DIRECTORY_SEPARATOR . "{$value}.php";

            if (file_exists($pathHelperUser)) {
                require_once $pathHelperUser;
            }
        }
    }
}
public function acaoEmMassa()
{
    $post = $this->request->getPost();

    // 1. Valida se há registros marcados
    if (empty($post['ids'])) {
        return Redirect::page($this->controller, [
            "msgError" => "Nenhum registro selecionado."
        ]);
    }

    // 2. Instancia dinamicamente o modelo de usuários apenas se a classe existir
    $usuarioModel = null;
    if (class_exists('\App\Model\UsuarioModel')) {
        $usuarioModel = new \App\Model\UsuarioModel();
    }

    foreach ($post['ids'] as $id) {

        // 3. Busca o registro atual pelo ID (funciona para qualquer model principal)
        $registro = $this->model->getById($id);
        if (!$registro) {
            continue; 
        }

        // Captura o usuario_id se ele existir no registro atual (ex: Clientes e Funcionários)
        $usuarioIdVinculado = $registro['usuario_id'] ?? null;

        // ==========================================
        // AÇÃO 1: INATIVAR
        // ==========================================
        if ($post['acao'] == 'inativar') {
            // Atualiza status do registro principal (Cliente, Funcionário, etc.)
            $this->model->update([
                "id" => $id,
                "statusRegistro" => 2
            ]);

            // Se houver usuário vinculado e o modelo de usuários existir, inativa o login também
            if ($usuarioIdVinculado && $usuarioModel) {
                $usuarioModel->update([
                    "id" => $usuarioIdVinculado,
                    "statusRegistro" => 2
                ]);
            }
        }

        // ==========================================
        // AÇÃO 2: EXCLUIR
        // ==========================================
        if ($post['acao'] == 'excluir') {
            // Caso seja o controller de Boletos e você queira apagar arquivos físicos
            if ($this->controller === 'Boleto' && !empty($registro['arquivo'])) {
                $caminhoArquivo = "assets/boletos/" . $registro['arquivo'];
                if (file_exists($caminhoArquivo)) {
                    @unlink($caminhoArquivo);
                }
            }

            // Apaga o registro da tabela principal
            $this->model->delete(["id" => $id]);

            // Se houver usuário de login vinculado, apaga para não deixar lixo no banco
            if ($usuarioIdVinculado && $usuarioModel) {
                $usuarioModel->delete(["id" => $usuarioIdVinculado]);
            }
        }
    }

    return Redirect::page($this->controller, [
        "msgSucesso" => "Ação em massa realizada com sucesso."
    ]);
}

    /**
     * loadView
     *
     * @param string $nome 
     * @param array $dados 
     * @param bool $exibeCabRodape 
     * @return void
     */
    public function loadView($nome, $dados = [], $exibeCabRodape = true)
{
    $pathView = ".." . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "View" . DIRECTORY_SEPARATOR;

    // Cabeçalho (opcional)
    if ($exibeCabRodape) {
        require_once $pathView . "Comuns" . DIRECTORY_SEPARATOR . "cabecalho.php";
    }

    // Se houver dados antigos de formulário com erro, eles substituem o que veio
    if (Session::get("inputs") !== false) {
        $dados = Session::getDestroy("inputs");
    }

    // Preenche $_POST com os dados (para setValor funcionar na view)
    if (isset($dados['data']) && is_array($dados['data'])) {
        $_POST = $dados['data'];
    } elseif (!empty($dados) && is_array($dados)) {
        $_POST = $dados;
    }

    // Adiciona mensagens de erro ao $_POST
    if (Session::get("errors") !== false) {
        $_POST['formErrors'] = Session::getDestroy('errors');
    }

    // Extrai variáveis para serem usadas diretamente na view (ex: $form_action)
    extract($dados);

    // Carrega a view
    $caminhoArquivo = $pathView . $nome . ".php";
    if (file_exists($caminhoArquivo)) {
        require_once $caminhoArquivo;
    } else {
        require_once $pathView . "Comuns" . DIRECTORY_SEPARATOR . "erros.php";
    }

    // Rodapé (opcional)
    if ($exibeCabRodape) {
        require_once $pathView . "Comuns" . DIRECTORY_SEPARATOR . "rodape.php";
    }
}
/**
     * listaAtivos
     *
     * Retorna a lista de registros ativos (statusRegistro = 1) usando o model atual.
     * Requer que o model tenha o método 'listaPorStatus' ou usa filtro direto se disponível.
     *
     * @param string $orderby
     * @param string $direction
     * @return array
     */
    public function listaAtivos($orderby = 'nome', $direction = 'ASC')
    {
        if (method_exists($this->model, 'listaPorStatus')) {
            return $this->model->listaPorStatus(1, $orderby, $direction);
        } else {
            // Caso seu model não tenha listaPorStatus, tenta filtro direto no db:
            if (property_exists($this->model, 'db')) {
                return $this->model->db->where(['statusRegistro' => 1])->orderBy($orderby, $direction)->findAll();
            }
        }

        // fallback vazio
        return [];
    }
    public function excluirDinamico($id)
{
    // Garante o cabeçalho JSON
    header('Content-Type: application/json');

    try {
        $agendamentoModel = new \App\Model\AgendamentoModel();

        // 🔍 CAPTURA DINÂMICA:
        // Usamos Reflexão do PHP para ler a propriedade protegida/privada 'primaryKey' do seu ModelMain
        $reflector = new \ReflectionClass($agendamentoModel);
        $propriedadeChave = $reflector->getProperty('primaryKey');
        $propriedadeChave->setAccessible(true);
        $nomeDaChavePrimaria = $propriedadeChave->getValue($agendamentoModel);

        // Se por acaso a propriedade estiver vazia no model, usamos 'id' como plano de fuga
        if (empty($nomeDaChavePrimaria)) {
            $nomeDaChavePrimaria = 'id';
        }

        /* * 💡 Agora o array vai exatamente com o nome que o ModelMain espera no método delete.
         * Se a chave for 'id', o array vira: ['id' => X]
         */
        $dadosParaDeletar = [
            $nomeDaChavePrimaria => (int)$id
        ];
        
        // Executa a deleção
        if ($agendamentoModel->delete($dadosParaDeletar)) {
            echo json_encode(['status' => 'sucesso']);
            exit;
        }

        echo json_encode([
            'status' => 'erro', 
            'mensagem' => 'O registro não foi localizado no banco de dados ou já foi removido.'
        ]);
        exit;

    } catch (\Exception $e) {
        echo json_encode([
            'status' => 'erro', 
            'mensagem' => 'Erro interno no servidor: ' . $e->getMessage()
        ]);
        exit;
    }
}
}


