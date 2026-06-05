<?php

namespace App\Model;

use Core\Library\ModelMain;

class AgendamentoModel extends ModelMain
{
    protected $table = "agendamento";

    public $validationRules = [
        "data"  => [
            "label" => 'Data',
            "rules" => 'required|date'
        ],
        "horario"  => [
            "label" => 'Horário',
            "rules" => 'required'
        ],
        "tipo_servico_id"  => [
            "label" => 'Tipo de Serviço',
            "rules" => 'required|int'
        ],

        "usuario_id"  => [
            "label" => 'Usuario',
            "rules" => 'required|int'
        ],
        "observacoes" => [
            "label" => 'Observações',
            "rules" => 'max:255'
        ]
    ];
    /**
     * Busca um agendamento específico pelo ID
     * @param int $id
     * @return array|bool
     */
    public function getById($id)
    {
        return $this->db
                    ->where('id', (int)$id)
                    ->first();
    }

    /**
     * lista com JOIN para exibir dados do serviço, animal e funcionário
     *
     * @return array
     */
    public function listarTodos($filtros = [])
{
    $this->db->select("
            agendamento.*, 
            tipo_servico.nome AS nome_servico, 
            usuario.nome AS nome_usuario
        ")
        ->join("tipo_servico", "tipo_servico.id = agendamento.tipo_servico_id")
        ->join("usuario", "usuario.id = agendamento.usuario_id", "left");

    if (!empty($filtros['usuario_id'])) {
        $this->db->where(["agendamento.usuario_id" => $filtros["usuario_id"]]);
    }

    return $this->db
                ->orderBy("agendamento.data, agendamento.horario")
                ->findAll();
}



}
