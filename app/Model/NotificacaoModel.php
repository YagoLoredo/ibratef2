<?php

namespace App\Model;

use Core\Library\ModelMain;

class NotificacaoModel extends ModelMain
{
    protected $table = "notificacao";
    
    /**
     * Cria uma nova notificação para um usuário específico.
     * * @param int $usuarioId ID do cliente/usuário que receberá o alerta
     * @param string $mensagem O texto que aparecerá no sininho
     * @param string $tipo O tipo da notificação (ex: 'boleto', 'agendamento')
     * @return bool Retorna true se inserido com sucesso
     */
    public function criarAlerta(int $usuarioId, string $mensagem, string $tipo = 'geral')
    {
        // Certifique-se de que os nomes dos índices batem com as colunas da sua tabela no banco
        return $this->db->insert([
            'usuario_id'  => $usuarioId,
            'mensagem'    => $mensagem,
            'tipo'        => $tipo,
            'visualizada' => 0,
            'data'        => date('Y-m-d H:i:s')
        ]);
    }
    /**
     * Marca todas as notificações de um usuário como lidas
     * @param int $usuarioId
     * @return bool
     */
    public function marcarTodasComoLidasDoUsuario(int $usuarioId)
    {
        // Se o seu framework atualiza enviando os campos em um único array (onde o ID mapeia a busca):
        return $this->db->update([
            'usuario_id'  => $usuarioId,
            'visualizada' => 1
        ]);
    }
}