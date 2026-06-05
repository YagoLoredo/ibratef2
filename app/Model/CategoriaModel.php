<?php

namespace App\Model;

use Core\Library\ModelMain;

class CategoriaModel extends ModelMain
{
    protected $table = "categoria";
    
    public $validationRules = [
        "nome"  => [
            "label" => 'Nome',
            "rules" => 'required|min:2|max:30'
        ],
        'statusRegistro' => [
            'label' => 'Status',
            'rules' => 'required|int'
        ]
    ];
}