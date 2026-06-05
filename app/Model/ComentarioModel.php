<?php
namespace App\Model;
use Core\Library\ModelMain;
use Core\Library\Session; 

class ComentarioModel extends ModelMain
{
    protected $table = 'comentario';
    public $validationRules = [
        "automacao_id"  => [
            "label" => 'Automaçao',
            "rules" => 'required|int'
        ],
        "usuario_id"  => [
            "label" => 'Usuario',
            "rules" => 'required|int'
        ],
        "comentario"  => [
            "label" => 'Comentario',
            "rules" => 'required|text'
        ]
    ];
    public function listarPorAutomacao($automacao_id)
{
    return $this->db
        ->select("comentario.*, usuario.nome as usuario_nome")
        ->join("usuario", "usuario.id = comentario.usuario_id")
        ->where("comentario.automacao_id", $automacao_id)
        ->orderBy("comentario.data", "DESC")
        ->findAll();
}
}