<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CHECKLIST</title>
</head>

<body>
    <div class="links">
        <ul class="menu">
            <li>
                <a href="cadastro.php"><img src="./img/logo_marca.png" id="logo" alt="Logo img"></a>
            </li>
            <li>
                <a href="index.php">Tarefas Diarias ⮟</a>
            </li>
            <li>
                <a href="cadastrodetaref.php">Cadastro de Tarefas ⮟</a>
            </li>
            <li>
                <a href="notificacoes.php">Notificações ⮟</a>
            </li>
            <li>
                <a href="editartarefas.php"> Editar Tarefas ⮟</a>
            </li>
            <li>
                <a href="status.php">Status de Tarefas ⮞</a>
            </li>
        </ul>

    </div>

    <h1>STATUS DE TAREFAS:</h1>

    <div class="tarefas">
        <div class="concluidas">
            <h2>CONCLUÍDA</h2>
            <ul>
                <li><input type="checkbox" checked disabled> Tarefa 1</li>
                <li><input type="checkbox" checked disabled> Tarefa 2</li>
                <li><input type="checkbox" checked disabled> Tarefa 3</li>
                <li><input type="checkbox" checked disabled> Tarefa 4</li>
            </ul>
        </div>
        <div class="incompletas">
            <h2>INCOMPLETA</h2>
            <ul>
                <li><input type="checkbox" disabled> Tarefa 5</li>
                <li><input type="checkbox" disabled> Tarefa 6</li>
                <li><input type="checkbox" disabled> Tarefa 7</li>
                <li><input type="checkbox" disabled> Tarefa 8</li>
            </ul>
        </div>
    </div>
</body>

</html>
