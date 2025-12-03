<?php

require_once 'conexao.php'; 
header('Content-Type: application/json');
session_start();

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        "status" => "ok",
        "mensagem" => "Usuário já está logado",
        "usuario" => [
            "id" => $_SESSION['usuario_id'],
            "nome" => $_SESSION['usuario_nome'],
            "tipo" => $_SESSION['tipo_acesso']
        ]
    ]);
        exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input", true));

$login_digitado = trim ($data["login"] ?? "");
$senha_digitada = $data["senha"] ?? "";

try {
    $sql = "SELECT codigo, nome, senha, tipo_usuario FROM funcionarios WHERE login = :login";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login_digitado);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica o usuário e a senha usando o hash
    if ($usuario && password_verify($senha_digitada, $usuario['senha'])) {

            $_SESSION['usuario_id'] = $usuario['codigo'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_acesso'] = $usuario['tipo_usuario'];
            
        echo json_encode([
            "status" => "ok",
            "mensagem" => "Login realizado com sucesso",
            "usuario" => [
                "id" => $usuario['codigo'],
                "nome" => $usuario['nome'],
                "tipo" => $usuario['tipo_usuario']
            ]
            ]);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(["erro" => "Login ou senha incorreta"]);
        exit;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro no servidor: " . $e->getMessage()]);
    exit;
}
?>