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
// Parte do código onde o formulário é processado (cadastrandotaf.php)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descricao = $_POST['descricao'];
    $sub_atividade = $_POST['sub_atividade']; // Subtarefas separadas por vírgula
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

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CHECKLIST</title>
</head>

<body>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php elseif (!empty($success)): ?>
        <script type="text/javascript">
            alert("Tarefa cadastrada com sucesso!");
            window.location.href = "index.php"; // Redireciona para a página index.php após o clique em OK
        </script>
    <?php endif; ?>

    <div class="links">
        <ul class="menu">
            <li>
                <a href="cadastro.html"><img src="./img/logo_marca.png" id="logo" alt="Logo img"></a>
            </li>
            <li id="link">
                <a href="index.php">Tarefas Diarias ⮟</a>
            </li>
            <li id="2">
                <a href="cadastrodetaref.php">Cadastro de Tarefas ⮞</a>
            </li>
            <li id="3">
                <a href="notificacoes.php">Notificações ⮟</a>
            </li>
            <li id="4">
                <a href="editartarefas.php"> Editar ⮟</a>
            </li>
            <li id="5">
                <a href="status.php">Status de Tarefas ⮟</a>
            </li>
        </ul>
    </div>

    <h1>CADASTRAR TAREFAS:</h1>

    <div class="form-container">
        <form action="cadastrodetaref.php" method="POST">
            <div class="box-form">
                <input type="text" name="descricao" placeholder="Descreva a Atividade" required><br>
            </div>
            <div class="box-form1">
                <input type="text" name="sub_atividade" placeholder="Descreva uma Sub-Atividade"><br>
            </div>
            <select name="categoria" required>
                <option value="">Escolha a categoria dessa tarefa/rotina :</option>
                <option value="trabalho">Trabalho</option>
                <option value="pessoal">Pessoal</option>
                <option value="estudos">Estudos</option>
                <option value="negocios">Negócios</option>
                <option value="outros">Outros</option>
            </select><br><br>

            <select name="prioridade" required>
                <option value="">Escolha o tipo de prioridade :</option>
                <option value="baixa">Baixa</option>
                <option value="media">Média</option>
                <option value="alta">Alta</option>
            </select><br><br>

            <div class="box-form2">
                <label for="inicio">Defina um horário de início:</label>
                <input type="time" name="inicio"><br>
            </div>
            <div class="box-form3">
                <label for="termino">Defina um horário de término:</label>
                <input type="time" name="termino"><br>
            </div>

            <div class="button">
                <button type="submit">Cadastrar Tarefa</button><br>
            </div>
        </form>
    </div>
</body>

</html>
