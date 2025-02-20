<?php
session_start(); // Começa a sessão para garantir que as variáveis de sessão sejam usadas

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "checklist_db";

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['senha'])) {
            // Armazenando o ID do usuário na sessão
            $_SESSION['user_id'] = $row['id'];
            echo "<script>alert('Login realizado com sucesso!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location.href='login.php';</script>";
    }
}

$conn->close();
?>
