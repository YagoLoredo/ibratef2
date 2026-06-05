<?php

namespace App\Model;

use Core\Library\ModelMain;

class contrachequeModel extends ModelMain
{
    protected $table = "contracheque";
    
    public $validationRules = [

        "funcionario_id"  => [
            "label" => 'funcionario',
            "rules" => 'required|int'
        ],
        "descricao" => [
            "label" => 'Descrição',
            "rules" => 'required|min:3|max:150'
        ],
        "arquivo" => [
            "label" => 'PDF',
            "rules" => 'max:255'
        ],
        "data"  => [
            "label" => 'data',
            "rules" => 'required|Date'
        ],
    ];

    /**
     * Lista contracheques (com ou sem filtro)
     */
public function listaContracheque($filtros = [])
{
    $this->db->select("
        contracheque.*, 
        funcionario.nome AS nome_funcionario
    ")
    ->join("funcionario", "funcionario.id = contracheque.funcionario_id", "left");

    // 🔐 filtro por usuário logado
    if (!empty($filtros['funcionario_id'])) {
        $this->db->where("contracheque.funcionario_id", (int)$filtros["funcionario_id"]);
    }

    // 🔍 filtro por ID
    if (!empty($filtros['id'])) {
        $this->db->where("contracheque.id", (int)$filtros['id']);
    }

    // 🔍 filtro por descrição
    if (!empty($filtros['descricao'])) {
        $this->db->whereLike("contracheque.descricao", trim($filtros['descricao']));
    }

    // 🔍 busca pelo nome do cliente (usuário)
    if (!empty($filtros['busca'])) {
        $this->db->whereLike("funcionario.nome", trim($filtros['busca']));
    }

    return $this->db
                ->orderBy("contracheque.data", "DESC")
                ->findAll();
}

}