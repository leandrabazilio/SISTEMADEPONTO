<?php

require_once 'conexao.php'; 
$mensagem_cadastro = "";

// CADASTRO DE NOVO COLABORADOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastrar') {
    $nome = trim($_POST['nome']);
    $login = trim($_POST['login']);
    $senha_pura = $_POST['senha'];
    $tipo_usuario = 'Colaborador'; 

    if (!empty($nome) && !empty($login) && !empty($senha_pura)) {
        // GERAÇÃO DO HASH SEGURO
        $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT); 

        $sql = "INSERT INTO Funcionarios (nome, login, senha, tipo_usuario) VALUES (:nome, :login, :senha_hash, :tipo)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha_hash', $senha_hash);
            $stmt->bindParam(':tipo', $tipo_usuario);
            $stmt->execute();
            
            $mensagem_cadastro = "<p style='color:green;'>Cadastro realizado com sucesso! Use seu login e senha para entrar.</p>";
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensagem_cadastro = "<p style='color:red;'>Erro M3: O login '$login' já existe.</p>";
            } else {
                $mensagem_cadastro = "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
            }
        }
    } else {
         $mensagem_cadastro = "<p style='color:red;'>Erro M1: Preencha todos os campos.</p>";
    }
}
?>