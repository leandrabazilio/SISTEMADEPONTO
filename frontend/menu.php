<aside>
<nav class="menu-lateral">
    <div class="btn-expandir">
        <i class="bi bi-three-dots-vertical" id="btn-exp"></i>
    </div> 
    <ul>
        <li class="nav-item">
            <a class="nav-link active" href="painel.php?pg=home">
                <span class="icon"><i class="bi bi-house"></i></span>
                <span class="txt-link">Home</span>
            </a>
        </li>

        <li class="nav-item">
                <a class="nav-link" href="painel.php?pg=pontos">
                    <span class="icon"><i class="bi bi-clipboard2-data-fill"></i></span>
                    <span class="txt-link">Relatório de Pontos</span>
                </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="painel.php?pg=perfil">
                <span class="icon"><i class="bi bi-person-circle"></i></span>
                <span class="txt-link">Meu Perfil</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" id="logout-btn">
                <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                <span class="txt-link">Sair</span>
            </a>
        </li>
    </ul>
</nav>
</aside>
<script src="menu.js"></script>
<script src="click.js"></script>

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