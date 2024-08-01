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

// Função para carregar médico do paciente
function loadDoctorForPatient($conn, $userId) {
    $stmt = $conn->prepare('
        SELECT d.id, d.username 
        FROM user_in_doctor uid
        JOIN usuario d ON uid.doctor_id = d.id
        WHERE uid.user_id = ? 
        ORDER BY uid.timestamp DESC
        LIMIT 1
    ');
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
        redirect('index.php'); // Redireciona para a página de login após logout
        exit();
    }
    
    $conn = dbConnect();
    $timestamp = date('Y-m-d H:i:s');
    $doctor_id = $_POST['doctor_id'];
    $procedure_type = $_POST['procedure_type'];
    $pre_or_post = $_POST['pre_or_post'];
    $atendimento_datetime = $_POST['atendimento_datetime'];

    $stmt = $conn->prepare('
        INSERT INTO feedback (user_id, doctor_id, timestamp, procedure_type, pre_or_post, atendimento_datetime) 
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    $stmt->bind_param('iissss', $_SESSION['user_id'], $doctor_id, $timestamp, $procedure_type, $pre_or_post, $atendimento_datetime);
    $stmt->execute();

    redirect('page_sucess.php'); // Redireciona após salvar
    exit();
}

$conn = dbConnect();
$doctor = loadDoctorForPatient($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="dashboard.php" method="post" style="display: inline;">
        <button type="submit" name="logout" class="logout-button">Sair</button>
    </form>
    <div id="feedback" class="section">
        <h2>Feedback do Paciente</h2>
        <h3>Profissional que atendeu: <?php echo htmlspecialchars($doctor['username']); ?></h3>
        <form action="feedback.php" method="post">
            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor['id']); ?>">

            <h3>Tipo de Procedimento</h3>
            <input type="text" name="procedure_type" required>

            <h3>Data e Hora do Atendimento</h3>
            <input type="datetime-local" name="atendimento_datetime" required>

            <h3>Pré ou Pós Consulta</h3>
            <select name="pre_or_post" required>
                <option value="pré consulta">Pré Consulta</option>
                <option value="pós consulta">Pós Consulta</option>
            </select>

            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>
