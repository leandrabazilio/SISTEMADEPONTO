<?php

session_start();

require_once 'conexao.php'; 
date_default_timezone_set('America/Sao_Paulo'); // Garante que a hora da exibição está correta


// VERIFICAÇÃO DE SEGURANÇA
// Apenas administradores (tipo_acesso = 'Administrador') podem ver esta página

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_acesso'] !== 'Administrador') {
    // Redireciona para o login se não for admin
    header('Location: login.php');
    exit();
}

$nome_admin = $_SESSION['usuario_nome'];
$relatorio_pontos = [];
$mensagem_erro = "";


//  LÓGICA DE RELATÓRIO (READ com JOIN)
// Seleciona o nome do funcionário e os dados do ponto, unindo as duas tabelas (Funcionarios e Pontos)

$sql_relatorio = "
    SELECT 
        F.nome, 
        P.data_hora, 
        P.tipo_registro
    FROM 
        Pontos P
    JOIN 
        Funcionarios F ON P.funcionario_codigo = F.codigo
    ORDER BY 
        P.data_hora DESC
"; 

try {
    // query() é usada para consultas SELECT que não usam variáveis (parâmetros)
    $stmt_relatorio = $pdo->query($sql_relatorio);
    $relatorio_pontos = $stmt_relatorio->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar relatório: " . $e->getMessage();
}

?>