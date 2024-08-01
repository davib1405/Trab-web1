<?php
session_start();
include '../config.php';
include '../db.php';
include '../functions.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    redirect('index.php'); // Redireciona para login se não estiver logado
    exit();
}

// Processa o logout se solicitado
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    redirect('index.php'); // Redireciona para a página de login após logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Enviado</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Obrigado pelo feedback!</h1>
        <div class="message">
            Seu feedback foi salvo com sucesso.
        </div>
        <a href="custom_avatar.php" class="button">
            <span class="icon">&#128100;</span> Desejo atualizar meu Avatar
        </a>
        <form action="page_sucess.php" method="post">
            <button type="submit" name="logout" class="button exit">
                <span class="icon">&#10060;</span> Sair
            </button>
        </form>
    </div>
</body>
</html>
