<?php

namespace App\Model;

use Core\Library\ModelMain;

class UsuarioModel extends ModelMain
{
    protected $table = "usuario";
    protected $primaryKey = "id";

    public $validationRules = [
    "nome"  => [
        "label" => 'Nome',
        "rules" => 'required|min:3|max:60'
    ],
    "email"  => [
        "label" => 'Email',
        "rules" => 'required|email|min:5|max:150' // Adicionei a regra 'email' aqui!
    ],
    "nivel"  => [
        "label" => 'Nível',
        "rules" => 'required|int' // Adicionei 'required' para garantir que nunca venha vazio
    ],
    "statusRegistro"  => [
        "label" => 'Status',
        "rules" => 'required|int' // Adicionei 'required'
    ]
];

    /**
     * getUserEmail
     *
     * @param string $email 
     * @return array
     */
    public function getUserEmail($email)
    {
        return $this->db->where("email", $email)->first();
    }
    public function listaUsuario()
{
    return $this->db
        ->select("*")
        ->from("usuario")
        ->orderBy("nivel")
        ->orderBy("nome")
        ->findAll();
}
/**
     * Busca usuários que tenham os níveis informados e estejam ativos
     *
     * @param array $niveis Ex: [11, 21]
     * @return array
     */
    public function buscarPorNiveis(array $niveis)
    {
        // Usando o whereIn para buscar todos os níveis passados no array de uma vez
        return $this->db
            ->whereIn("nivel", $niveis)
            ->where("statusRegistro", 1) // Garante que só vai notificar admins ativos
            ->findAll();
    }
    /**
     * Remove a obrigatoriedade de campos administrativos para permitir
     * a validação correta na tela de alteração de perfil do próprio usuário.
     *
     * @return array
     */
    public function getRulesPerfil()
    {
        $rules = $this->validationRules;
        
        // Remove completamente as regras de Nível e Status
        unset($rules['nivel']);
        unset($rules['statusRegistro']);
        
        return $rules;
    }
    
}