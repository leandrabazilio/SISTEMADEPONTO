
<?php

require_once 'conexao.php'; 

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['tipo_acesso'] === 'Administrador') {
        header('Location: painel_admin.php');
    } else {
        header('Location: painel_colaborador.php');
    }
    exit();
}

$erro_login = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_digitado = trim($_POST['login']);
    $senha_digitada = $_POST['senha'];

    $sql = "SELECT codigo, nome, senha, tipo_usuario FROM Funcionarios WHERE login = :login";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login_digitado);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica o usuário E a senha usando o hash
        if ($usuario && password_verify($senha_digitada, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['codigo'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_acesso'] = $usuario['tipo_usuario'];
            
            // Redirecionamento após sucesso
            if ($usuario['tipo_acesso'] === 'Administrador') {
                header('Location: painel_admin.php');
            } else {
                header('Location: painel_colaborador.php');
            }
            exit();
            
        } else {
            $erro_login = "Login ou senha incorretos. Tente novamente.";
        }
        
    } catch (PDOException $e) {
        $erro_login = "Erro na autenticação: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
    <h2>Acesso ao Sistema</h2>
    <?php if ($erro_login): ?><p style="color:red;"><?php echo $erro_login; ?></p><?php endif; ?>
    
    <form method="POST" action="login.php">
        <label>Login:</label><input type="text" name="login" required><br>
        <label>Senha:</label><input type="password" name="senha" required><br>
        <button type="submit">Entrar</button>
    </form>
    
    <p><a href="index.php">Voltar</a></p>
</body>
</html>

