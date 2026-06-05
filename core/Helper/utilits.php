<?php

    /**
     * baseUrl
     *
     * @return string
     */
    function baseUrl()
    {
        return $_ENV['BASEURL'];
    }
    
    /**
     * getUsuarioLogado
     * Conecta à Database fornecendo os argumentos esperados pelo construtor do framework
     * @return array|null
     */
    function getUsuarioLogado()
    {
        $userId = \Core\Library\Session::get("userId");
        if (!$userId) {
            return null;
        }

        try {
            // Buscamos as credenciais que o seu sistema usa. 
            // Se o seu banco local tiver senha ou outro nome, altere os valores abaixo:
            $driver   = 'mysql';
            $host     = 'localhost';
            $dbname   = 'ibratef'; 
            $user     = 'root';
            $pass     = ''; // Se seu MySQL local tiver senha, coloque ela aqui dentro (ex: 'root')
            $timezone = defined('DEFAULT_TIME_ZONE') ? DEFAULT_TIME_ZONE : 'America/Sao_Paulo';

            // Passamos os 6 argumentos exatos que o construtor da sua classe Database exige!
            $databaseInstancia = new \Core\Library\Database($driver, $host, $dbname, $user, $pass, $timezone);
            
            // Usamos a reflexão para extrair o PDO puro de dentro dele, ignorando métodos ausentes
            $reflector = new \ReflectionClass($databaseInstancia);
            $propriedadeNome = $reflector->hasProperty('db') ? 'db' : ($reflector->hasProperty('pdo') ? 'pdo' : 'con');
            
            $propriedade = $reflector->getProperty($propriedadeNome);
            $propriedade->setAccessible(true);
            $pdo = $propriedade->getValue($databaseInstancia);

            if ($pdo instanceof \PDO) {
                // Buscamos o nome e a foto reais diretamente de quem está logado
                $stmt = $pdo->prepare("SELECT foto, nome FROM usuarios WHERE id = ?");
                $stmt->execute([$userId]);
                return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
            }
            
            return null;
            
        } catch (\Exception $e) {
            // Evita que o cabeçalho quebre caso a senha do banco mude localmente
            return null;
        }
    }