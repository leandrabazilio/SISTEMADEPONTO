<?php
// Este arquivo é incluído pelo painel.php, que já inicia a sessão e faz a verificação de segurança.
// Garante que a conexão com o banco de dados esteja disponível.
require_once '../sistemadeponto/conexao.php';

$funcionarios = [];
$mensagem_erro = '';

// A verificação de administrador já é feita em painel.php, mas é uma boa prática
// garantir que apenas administradores executem a consulta.
if ($_SESSION['tipo_acesso'] === 'Administrador') {
    try {
        $sql = "SELECT codigo, nome, login, tipo_usuario FROM Funcionarios ORDER BY nome ASC";
        $stmt = $pdo->query($sql);
        $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao buscar funcionários: " . $e->getMessage();
    }
} else {
    // Esta mensagem não deve aparecer na prática, pois painel.php já bloqueia o acesso.
    $mensagem_erro = "Acesso negado."; 
}
?>

<main class="conteudo">
    <div class="content-card">
        <h2>Gerenciar Colaboradores</h2>

        <div id="feedback-gerenciar" class="message" style="display: none;"></div>

        <?php if (!empty($mensagem_erro)): ?>
            <p class="message msg-erro"><?php echo htmlspecialchars($mensagem_erro); ?></p>
        <?php elseif (empty($funcionarios)): ?>
            <p>Nenhum colaborador cadastrado no momento.</p>
        <?php else: ?>
            <table class="tabela-pontos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Cargo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funcionarios as $func): ?>
                        <tr id="funcionario-<?php echo $func['codigo']; ?>">
                            <td><?php echo htmlspecialchars($func['nome']); ?></td>
                            <td><?php echo htmlspecialchars($func['login']); ?></td>
                            <td><?php echo htmlspecialchars($func['tipo_usuario']); ?></td>
                            <td>
                                <!-- O botão de editar pode levar para a página de perfil do usuário -->
                                <a href="painel.php?pg=perfil&id=<?php echo $func['codigo']; ?>" class="btn-acao btn-editar">Editar</a>
                                
                                <!-- O botão de excluir terá uma função JS para confirmar a ação -->
                                <button onclick="excluirFuncionario(<?php echo $func['codigo']; ?>)" class="btn-acao btn-excluir">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<script>
function excluirFuncionario(codigo) {
    // Pede confirmação antes de prosseguir
    if (!confirm('Tem certeza que deseja excluir este funcionário? Esta ação não pode ser desfeita.')) {
        return;
    }

    const feedbackDiv = document.getElementById('feedback-gerenciar');

    fetch('../sistemadeponto/excluir.funcionario.php', {
        method: 'POST', // Usando POST para enviar um corpo JSON
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ codigo: codigo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            // Remove a linha da tabela
            const linhaParaRemover = document.getElementById('funcionario-' + codigo);
            if (linhaParaRemover) {
                linhaParaRemover.remove();
            }
            
            // Exibe mensagem de sucesso
            feedbackDiv.textContent = data.mensagem;
            feedbackDiv.className = 'message msg-sucesso';
            feedbackDiv.style.display = 'block';

        } else {
            // Exibe mensagem de erro
            feedbackDiv.textContent = data.erro || 'Ocorreu um erro ao excluir.';
            feedbackDiv.className = 'message msg-erro';
            feedbackDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        feedbackDiv.textContent = 'Não foi possível conectar ao servidor.';
        feedbackDiv.className = 'message msg-erro';
        feedbackDiv.style.display = 'block';
    });
}
</script>

<style>
    .btn-acao { padding: 5px 10px; border-radius: 4px; color: white; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
    .btn-editar { background-color: #2196F3; } /* Azul */
    .btn-excluir { background-color: #f44336; } /* Vermelho */
</style>