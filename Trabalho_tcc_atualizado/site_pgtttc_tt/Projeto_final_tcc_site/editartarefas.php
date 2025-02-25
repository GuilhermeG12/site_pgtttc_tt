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
                <a href="cadastro.php" id="cad"><img src="./img/logo_marca.png" id="logo" alt="Logo img"></a>
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
                <a href="editartarefas.php"> Editar Tarefas ⮞</a>
            </li>
            <li>
                <a href="status.php">Status de Tarefas ⮟</a>
            </li>
        </ul>

    </div>

    <h1>EDIÇÃO DE TAREFAS:</h1>

    <div class="notificacoes">
        <select name="NOTIFICAÇÕES" id="select_notificacoes">
            <option value="escolha">Defina Tarefa a ser editada:</option>
            <option value="tarefa1">Primeira tarefa</option>
            <option value="tarefa2">Segunda tarefa</option>
        </select><br>

        <div class="button">
            <button id="botton" type="button">Editar essas tarefa</button><br>
        </div>

      </div>
</body>

</html>
