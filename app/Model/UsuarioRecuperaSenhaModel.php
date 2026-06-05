<?php

namespace App\Model;

use Core\Library\ModelMain;

class UsuarioRecuperaSenhaModel extends ModelMain
{
    protected $table = "usuariorecuperasenha";

    /**
     * getRecuperaSenhaChave - Recuperar os dados do usuário especificado em $email
     *
     * @param string $chave 
     * @return array
     */
    public function getRecuperaSenhaChave($chave) 
{
    // 1. Verifique se a chave não está vazia antes de ir ao banco
    if (empty($chave)) {
        return null;
    }

    // 2. Garanta que o statusRegistro seja comparado corretamente
    // Use o padrão que seu banco exige (maiúsculas/minúsculas)
    return $this->db->where([
        "statusRegistro" => 1, 
        "chave"          => trim($chave) // Limpa espaços acidentais
    ])->first();
}

    /**
     * desativaChave - Desativa chave de acesso
     *
     * @param mixed $id 
     * @return void
     */
    function desativaChave($id) 
    {
        $rs = $this->db->where(["id" => $id])->update(["statusRegistro" => 2, "updated_at" => date("Y-m-d H:i:s")]);
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }      
    }

    /**
     * desativaChave - Desativa chave de acesso
     *
     * @param mixed $id 
     * @return void
     */
    function desativaChaveAntigas($id) 
    {
        $rs = $this->db->where(["id <>" => $id])->update(["statusRegistro" => 2]);
        
        if ($rs > 0) {
            return true;
        } else {
            return false;
        }      
    }
    
}