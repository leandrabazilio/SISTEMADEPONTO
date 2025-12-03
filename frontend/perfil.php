<?php
require_once '../sistemadeponto/conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$nome_usuario = '';
$login_usuario = '';
$tipo_acesso = '';
$mensagem_erro = '';
$mensagem_sucesso = '';

// Buscar dados do usuário logado
try {
    $sql = "SELECT codigo, nome, login, tipo_usuario FROM Funcionarios WHERE codigo = :codigo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigo', $usuario_id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nome_usuario = htmlspecialchars($usuario['nome']);
        $login_usuario = htmlspecialchars($usuario['login']);
        $tipo_acesso = htmlspecialchars($usuario['tipo_usuario']);
    } else {
        $mensagem_erro = "Dados do usuário não encontrados.";
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar dados do perfil: " . $e->getMessage();
}

?>

<main class="conteudo">
    <div class="content-card">
        <h2>Meu Perfil</h2>
    
        <?php if (!empty($mensagem_erro)): ?>
            <div class="message error"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>
    
        <div id="feedbackMessage" class="message" style="display: none;"></div>
    
        <form id="formPerfil" class="form-perfil">
            <input type="hidden" name="codigo" value="<?php echo $usuario_id; ?>">
            
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $nome_usuario; ?>" required>
    
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?php echo $login_usuario; ?>" disabled>
            <small>O login não pode ser alterado.</small>
            <br><br>
    
            <label for="tipo_usuario">Tipo de Usuário:</label>
            <input type="text" id="tipo_usuario" name="tipo_usuario" value="<?php echo $tipo_acesso; ?>" disabled>
            <small>O tipo de usuário não pode ser alterado.</small>
            <br><br>
    
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</main>

<script>
document.getElementById('formPerfil').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const feedbackMessage = document.getElementById('feedbackMessage');
    feedbackMessage.style.display = 'none';
    feedbackMessage.className = 'message';

    const data = {
        codigo: form.codigo.value,
        nome: form.nome.value
    };

    fetch('../sistemadeponto/atualizar_perfil.php', {
        method: 'POST', // Usando POST para simplicidade, mas PUT seria mais RESTful
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'ok') {
            feedbackMessage.textContent = result.mensagem;
            feedbackMessage.classList.add('success');
            feedbackMessage.style.display = 'block';
            // Opcional: Atualizar o nome na sessão do frontend ou recarregar a página para refletir a mudança
            // window.location.reload(); 
        } else {
            feedbackMessage.textContent = result.erro || 'Erro ao atualizar perfil.';
            feedbackMessage.classList.add('error');
            feedbackMessage.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        feedbackMessage.textContent = 'Não foi possível conectar ao servidor para atualizar o perfil.';
        feedbackMessage.classList.add('error');
        feedbackMessage.style.display = 'block';
    });
});
</script>