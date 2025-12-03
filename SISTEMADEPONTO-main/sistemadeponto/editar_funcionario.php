<?php

require_once 'conexao.php';

// Verifica se o ID foi enviado
if (!isset($_GET['id'])) {
    echo "ID do funcionário não informado.";
    exit;
}

$id = intval($_GET['id']);

// Se for POST → atualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'] ?? null;
    $login = $_POST['login'] ?? null;
    $tipo = $_POST['tipo_usuario'] ?? null;
    $senhaNova = $_POST['senha'] ?? null;

    if (!$nome || !$login || !$tipo) {
        echo "Dados incompletos.";
        exit;
    }

    if (!empty($senhaNova)) {
        // Atualiza com senha nova
        $senha_hash = password_hash($senhaNova, PASSWORD_DEFAULT);

        $sql = "UPDATE funcionarios 
                SET nome = ?, login = ?, senha = ?, tipo_usuario = ?
                WHERE codigo = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $login, $senha_hash, $tipo, $id]);

    } else {
        // Atualiza sem alterar a senha
        $sql = "UPDATE funcionarios 
                SET nome = ?, login = ?, tipo_usuario = ?
                WHERE codigo = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $login, $tipo, $id]);
    }

    echo "Funcionário atualizado com sucesso.";
    exit;
}

// Se não for POST → obter dados para edição
$sql = "SELECT * FROM funcionarios WHERE codigo = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$funcionario) {
    echo "Funcionário não encontrado.";
    exit;
}

echo json_encode($funcionario);
?>