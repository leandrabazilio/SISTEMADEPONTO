<?php

// Define o fuso horário oficial de Brasília 
date_default_timezone_set('America/Sao_Paulo');

$host = 'localhost'; 
$dbname = 'db_pontos'; 
$user = 'root';        // Seu usuário do MySQL
$password = '';        // Sua senha do MySQL 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Inicia a sessão para controle de login em todos os arquivos que o incluírem
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
} catch (PDOException $e) {
    // Se a conexão falhar, exibe uma mensagem crítica
    die("<h1>Erro Crítico de Conexão com o Banco de Dados:</h1><p>" . $e->getMessage() . "</p>");
}
?>