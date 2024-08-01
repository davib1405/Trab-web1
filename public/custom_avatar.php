<?php
session_start();
include '../config.php';
include '../db.php';
include '../functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('index.php'); // Redireciona para login se não estiver logado
    exit();
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $userId = $_SESSION['user_id'];
    $skinColor = $_POST['skinColor'];
    $hairColor = $_POST['hairColor'];
    $sentimento = $_POST['sentimento'];
    $hairType = $_POST['hairType'];
    $clotheType = $_POST['clotheType'];
    $clothesColor = $_POST['clothesColor'];

    $conn = dbConnect();

    // Verifica se o usuário já tem configurações de avatar
    $stmt = $conn->prepare('SELECT id FROM avatar_config WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Atualiza as configurações existentes
        $stmt = $conn->prepare('UPDATE avatar_config SET skin_color = ?, hair_color = ?, sentimento = ?, hair_type = ?, clothe_type = ?, clothes_color = ? WHERE user_id = ?');
        $stmt->bind_param('ssssssi', $skinColor, $hairColor, $sentimento, $hairType, $clotheType, $clothesColor, $userId);
    } else {
        // Insere novas configurações
        $stmt = $conn->prepare('INSERT INTO avatar_config (user_id, skin_color, hair_color, sentimento, hair_type, clothe_type, clothes_color) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issssss', $userId, $skinColor, $hairColor, $sentimento, $hairType, $clotheType, $clothesColor);
    }

    $stmt->execute();
    $stmt->close();

    // Redireciona para a página de customização com uma mensagem de sucesso
    $_SESSION['message'] = 'Configurações de avatar salvas com sucesso!';
    redirect('dashboard.php');
    exit();
}

// Conecta ao banco para carregar as configurações existentes
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $conn = dbConnect();

    $stmt = $conn->prepare('SELECT * FROM avatar_config WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $avatarSettings = $result->fetch_assoc();

    if ($avatarSettings) {
        $skinColor = $avatarSettings['skin_color'];
        $hairColor = $avatarSettings['hair_color'];
        $sentimento = $avatarSettings['sentimento'];
        $hairType = $avatarSettings['hair_type'];
        $clotheType = $avatarSettings['clothe_type'];
        $clothesColor = $avatarSettings['clothes_color'];
    } else {
        // Define valores padrão se não houver configurações
        $skinColor = 'ffdbac';
        $hairColor = '000000';
        $sentimento = '1';
        $hairType = 'curvy';
        $clotheType = 'blazerAndShirt';
        $clothesColor = '3c4f5c';
    }
} else {
    // Define valores padrão se o usuário não estiver logado
    $skinColor = 'ffdbac';
    $hairColor = '000000';
    $sentimento = '1';
    $hairType = 'curvy';
    $clotheType = 'blazerAndShirt';
    $clothesColor = '3c4f5c';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customização de Avatar</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-column {
            flex: 1;
            margin: 0 10px;
        }

        .form-column h3 {
            margin-top: 0;
        }

        .button-container {
            margin-bottom: 20px;
        }

        .button-container label {
            display: block;
        }

    </style>
</head>
<body>
    <div id="avatar-section" class="section">
        <h2>Customização de Avatar</h2>
        <div class="avatar-container">
            <img id="avatar" src="" alt="Avatar" />
        </div>
        <form id="avatar-form" method="post" action="custom_avatar.php">
            <div class="form-row">
                <div class="form-column">
                    <div class="button-container">
                        <h3>Cor da Pele:</h3>
                        <label><input type="radio" name="skinColor" class="skinCorButton" value="ffdbac" <?php echo $skinColor == 'ffdbac' ? 'checked' : ''; ?>> Claro</label>
                        <label><input type="radio" name="skinColor" class="skinCorButton" value="f1c27d" <?php echo $skinColor == 'f1c27d' ? 'checked' : ''; ?>> Médio</label>
                        <label><input type="radio" name="skinColor" class="skinCorButton" value="e0ac69" <?php echo $skinColor == 'e0ac69' ? 'checked' : ''; ?>> Escuro</label>
                        <label><input type="radio" name="skinColor" class="skinCorButton" value="8d5524" <?php echo $skinColor == '8d5524' ? 'checked' : ''; ?>> Mais Escuro</label>
                    </div>

                    <div class="button-container">
                        <h3>Tipo de Roupa:</h3>
                        <select id="clotheType" name="clotheType">
                            <option value="blazerAndShirt" <?php echo $clotheType == 'blazerAndShirt' ? 'selected' : ''; ?>>Blazer e Camiseta</option>
                            <option value="blazerAndSweater" <?php echo $clotheType == 'blazerAndSweater' ? 'selected' : ''; ?>>Blazer e Moletom</option>
                            <option value="hoodie" <?php echo $clotheType == 'hoodie' ? 'selected' : ''; ?>>Moletom</option>
                            <option value="collarAndSweater" <?php echo $clotheType == 'collarAndSweater' ? 'selected' : ''; ?>>Camisa e Moletom</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <h3>Cor do Cabelo:</h3>
                        <label><input type="radio" name="hairColor" class="hairCorButton" value="000000" <?php echo $hairColor == '000000' ? 'checked' : ''; ?>> Preto</label>
                        <label><input type="radio" name="hairColor" class="hairCorButton" value="b6a57a" <?php echo $hairColor == 'b6a57a' ? 'checked' : ''; ?>> Loiro</label>
                        <label><input type="radio" name="hairColor" class="hairCorButton" value="724133" <?php echo $hairColor == '724133' ? 'checked' : ''; ?>> Castanho</label>
                    </div>
                </div>
                <div class="form-column">
                    <div class="button-container">
                        <h3>Tipo de Sentimento:</h3>
                        <label><input type="radio" name="sentimento" class="sentimentoInput" value="0" <?php echo $sentimento == '0' ? 'checked' : ''; ?>> Triste</label>
                        <label><input type="radio" name="sentimento" class="sentimentoInput" value="1" <?php echo $sentimento == '1' ? 'checked' : ''; ?>> Normal</label>
                        <label><input type="radio" name="sentimento" class="sentimentoInput" value="2" <?php echo $sentimento == '2' ? 'checked' : ''; ?>> Raiva</label>
                    </div>

                    <div class="button-container">
                        <h3>Tipo de Cabelo:</h3>
                        <select id="hairType" name="hairType">
                            <option value="curvy" <?php echo $hairType == 'curvy' ? 'selected' : ''; ?>>Ondulado</option>
                            <option value="dreads01" <?php echo $hairType == 'dreads01' ? 'selected' : ''; ?>>Arrepiado</option>
                            <option value="curly" <?php echo $hairType == 'curly' ? 'selected' : ''; ?>>Encaracolado</option>
                            <option value="frizzle" <?php echo $hairType == 'frizzle' ? 'selected' : ''; ?>>Raspado</option>
                        </select>
                    </div>

                    <div class="button-container">
                        <h3>Cor da Roupa:</h3>
                        <label><input type="radio" name="clothesColor" class="clothesColorButton" value="3c4f5c" <?php echo $clothesColor == '3c4f5c' ? 'checked' : ''; ?>> Azul Escuro</label>
                        <label><input type="radio" name="clothesColor" class="clothesColorButton" value="65c9ff" <?php echo $clothesColor == '65c9ff' ? 'checked' : ''; ?>> Azul Claro</label>
                        <label><input type="radio" name="clothesColor" class="clothesColorButton" value="262e33" <?php echo $clothesColor == '262e33' ? 'checked' : ''; ?>> Preto</label>
                        <label><input type="radio" name="clothesColor" class="clothesColorButton" value="ff5c5c" <?php echo $clothesColor == 'ff5c5c' ? 'checked' : ''; ?>> Vermelho</label>
                    </div>
                </div>
            </div>

            <button type="submit">Salvar</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
