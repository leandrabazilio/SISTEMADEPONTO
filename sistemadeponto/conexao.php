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
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao conectar ao banco: " . $e->getMessage()]);
}
?>