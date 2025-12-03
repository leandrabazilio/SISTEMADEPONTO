
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Colaborador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            color: #e91e63;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #c2185b;
        }
        a {
            color: #e91e63;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Colaborador</h2>
        <form id="formCadastro" action="../sistemadeponto/index.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" >Cadastrar</button>
        </form>
        <p style="text-align: center; margin-top: 20px;"><a href="index.php">Já tem uma conta? Faça login aqui.</a></p>
    </div>

    <script>
        document.getElementById('formCadastro').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio padrão do formulário

            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData
            })
            .then(response => {
                // Você pode adicionar uma verificação da resposta do servidor aqui se ele retornar um status específico
                alert('Cadastro realizado com sucesso!');
                window.location.href = 'index.php'; // Redireciona para a página de login
            })
            .catch(error => {
                console.error('Erro ao cadastrar:', error);
                alert('Ocorreu um erro ao realizar o cadastro. Tente novamente.');
            });
        });
    </script>
</body>
</html>