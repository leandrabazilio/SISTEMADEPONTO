<?php

require_once 'conexao.php'; // Inclui a conexão e inicia a sessão

header('Content-Type: application/json');

// 1. Obter os dados da requisição
$data = json_decode(file_get_contents("php://input"), true);

$login = $data['login'] ?? null;
$senha = $data['senha'] ?? null;

if (!$login || !$senha) {
    http_response_code(400); // Bad Request
    echo json_encode(['erro' => 'Usuário e senha são obrigatórios.']);
    exit;
}

// 2. Buscar o usuário no banco de dados
try {
    $sql = "SELECT codigo, nome, senha, tipo_usuario FROM Funcionarios WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Verificar a senha
    // Se o usuário foi encontrado E a senha digitada corresponde à senha hash no banco
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        
        // 4. Autenticação bem-sucedida: Salvar dados na sessão
        $_SESSION['usuario_id'] = $usuario['codigo'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['tipo_acesso'] = $usuario['tipo_usuario'];

        // Envia resposta de sucesso para o frontend
        echo json_encode(['status' => 'ok']);
        exit;

    } else {
        // Usuário não encontrado ou senha incorreta
        http_response_code(401); // Unauthorized
        echo json_encode(['erro' => 'Usuário ou senha inválidos.']);
        exit;
    }

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
    exit;
}