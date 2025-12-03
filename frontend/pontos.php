<?php
// Inclui a conexão e inicia a sessão (que já é feita em conexao.php)
require_once '../sistemadeponto/conexao.php';

// 1. VERIFICAÇÃO DE SEGURANÇA
// Apenas usuários logados podem acessar esta página.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?erro=acesso_negado');
    exit();
}

// Variáveis para armazenar os dados e possíveis erros
$titulo_relatorio = "Relatório de Pontos";
$relatorio_pontos = [];
$mensagem_erro = "";
$tipo_acesso = $_SESSION['tipo_acesso'];
$usuario_id = $_SESSION['usuario_id'];

// 2. LÓGICA DE BUSCA DO RELATÓRIO (Backend)
if ($tipo_acesso === 'Administrador') {
    $titulo_relatorio = "Relatório Geral de Pontos";
    // SQL para Admin: busca todos os pontos de todos os funcionários.
    $sql_relatorio = "
        SELECT F.nome, P.data_hora, P.tipo_registro
        FROM Pontos P
        JOIN Funcionarios F ON P.funcionario_codigo = F.codigo
        ORDER BY P.data_hora DESC";
    $stmt_relatorio = $pdo->prepare($sql_relatorio);
} else { // Colaborador
    $titulo_relatorio = "Meus Registros de Ponto";
    // SQL para Colaborador: busca apenas os pontos do usuário logado.
    $sql_relatorio = "
        SELECT F.nome, P.data_hora, P.tipo_registro
        FROM Pontos P
        JOIN Funcionarios F ON P.funcionario_codigo = F.codigo
        WHERE P.funcionario_codigo = :usuario_id
        ORDER BY P.data_hora DESC";
    $stmt_relatorio = $pdo->prepare($sql_relatorio);
    $stmt_relatorio->bindParam(':usuario_id', $usuario_id);
}

try {
    // Executa a consulta e busca todos os resultados
    $stmt_relatorio->execute();
    $relatorio_pontos = $stmt_relatorio->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Armazena uma mensagem de erro se a consulta falhar
    $mensagem_erro = "Erro ao carregar o relatório de pontos: " . $e->getMessage();
}

?>

<main class="conteudo">
    <div class="content-card">
        <h2><?php echo $titulo_relatorio; ?></h2>
    
        <?php if (!empty($mensagem_erro)): ?>
            <p class="message msg-erro"><?php echo htmlspecialchars($mensagem_erro); ?></p>
        <?php elseif (empty($relatorio_pontos)): ?>
            <p>Nenhum registro de ponto encontrado.</p>
        <?php else: ?>
            <table class="tabela-pontos">
                <thead>
                    <tr>
                        <?php if ($tipo_acesso === 'Administrador'): ?>
                            <th>Funcionário</th>
                        <?php endif; ?>
                        <th>Data e Hora</th>
                        <th>Tipo de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relatorio_pontos as $ponto): ?>
                        <tr>
                            <?php if ($tipo_acesso === 'Administrador'): ?>
                                <td><?php echo htmlspecialchars($ponto['nome']); ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($ponto['data_hora']))); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $ponto['tipo_registro']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>