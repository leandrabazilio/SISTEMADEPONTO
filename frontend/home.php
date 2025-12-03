<?php
// Este arquivo assume que a sessão já foi iniciada pelo painel.php
// e que o usuário está autenticado.

$nome_usuario = htmlspecialchars($_SESSION['usuario_nome']);
$tipo_acesso = $_SESSION['tipo_acesso'];

?>

<main class="conteudo">
    <div class="content-card">
        <h2>Bem-vindo(a), <?php echo $nome_usuario; ?>!</h2>
    
        <?php if ($tipo_acesso === 'Colaborador'): ?>
            <p>Use os botões abaixo para registrar seu ponto.</p>
            
            <div class="painel-botoes">
                <button class="btn-entrada" onclick="baterPonto('entrada')">Registrar Entrada</button>
                <button class="btn-pausa" onclick="baterPonto('inicio_pausa')">Sair para Pausa</button>
                <button class="btn-pausa" onclick="baterPonto('fim_pausa')">Voltar da Pausa</button>
                <button class="btn-saida" onclick="baterPonto('saida')">Registrar Saída</button>
            </div>
    
            <div id="mensagem-ponto" class="message" style="display: none;"></div>
    
        <?php elseif ($tipo_acesso === 'Administrador'): ?>
            <p>Você está no painel de administração. Use o menu lateral para navegar entre os relatórios e funcionalidades.</p>
        <?php endif; ?>
    </div>
</main>

<script>
function baterPonto(tipo) {
    const mensagemDiv = document.getElementById('mensagem-ponto');
    mensagemDiv.style.display = 'none'; // Esconde a mensagem anterior

    // O backend espera os dados como FormData, pois usa $_POST
    const formData = new FormData();
    formData.append('tipo_registro', tipo);

    fetch('../sistemadeponto/painel_colaborador.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            mensagemDiv.className = 'message msg-sucesso';
            mensagemDiv.textContent = data.mensagem;
        } else {
            mensagemDiv.className = 'message msg-erro';
            mensagemDiv.textContent = data.erro || 'Ocorreu um erro desconhecido.';
        }
        mensagemDiv.style.display = 'block'; // Mostra a mensagem
    })
    .catch(error => {
        console.error('Erro ao bater ponto:', error);
        mensagemDiv.className = 'message msg-erro';
        mensagemDiv.textContent = 'Não foi possível conectar ao servidor para registrar o ponto.';
        mensagemDiv.style.display = 'block'; // Mostra a mensagem de erro
    });
}
</script>