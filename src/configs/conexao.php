<?php

//Inicio da conexão com o banco de dados utilizando PDO
$host = "138.186.93.168";
$user = "pedro";
$pass = "Pedroh255";
$dbname = "ponto";
$port = 3306;

try {
    //Conexao com a portaF
    //$conn = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

    //Conexao sem a porta
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    //echo "Conexão com banco de dados realizado com sucesso.";
} catch (PDOException $err) {
    echo "Erro: Conexão com banco de dados não realizado com sucesso. Erro gerado " . $err->getMessage();
}
    //Fim da conexao com o banco de dados utilizando PDO
