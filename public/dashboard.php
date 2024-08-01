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

// Inicializa variáveis
$message = '';
$doctors = [];

// Função para carregar médicos
function loadDoctors($conn) {
    $stmt = $conn->prepare('SELECT id, username FROM usuario WHERE tipo = 2');
    $stmt->execute();
    $result = $stmt->get_result();
    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    return $doctors;
}

// Função para carregar dados do usuário
function loadUserAvatarData($conn, $userId) {
    $stmt = $conn->prepare('SELECT skin_color, hair_color, sentimento, hair_type, clothe_type, clothes_color FROM avatar_config WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Processa o formulário de salvamento
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        redirect('index.php'); 
        exit();
    }

    $conn = dbConnect();
    $doctor_id = $_POST['doctor_id'];
    $feeling = $_POST['feeling'];

    $stmt = $conn->prepare('INSERT INTO user_in_doctor (user_id, doctor_id, feeling) VALUES (?, ?, ?)');
    $stmt->bind_param('iis', $_SESSION['user_id'], $doctor_id, $feeling);
    $stmt->execute();

    $_SESSION['message'] = 'Dados salvos com sucesso!';
    redirect('feedback.php'); // Redireciona após salvar
    exit();
}

$conn = dbConnect();
$doctors = loadDoctors($conn);
$userAvatarData = loadUserAvatarData($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="dashboard.php" method="post" style="display: inline;">
        <button type="submit" name="logout" class="logout-button">Sair</button>
    </form>
    <div id="dashboard" class="section">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <h2>Dashboard</h2>
        <h3>Selecione o Médico:</h3>
        <form action="dashboard.php" method="post">
            <select id="doctor-select" name="doctor_id" required>
                <option value="" disabled selected>Escolha um médico</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo htmlspecialchars($doctor['id']); ?>"><?php echo htmlspecialchars($doctor['username']); ?></option>
                <?php endforeach; ?>
            </select>

            <h3>Como você está se sentindo?</h3>
            <div id="feelings-options">
                <label>
                    <input type="radio" name="feeling" value="crying" id="feeling-crying-option">
                    <img id="feeling-crying" class="feeling-avatar" value="Chorando" alt="Chorando" src="">
                </label>
                <label>
                    <input type="radio" name="feeling" value="smiling" id="feeling-smiling-option">
                    <img id="feeling-smiling" class="feeling-avatar" value="Sorrindo" alt="Sorrindo" src="">
                </label>
            </div>
            <button type="submit">Salvar</button>
        </form>

        <!-- Campos invisíveis para JavaScript -->
        <input type="hidden" id="skin-color" value="<?php echo htmlspecialchars($userAvatarData['skin_color']); ?>">
        <input type="hidden" id="hair-color" value="<?php echo htmlspecialchars($userAvatarData['hair_color']); ?>">
        <input type="hidden" id="sentimento" value="<?php echo htmlspecialchars($userAvatarData['sentimento']); ?>">
        <input type="hidden" id="hair-type" value="<?php echo htmlspecialchars($userAvatarData['hair_type']); ?>">
        <input type="hidden" id="clothe-type" value="<?php echo htmlspecialchars($userAvatarData['clothe_type']); ?>">
        <input type="hidden" id="clothes-color" value="<?php echo htmlspecialchars($userAvatarData['clothes_color']); ?>">
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
