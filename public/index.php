<?php
session_start();
include '../config.php';
include '../db.php';
include '../functions.php';

if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php'); // Redireciona para login se não estiver logado
    exit();
}

// Inicializa a variável de mensagem
$message = '';

// Verifica se há uma mensagem de erro na sessão
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Remove a mensagem após exibição
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = dbConnect();

    // Prepara e executa a consulta
    $stmt = $conn->prepare('SELECT * FROM usuario WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['tipo'] == '2') {
            // Tipo 2 é Médico, não pode fazer login
            $_SESSION['message'] = 'Médicos só podem se cadastrar, não fazer login.';
            redirect('index.php');
            exit();
        } elseif (password_verify($password, $user['password'])) {
            // Tipo 1 é Paciente e pode fazer login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['tipo'] = $user['tipo'];
            redirect('dashboard.php');
            exit();
        } else {
            $_SESSION['message'] = 'Usuário ou senha inválidos.';
            redirect('index.php');
            exit();
        }
    } else {
        $_SESSION['message'] = 'Usuário não encontrado.';
        redirect('index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="login-container" class="section">
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <h2>Login</h2>
        <form action="index.php" method="post">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p>Não possui uma conta? <a href="register.php">Registrar-se</a></p>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
