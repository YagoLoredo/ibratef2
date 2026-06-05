<?php

namespace App\Model;

use Core\Library\ModelMain;

class boletoModel extends ModelMain
{
    protected $table = "boleto";
    
    public $validationRules = [

        "cliente_id"  => [
            "label" => 'cliente',
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
        "vencimento"  => [
            "label" => 'Vencimento',
            "rules" => 'required|Date'
        ],
    ];

    /**
     * Lista boletos (com ou sem filtro)
     */
public function listaBoleto($filtros = [])
{
    $this->db->select("
        boleto.*, 
        cliente.nome AS nome_cliente
    ")
    ->join("cliente", "cliente.id = boleto.cliente_id", "left");

    // 🔐 filtro por usuário logado
    if (!empty($filtros['cliente_id'])) {
        $this->db->where("boleto.cliente_id", (int)$filtros["cliente_id"]);
    }

    // 🔍 filtro por ID
    if (!empty($filtros['id'])) {
        $this->db->where("boleto.id", (int)$filtros['id']);
    }

    // 🔍 filtro por descrição
    if (!empty($filtros['descricao'])) {
        $this->db->whereLike("boleto.descricao", trim($filtros['descricao']));
    }

    // 🔍 busca pelo nome do cliente (usuário)
    if (!empty($filtros['busca'])) {
        $this->db->whereLike("cliente.nome", trim($filtros['busca']));
    }

    return $this->db
                ->orderBy("boleto.vencimento", "ASC")
                ->findAll();
}

}