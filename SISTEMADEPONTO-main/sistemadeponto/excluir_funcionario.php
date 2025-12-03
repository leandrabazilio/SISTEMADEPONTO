<?php

require_once 'conexao.php';

if (!isset($_GET['id'])) {
    echo "ID não informado.";
    exit;
}

$id = intval($_GET['id']);

$sql = "DELETE FROM funcionarios WHERE codigo = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$id])) {
    echo "Funcionário excluído com sucesso.";
} else {
    echo "Erro ao excluir funcionário.";
}

?>