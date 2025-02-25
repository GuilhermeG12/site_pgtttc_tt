<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "checklist_db";

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Usuário não autenticado.");
}

$tarefa_id = isset($_POST['tarefa_id']) ? intval($_POST['tarefa_id']) : 0;
if ($tarefa_id > 0) {
    // Atualiza o campo concluida para 1
    $sql = "UPDATE tarefas_status SET concluida = 1 WHERE tarefa_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tarefa_id);
    if ($stmt->execute()) {
        echo "Tarefa marcada como concluída.";
    } else {
        echo "Erro: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "ID de tarefa inválido.";
}
$conn->close();
?>
