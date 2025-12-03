<?php
require_once '../sistemadeponto/conexao.php';

// Determina qual usuário editar.
// Se um 'id' for passado na URL E o usuário logado for um Administrador, edita esse 'id'.
// Caso contrário, o usuário edita o próprio perfil.
$id_para_editar = $_SESSION['usuario_id']; // Padrão: editar a si mesmo
$is_admin_editando_outro = false;

if (isset($_GET['id']) && $_SESSION['tipo_acesso'] === 'Administrador') {
    $id_para_editar = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $is_admin_editando_outro = true;
}

$nome_usuario = '';
$login_usuario = '';
$tipo_acesso = '';
$mensagem_erro = '';
$mensagem_sucesso = '';

try {
    $sql = "SELECT codigo, nome, login, tipo_usuario FROM Funcionarios WHERE codigo = :codigo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':codigo', $id_para_editar);
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
        <h2><?php echo $is_admin_editando_outro ? 'Editar Perfil de Colaborador' : 'Meu Perfil'; ?></h2>
    
        <?php if (!empty($mensagem_erro)): ?>
            <div class="message error"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>
    
        <div id="feedbackMessage" class="message" style="display: none;"></div>
    
        <form id="formPerfil" class="form-perfil">
            <input type="hidden" name="codigo" value="<?php echo $id_para_editar; ?>">
            
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $nome_usuario; ?>" required>
    
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?php echo $login_usuario; ?>" disabled>
            <small>O login não pode ser alterado.</small>
            <br><br>
    
            <label for="tipo_usuario">Tipo de Usuário:</label>            
            <?php if ($_SESSION['tipo_acesso'] === 'Administrador'): ?>
                <select id="tipo_usuario" name="tipo_usuario">
                    <option value="Administrador" <?php echo ($tipo_acesso === 'Administrador') ? 'selected' : ''; ?>>
                        Administrador
                    </option>
                    <option value="Colaborador" <?php echo ($tipo_acesso === 'Colaborador') ? 'selected' : ''; ?>>
                        Colaborador
                    </option>
                </select>
            <?php else: ?>
                <input type="text" id="tipo_usuario" name="tipo_usuario" value="<?php echo $tipo_acesso; ?>" disabled>
            <?php endif; ?>
            <small>O tipo de usuário só pode ser alterado por um administrador.</small>
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
        nome: form.nome.value,
        tipo_usuario: form.tipo_usuario ? form.tipo_usuario.value : null // Envia o tipo de usuário se o campo existir
    };

    fetch('../sistemadeponto/editar_funcionario.php', {
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