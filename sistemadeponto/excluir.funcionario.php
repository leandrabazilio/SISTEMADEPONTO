<?php

session_start();

require_once 'conexao.php';
header('Content-Type: application/json');

// Verificação de segurança — somente Administrador pode excluir
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_acesso'] !== 'Administrador') {
    http_response_code(403);
    echo json_encode(["erro" => "Acesso negado"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') { // Aceita requisições POST para exclusão
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit;
}

// Obter o corpo da requisição (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// ID do funcionário
$codigo = $data["codigo"] ?? null;

if (!$codigo) {
    http_response_code(400);
    echo json_encode(["erro" => "Código do funcionário não informado"]);
    exit;
}

// Executar
try {
    $sql = "DELETE FROM Funcionarios WHERE codigo = :codigo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();

    // Verifica se realmente excluiu
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "ok",
            "mensagem" => "Funcionário excluído com sucesso"
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["erro" => "Funcionário não encontrado"]);
    }

    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao excluir: " . $e->getMessage()]);
    exit;
}
?>