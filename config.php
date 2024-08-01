<?php
function dbConnect() {
    $servername = DB_SERVER;
    $username = DB_USER;
    $password = DB_PASS;
    $dbname = DB_NAME;

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    return $conn;
}
?>