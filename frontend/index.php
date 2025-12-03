<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Página de Login</title>
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
  <h2>Login</h2>
  <div id="mensagemErro" class="message" style="color: red;"></div>
  <form id="formLogin" action="../sistemadeponto/login.php" method="post">
    <label for="login">Usuário:</label>
    <input type="text" id="login" name="login" required><br><br>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br><br>

    <button type="submit" id="loginButton">Entrar</button>

    <p style="text-align: center; margin-top: 20px;"><a href="cadastro.php">Cadastre-se</a></p>
  </form>
      </div>

  <script>
    document.getElementById('formLogin').addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        const form = event.target;
        const loginButton = document.getElementById('loginButton');
        const mensagemErro = document.getElementById('mensagemErro');
        
        const data = {
            login: form.login.value,
            senha: form.senha.value
        };

        loginButton.disabled = true;
        mensagemErro.textContent = '';

        fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json().then(data => ({ ok: response.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.status === 'ok') {
                window.location.href = 'painel.php'; // Redireciona para o painel
            } else {
                mensagemErro.textContent = data.erro || 'Ocorreu um erro inesperado.';
                loginButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erro no login:', error);
            mensagemErro.textContent = 'Não foi possível conectar ao servidor.';
            loginButton.disabled = false;
        });
    });
  </script>
</body>
</html>