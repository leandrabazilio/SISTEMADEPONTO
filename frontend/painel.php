<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="styleconteudo.css">
<link rel="stylesheet" href="stylerodape.css">
<?php
session_start(); // Garante que a sessão seja iniciada para ler as variáveis de sessão.

// 1. VERIFICAÇÃO DE SEGURANÇA: Checa se o usuário está logado.
// Se não houver um 'usuario_id' na sessão, redireciona para a página de login.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php'); // Redireciona para o login
    exit(); // Interrompe a execução do script
}

// Inclui os componentes visuais da página (cabeçalho e menu lateral)
include_once "topo.php";
include_once "menu.php";

// 2. ROTEAMENTO DE PÁGINAS
// Pega o valor do parâmetro 'pg' da URL. Se não existir, usa 'home' como padrão.
$pagina = $_GET['pg'] ?? 'home';

// Cria um array com as páginas permitidas para evitar inclusão de arquivos indesejados.
$paginas_permitidas = ['home', 'pontos', 'perfil', 'gerenciar'];
$paginas_admin = ['gerenciar']; // Páginas que só o admin pode acessar

if (!in_array($pagina, $paginas_permitidas)) {
    // Se a página não existe, mostra erro 404
    $pagina_a_incluir = '404.php';
    echo "<main class='conteudo'><h2>Erro 404: Página não encontrada</h2></main>";
} elseif (in_array($pagina, $paginas_admin) && $_SESSION['tipo_acesso'] !== 'Administrador') {
    // Se um colaborador tenta acessar uma página de admin, mostra acesso negado
    echo "<main class='conteudo'><h2>Acesso Negado</h2><p>Você não tem permissão para acessar esta página.</p></main>";
} else {
    // Se a página for permitida, inclui o arquivo .php correspondente.
    include_once $pagina . '.php';
}

// Inclui o rodapé da página
include_once "rodape.php";
?>

<!-- Scripts que antes estavam no menu.php -->
<script src="menu.js"></script>
<script src="click.js"></script>
