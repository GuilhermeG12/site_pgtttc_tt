<?php
$servername = "localhost"; // Servidor MySQL (geralmente localhost no XAMPP)
$username = "root"; // Usuário padrão do phpMyAdmin
$password = ""; // Senha vazia no XAMPP por padrão
$dbname = "checklist_db"; // Nome do banco de dados

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptografando senha
    
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $conn->error . "'); window.location.href='cadastro.php';</script>";
    }
}
$conn->close();
?>
