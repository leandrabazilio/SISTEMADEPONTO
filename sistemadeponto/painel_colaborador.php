<?php

require_once 'conexao.php'; 
header('Content-Type: application/json');

// 1. VERIFICAÇÃO DE SEGURANÇA
// Se o usuário NÃO estiver logado ou não for um colaborador, retorna um erro.
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_acesso'] !== 'Colaborador') {
    http_response_code(403);
    echo json_encode(["erro" => "Acesso negado"]);
    exit();
}

// 2. REGISTRO DE PONTO (Endpoint para POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_registro'])) {
    $tipo = $_POST['tipo_registro'];
    $data_hora_atual = date('Y-m-d H:i:s'); // Captura a hora exata do servidor
    $id_colaborador = $_SESSION['usuario_id'];

    $sql_insert = "INSERT INTO Pontos (funcionario_codigo, data_hora, tipo_registro) VALUES (:codigo, :data_hora, :tipo)";

    try {
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(':codigo', $id_colaborador);
        $stmt_insert->bindParam(':data_hora', $data_hora_atual);
        $stmt_insert->bindParam(':tipo', $tipo);
        $stmt_insert->execute();
        
        // Formata o tipo de registro para uma leitura mais amigável
        $tipo_formatado = ucfirst(str_replace('_', ' ', $tipo));
        $hora_formatada = date('H:i:s', strtotime($data_hora_atual));

        echo json_encode([
            "status" => "ok", 
            "mensagem" => "Ponto de '{$tipo_formatado}' registrado com sucesso às {$hora_formatada}!"
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao registrar ponto: " . $e->getMessage()]);
    }
    exit(); // Encerra o script após tratar o POST
}

// 3. BUSCA DE HISTÓRICO (Endpoint para GET)
// Se a requisição não for POST, assume-se que é para buscar o histórico do dia.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $hoje = date('Y-m-d');
    $id_colaborador = $_SESSION['usuario_id'];
    $sql_read = "SELECT data_hora, tipo_registro FROM Pontos 
                 WHERE funcionario_codigo = :codigo AND DATE(data_hora) = :hoje 
                 ORDER BY data_hora DESC"; 
    
    $stmt_read = $pdo->prepare($sql_read);
    $stmt_read->bindParam(':codigo', $id_colaborador);
    $stmt_read->bindParam(':hoje', $hoje);
    $stmt_read->execute();
    $pontos_do_dia = $stmt_read->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(["status" => "ok", "pontos" => $pontos_do_dia]);
    exit();
}
?>