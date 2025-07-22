<?php

//definição de constantes para a conexão com o Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'crud_usuarios');
define('DB_USER', 'root');
define('DB_PASS', 'logospc1');

try {
    //tenta a conexão com o banco de dados
    $pdo = new PDO("mysql:host=" .DB_HOST. ";dbname=" .DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $msg) {
    //em caso de erro, retornar esta mensagem
    die ("ERRO: Não foi possível conectar. " .$msg->getMessage());
}


