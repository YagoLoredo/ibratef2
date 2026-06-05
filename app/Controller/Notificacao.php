<?php
namespace App\Controller;

use Core\Library\ControllerMain;
use App\Model\NotificacaoModel;
use Core\Library\Session; // Importamos a classe correta de sessão do seu framework

class Notificacao extends ControllerMain
{
    // Sua listagem geral (caso o cliente clique em "Ver todas")
    public function index()
    {
        $model = new NotificacaoModel();
        
        // CORRIGIDO: Usando o padrão do seu sistema (userId)
        $usuarioId = Session::get("userId"); 

        $notificacoes = $model
            ->db
            ->where('usuario_id', $usuarioId)
            ->orderBy('data', 'DESC')
            ->findAll();

        return $this->loadView('notificacoes', [
            'notificacoes' => $notificacoes
        ]);
    }

    // Retorna JSON para o menu do topo do site
    public function buscarNaoLidas()
    {
        header('Content-Type: application/json');
        
        $model = new NotificacaoModel();
        
        // CORRIGIDO: Mudado de $_SESSION['usuario_id'] para Session::get("userId")
        $usuarioId = Session::get("userId"); 

        if (!$usuarioId) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Não autenticado']);
            exit;
        }

        // Busca apenas as que não foram visualizadas pelo usuário atual
        $naoLidas = $model
            ->db
            ->where('usuario_id', $usuarioId)
            ->where('visualizada', 0)
            ->orderBy('data', 'DESC')
            ->findAll();

        // Garante que se vier falso ou nulo do banco, vire um array limpo
        $naoLidas = $naoLidas ? $naoLidas : [];

        echo json_encode([
            'status' => 'sucesso',
            'total' => count($naoLidas),
            'dados' => $naoLidas
        ]);
        exit;
    }

    public function marcarComoLida()
    {
        header('Content-Type: application/json');
        $post = $this->request->getPost();

        if (isset($post['id'])) {
            $model = new NotificacaoModel();
            
            // Força a atualização do campo visualizada para 1
            $model->update([
                'id' => (int)$post['id'],
                'visualizada' => 1
            ]);
            
            echo json_encode(['status' => 'sucesso']);
            exit;
        }

        echo json_encode(['status' => 'erro', 'mensagem' => 'ID inválido']);
        exit;
    }
    public function MarcarTodasComoLida()
{
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    try {
        // 1. Recupera o ID do usuário logado na sessão
        $userId = \Core\Library\Session::get("userId");

        if (!$userId) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
            exit;
        }

        $notificacaoModel = new \App\Model\NotificacaoModel();
        
        // 2. Chame o método correto do seu Model passando o ID do usuário
        // OBS: Certifique-se de que o seu Model tem uma query parecida com:
        // "UPDATE notificacoes SET lida = 1 WHERE usuario_id = ?"
        $sucesso = $notificacaoModel->marcarTodasComoLidasDoUsuario($userId); 

        // Se o seu framework retorna o número de linhas afetadas ou true:
        if ($sucesso) {
            echo json_encode(['status' => 'sucesso']);
            exit;
        } else {
            // Caso nenhuma linha tenha sido alterada (ou já estavam lidas)
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Nenhuma modificação necessária.']);
            exit;
        }

    } catch (\Exception $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro no servidor: ' . $e->getMessage()]);
        exit;
    }
}
}