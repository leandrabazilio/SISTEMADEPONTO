<main class="home">
    <p>Acesse aqui para bater seu ponto ou vá para o Cadastro se for novo.</p>
    
    <a href="login.php">Fazer Login / Bater Ponto</a>
    <hr>
    
    <h2>Novo Cadastro</h2>
    <?php echo $mensagem_cadastro; ?>
    
    <form method="POST" action="index.php">
        <input type="hidden" name="acao" value="cadastrar">
        <label>Nome:</label><input type="text" name="nome" required><br>
        <label>Login:</label><input type="text" name="login" required><br>
        <label>Senha:</label><input type="password" name="senha" required><br>
        <button type="submit">Criar Cadastro</button>
    </form>
</main>