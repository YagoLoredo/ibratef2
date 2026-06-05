<?php

namespace App\Model;

use Core\Library\ModelMain;

class ClienteModel extends ModelMain
{
    protected $table = "cliente";
    
    public $validationRules = [
        "nome"  => [
            "label" => 'Nome',
            "rules" => 'required|min:2|max:100'
        ],
        "telefone"  => [
            "label" => 'Telefone',
            "rules" => 'required|max:50|unique:cliente.telefone'
        ],
        "email"  => [
            "label" => 'Email',
            "rules" => 'required|min:2|max:150|email' // 🔥 removido unique
        ],
        "cpf"   => [
            "label" => 'CPF',
            "rules" => 'required|min:11|max:14' // 🔥 não usar INT
        ],
        "endereco"  => [
            "label" => 'Endereco',
            "rules" => 'required|min:2|max:150'
        ]
    ];

    public function listarTodos($filtros = [])
    {
        $this->db->select("
            cliente.*, 
            usuario.nome AS nome_usuario,
            usuario.email AS email_usuario
        ")
        ->join("usuario", "usuario.id = cliente.usuario_id", "left");

        if (!empty($filtros["usuario_id"])) {
            $this->db->where(["cliente.usuario_id" => $filtros["usuario_id"]]);
        }

        return $this->db
                    ->orderBy("cliente.nome")
                    ->findAll();
    }

    public function listaClientesDoUsuario($usuarioId)
    {
        return $this->db
                    ->orderBy("id, nome")
                    ->where("usuario_id", $usuarioId)
                    ->getAll();
    }
}