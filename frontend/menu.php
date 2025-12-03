<?php
// Garante que a sessão está iniciada para acessar a variável 'tipo_acesso'
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tipo_acesso = $_SESSION['tipo_acesso'] ?? 'Colaborador'; // Define um padrão para segurança
$pagina_atual = $_GET['pg'] ?? 'home';
?>
<aside>
<nav class="menu-lateral">
    <div class="btn-expandir">
        <i class="bi bi-list" id="btn-exp"></i>
    </div> 
    <ul>
        <li class="nav-item <?php echo ($pagina_atual === 'home') ? 'ativo' : ''; ?>">
            <a class="nav-link" href="painel.php?pg=home">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="txt-link">Início</span>
            </a>
        </li>

        <?php if ($tipo_acesso === 'Administrador'): ?>
            <!-- Itens de Menu do Administrador -->
            <li class="nav-item <?php echo ($pagina_atual === 'gerenciar') ? 'ativo' : ''; ?>">
                <a class="nav-link" href="painel.php?pg=gerenciar">
                    <span class="icon"><i class="bi bi-people"></i></span>
                    <span class="txt-link">Gerenciar Colaboradores</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item <?php echo ($pagina_atual === 'pontos') ? 'ativo' : ''; ?>">
            <a class="nav-link" href="painel.php?pg=pontos">
                <span class="icon"><i class="bi bi-journal-text"></i></span>
                <span class="txt-link">Relatório de Pontos</span>
            </a>
        </li>

        <li class="nav-item <?php echo ($pagina_atual === 'perfil') ? 'ativo' : ''; ?>">
            <a class="nav-link" href="painel.php?pg=perfil">
                <span class="icon"><i class="bi bi-person-circle"></i></span>
                <span class="txt-link">Meu Perfil</span>
            </a>
        </li>

        <li class="nav-item" id="logout-item">
            <a class="nav-link" href="#" id="logout-btn">
                <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                <span class="txt-link">Sair</span>
            </a>
        </li>
    </ul>
</nav>
</aside>

<script>
document.getElementById('logout-btn').addEventListener('click', function(event) {
    event.preventDefault();
    fetch('../sistemadeponto/logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                window.location.href = 'index.php'; // Redireciona para o login
            }
        });
});
</script>