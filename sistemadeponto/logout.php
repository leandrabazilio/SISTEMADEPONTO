<?php

session_start();
session_unset();  // Remove as variáveis de sessão
session_destroy(); // Destrói a sessão

header('Content-Type: application/json');
echo json_encode(["status" => "ok", "mensagem" => "Sessão encerrada"]);
?>
