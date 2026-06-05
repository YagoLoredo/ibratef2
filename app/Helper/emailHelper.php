<?php

/**
 * emailRecuperacaoSenha
 *
 * @param string $cLink 
 * @return array
 */
function emailRecuperacaoSenha($cLink)
{
    return [
        "assunto" => 'IBRATEF - RECUPERAÇÃO DE SENHA',
        "corpo" => '
                Você solicitou a recuperação de sua senha? <br><br>
                Caso tenha solicitado, clique no link a seguir para prosseguir <a href="'. $cLink . '" title="Recuperar a senha"> com a recuperação da sua senha.</a> <br><br>
                Att: <br><br>
                Equipe Ibratef
            '
    ];
}
function emailContatoRecebido($nome, $celular, $email, $assunto, $mensagem)
{
    return [
        "assunto" => "Novo Contato Recebido - $assunto",
        "corpo" => "
            <p><strong>Nome:</strong> $nome</p>
            <p><strong>Celular:</strong> $celular</p>
            <p><strong>E-mail:</strong> $email</p>
            <p><strong>Assunto:</strong> $assunto</p>
            <p><strong>Mensagem:</strong> $mensagem</p>
        "
    ];
}

