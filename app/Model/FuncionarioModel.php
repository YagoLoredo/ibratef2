<?php

namespace App\Model;

use Core\Library\ModelMain;

class FuncionarioModel extends ModelMain
{
    protected $table = "funcionario";
    
    public $validationRules = [
        "nome"  => [
            "label" => 'Nome',
            "rules" => 'required|min:2|max:100'
        ],
        "cpf"  => [
            "label" => 'Cpf',
            "rules" => 'required|min:11|max:14'
        ],
        "email"  => [
            "label" => 'Email',
            "rules" => 'required|min:2|max:150|email'
        ],
    ];

    /**
     * Busca um funcionário pelo ID
     * @param int $id
     * @return array|bool
     */
    public function getById($id)
    {
        return $this->db
                    ->where('id', (int)$id)
                    ->first(); // Busca o primeiro registro encontrado
    }
}