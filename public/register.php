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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $conn = dbConnect();

    // Verifica se o nome de usuário já existe
    $stmt = $conn->prepare('SELECT id FROM usuario WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nome de usuário já existe
        $_SESSION['message'] = 'Nome de usuário já está em uso.';
        redirect('register.php');
        exit();
    }

    // Prepara e executa a consulta para inserir o novo usuário
    $stmt = $conn->prepare('INSERT INTO usuario (username, password, tipo) VALUES (?, ?, ?)');
    $stmt->bind_param('ssi', $username, $password, $tipo);
    $stmt->execute();

    // Pega o id do usuário recém-criado
    $user_id = $stmt->insert_id;

    if ($tipo == '1') {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['tipo'] = $tipo;
        redirect('custom_avatar.php');
        exit();
    } else {
        $_SESSION['message'] = 'Cadastro realizado com sucesso!';
        redirect('register.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar-se</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="register-container" class="section">
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <h2>Registrar-se</h2>
        <form id="register-form" action="register.php" method="post">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <label for="tipo">Tipo de Usuário:</label>
            <select id="tipo" name="tipo" required>
                <option value="1">Paciente</option>
                <option value="2">Médico</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
        <p>Já possui uma conta? <a href="index.php">Login</a></p>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
