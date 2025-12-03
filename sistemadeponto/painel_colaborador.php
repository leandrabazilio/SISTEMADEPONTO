<?php

session_start();

require_once 'conexao.php'; 
header('Content-Type: application/json');

//  VERIFICAÇÃO DE SEGURANÇA
// Se o usuário NÃO estiver logado ou não for um colaborador, redireciona para o login.

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_acesso'] !== 'Colaborador') {
    http_response_code(403);
    echo json_encode(["erro" => "Acesso negado"]);
    exit();
}

// Variáveis da sessão
$id_colaborador = $_SESSION['usuario_id'];
$nome_colaborador = $_SESSION['usuario_nome'];

$mensagem = "";
$pontos_do_dia = [];

//  REGISTRO DE PONTO

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_registro'])) {
    $tipo = $_POST['tipo_registro'];
    $data_hora_atual = date('Y-m-d H:i:s'); // Captura a hora exata do servidor

    $sql_insert = "INSERT INTO Pontos (funcionario_codigo, data_hora, tipo_registro) VALUES (:codigo, :data_hora, :tipo)";

    try {
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(':codigo', $id_colaborador);
        $stmt_insert->bindParam(':data_hora', $data_hora_atual);
        $stmt_insert->bindParam(':tipo', $tipo);
        $stmt_insert->execute();
        
        $mensagem = "<p style='color:green;'>Ponto de *" . ucfirst($tipo) . "* registrado com sucesso às $data_hora_atual!</p>";
        
    } catch (PDOException $e) {
        $mensagem = "<p style='color:red;'>Erro ao registrar ponto: " . $e->getMessage() . "</p>";
    }
}

// EXIBIÇÃO DO HISTÓRICO DIÁRIO 
// Mostra os pontos que o colaborador já bateu HOJE.

// Formata a data de hoje para ser usada na consulta SQL
$hoje = date('Y-m-d');
$sql_read = "SELECT data_hora, tipo_registro FROM Pontos 
             WHERE funcionario_codigo = :codigo 
             AND DATE(data_hora) = :hoje 
             ORDER BY data_hora DESC"; 

try {
    $stmt_read = $pdo->prepare($sql_read);
    $stmt_read->bindParam(':codigo', $id_colaborador);
    $stmt_read->bindParam(':hoje', $hoje);
    $stmt_read->execute();
    $pontos_do_dia = $stmt_read->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // A falha na leitura não deve parar o sistema, apenas exibe um erro
    $mensagem_read = "<p style='color:red;'>Erro ao carregar histórico: " . $e->getMessage() . "</p>";
}

?>