<?php

// Define o fuso horário oficial de Brasília 
date_default_timezone_set('America/Sao_Paulo');

$host = 'localhost'; 
$dbname = 'controle_ponto'; 
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
    // Se a conexão falhar, interrompe a execução e exibe uma mensagem de erro clara.
    die("<h1>Erro Crítico de Conexão</h1><p>Não foi possível conectar ao banco de dados. Verifique as credenciais e o status do servidor.</p><p>Detalhe do erro: " . $e->getMessage() . "</p>");
}
?>