<?php

namespace App\Model;

use Core\Library\ModelMain;

class automacaoModel extends ModelMain
{
    protected $table = "automacao";
    
    public $validationRules = [
        "descricao"  => [
            "label" => 'Descrição',
            "rules" => 'required|min:3|max:50'
        ],
        "cate_id"  => [
            "label" => 'Categoria',
            "rules" => 'required|int'
        ],
        "statusRegistro"  => [
            "label" => 'Status',
            "rules" => 'required|int'
        ],
        "detalhes"  => [
        "label" => 'Detalhes',
        "rules" => 'required|min:3|max:255'
        ],
    ];

    /**
     * Lista os automacoes com suas categorias.
     *
     * @return array
     */
    public function listaautomacao()
    {
        return $this->db->select("automacao.*, categoria.nome ")
                        ->join("categoria", "categoria.id = automacao.cate_id")
                        ->orderBy("categoria.nome, automacao.descricao, automacao.detalhes")
                        ->findAll();
    }
    
    
}
   
