<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <title>Painel do Administrador</title>
</head>
<body>

    <div class="topo">
        <h1>Painel do Administrador</h1>
    </div>

    <nav class="menu-lateral">
        <div class="btn-expandir">
            <i class="bi bi-list" id="btn-exp"></i>
        </div>

        <ul>
            <li class="nav-item ativo">
                <a href="#">
                    <span class="icon"><i class="bi bi-house-door"></i></span>
                    <span class="txt-link">Início</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <span class="icon"><i class="bi bi-people"></i></span>
                    <span class="txt-link">Gerenciar Colaboradores</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <span class="icon"><i class="bi bi-journal-text"></i></span>
                    <span class="txt-link">Relatórios</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <span class="icon"><i class="bi bi-gear"></i></span>
                    <span class="txt-link">Configurações</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>

    <main>
        <!-- O conteúdo principal do painel do administrador virá aqui -->
        <h2>Bem-vindo, Administrador!</h2>
        <p>Aqui você poderá gerenciar o sistema.</p>
    </main>

    <script src="menu.js"></script> <!-- Se você tiver um JS para o menu -->

</body>
</html>