<?php

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
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <style>
        /* Estilo simples para melhor visualização da tabela */
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Painel do Administrador - Relatório Completo</h1>
    <p>Bem-vindo(a), <?php echo $nome_admin; ?>! | <a href="logout.php">Sair</a></p>
    <hr>
    
    <h2>Relatório de Todos os Pontos Batidos</h2>

    <?php if (!empty($mensagem_erro)): ?>
        <p style='color:red;'><?php echo $mensagem_erro; ?></p>
    <?php elseif (empty($relatorio_pontos)): ?>
        <p>Ainda não há registros de ponto no sistema.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Data e Hora</th>
                    <th>Tipo de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relatorio_pontos as $registro): ?>
                <tr>
                    <td><?php echo htmlspecialchars($registro['nome']); ?></td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($registro['data_hora'])); ?></td>
                    <td><?php echo ucfirst($registro['tipo_registro']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>