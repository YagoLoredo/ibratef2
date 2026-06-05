<?php

namespace App\Model;

use Core\Library\ModelMain;

class TipoServicoModel extends ModelMain
{
    protected $table = "tipo_servico";

    public $validationRules = [
        "nome"  => [
            "label" => 'Nome do Serviço',
            "rules" => 'required|min:3|max:100'
        ],
        "preco" => [
            "label" => 'Preço',
            "rules" => 'required|numeric|min:0'
        ]
    ];



}
