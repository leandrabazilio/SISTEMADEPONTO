<?php

session_start();

require_once 'conexao.php';
header('Content-Type: application/json');

// Segurança
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_acesso'] !== 'Administrador') {
    http_response_code(403);
    echo json_encode(["erro" => "Acesso negado"]);
    exit;
}

// Buscar informações do funcionário
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['codigo'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Código não informado"]);
        exit;
    }

    $codigo = intval($_GET['codigo']);

    try {
        $sql = "SELECT codigo, nome, cargo, login, tipo_usuario FROM Funcionarios WHERE codigo = :c";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':c', $codigo);
        $stmt->execute();
        $func = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$func) {
            http_response_code(404);
            echo json_encode(["erro" => "Funcionário não encontrado"]);
            exit;
        }

        echo json_encode(["status" => "ok", "funcionario" => $func]);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => $e->getMessage()]);
        exit;
    }
}

// Editar funcionário
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'PUT' || ($method === 'POST' && ($_POST['_method'] ?? '') === 'PUT')) {

    $data = json_decode(file_get_contents("php://input"), true);

    $codigo = $data["codigo"] ?? null;
    $nome = trim($data["nome"] ?? "");
    $cargo = trim($data["cargo"] ?? "");
    $tipo_usuario = trim($data["tipo_usuario"] ?? "");

    if (!$codigo || !$nome || !$cargo || !$tipo_usuario) {
        http_response_code(400);
        echo json_encode(["erro" => "Dados incompletos para edição"]);
        exit;
    }

    try {
        $sql = "UPDATE Funcionarios 
                SET nome = :nome, cargo = :cargo, tipo_usuario = :tipo 
                WHERE codigo = :codigo";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':tipo', $tipo_usuario);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();

        echo json_encode(["status" => "ok", "mensagem" => "Funcionário atualizado"]);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => $e->getMessage()]);
        exit;
    }
}

// Se chegar aqui → método inválido
http_response_code(405);
echo json_encode(["erro" => "Método não permitido"]);
exit;
?>