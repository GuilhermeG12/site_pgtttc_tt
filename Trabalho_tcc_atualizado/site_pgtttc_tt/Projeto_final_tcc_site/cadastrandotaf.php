<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "checklist_db"; // Banco de dados checklist_db

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Variáveis de erro
$error = "";

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST['descricao'];
    $sub_atividade = $_POST['sub_atividade'];
    $categoria = $_POST['categoria'];
    $prioridade = $_POST['prioridade'];
    $horario_inicio = $_POST['inicio'];
    $horario_termino = $_POST['termino'];

    // Validação
    if (empty($descricao) || empty($categoria) || empty($prioridade)) {
        $error = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // Insere a tarefa no banco de dados
        $sql = "INSERT INTO tarefas_completas (descricao, sub_atividade, categoria, prioridade, horario_inicio, horario_termino, user_id) 
                VALUES ('$descricao', '$sub_atividade', '$categoria', '$prioridade', '$horario_inicio', '$horario_termino', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            $success = "Tarefa cadastrada com sucesso!";
        } else {
            $error = "Erro ao cadastrar a tarefa: " . $conn->error;
        }
    }
}

$conn->close();
?>
