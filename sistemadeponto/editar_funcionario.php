<?php

session_start();

require_once 'conexao.php';
header('Content-Type: application/json');

// Segurança - VERIFICAR SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['usuario_id'])) {
   http_response_code(403);
   echo json_encode(["erro" => "Acesso negado: Usuário não logado"]);
   exit();
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

if ($method === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $codigo = $data["codigo"] ?? null;
    $nome = trim($data["nome"] ?? "");
    $tipo_usuario = trim($data["tipo_usuario"] ?? "");

    // 1. Validação de Segurança
    $is_admin = ($_SESSION['tipo_acesso'] === 'Administrador');
    $is_self_edit = ($_SESSION['usuario_id'] == $codigo);

    if (!isset($_SESSION['usuario_id']) || (!$is_admin && !$is_self_edit)) {
        http_response_code(403);
        echo json_encode(["erro" => "Acesso negado. Você não tem permissão para editar este perfil."]);
        exit;
    }

    // 2. Validação dos dados
    if (!$codigo || !$nome) {
        http_response_code(400);
        echo json_encode(["erro" => "Dados incompletos para edição"]);
        exit;
    }

    try {
        // 3. Montagem da Query SQL
        if ($is_admin && $tipo_usuario != null) {
            // Admin pode mudar nome e tipo de usuário
            $sql = "UPDATE Funcionarios SET nome = :nome, tipo_usuario = :tipo WHERE codigo = :codigo";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':tipo', $tipo_usuario);
        } else {
            // Colaborador só pode mudar o próprio nome
            $sql = "UPDATE Funcionarios SET nome = :nome WHERE codigo = :codigo";
            $stmt = $pdo->prepare($sql);
        }

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();

        // Atualiza o nome na sessão se o usuário editou a si mesmo
        if ($is_self_edit) {
            $_SESSION['usuario_nome'] = $nome;
        }

        echo json_encode(["status" => "ok", "mensagem" => "Perfil atualizado com sucesso!"]);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro de banco de dados: " . $e->getMessage()]);
        exit;
    }
}

// Se chegar aqui → método inválido
http_response_code(405);
echo json_encode(["erro" => "Método não permitido"]);
exit;
?>