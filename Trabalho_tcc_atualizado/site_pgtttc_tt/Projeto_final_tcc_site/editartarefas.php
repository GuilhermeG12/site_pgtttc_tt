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
                <a href="cadastro.html" id="cad"><img src="./img/logo_marca.png" id="logo" alt="Logo img"></a>
            </li>

            <li>
                <a href="index.html">Tarefas Diarias ⮟</a>
            </li>

            <li>
                <a href="cadastrodetaref.html">Cadastro de Tarefas ⮟</a>
            </li>

            <li>
                <a href="notificacoes.html">Notificações ⮟</a>
            </li>
            <li>
                <a href="editartarefas.html"> Editar Tarefas ⮞</a>
            </li>
            <li>
                <a href="status.html">Status de Tarefas ⮟</a>
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

        <div class="box">
            <div class="tarefa">
                <div class="checkbox-taf1">
                  <p>Prioridade : XXXXX</p>
                  <p>Categoria : XXXXX</p>
                  <label for="opcao1"></label>
                  <div class="checkbox-container">
                    <input type="checkbox" id="opcao1" name="opcao1" value="1" />
                    <h2>Primeira tarefa: XXXXXXXXX</h2>
                  </div>
                </div>
                <div class="checkbox-sub">
                  <input type="checkbox" id="opcao2" name="opcao2" value="2" />
                  <span>Tarefa 1.1</span>
                </div>
                <div class="checkbox-sub">
                  <input type="checkbox" id="opcao2" name="opcao2" value="2" />
                  <span>Tarefa 1.2</span>
                </div>
                <div class="checkbox-sub">
                  <input type="checkbox" id="opcao2" name="opcao2" value="2" />
                  <span>Tarefa 1.3</span>
                </div>
              </div>
              <div class="tarefa">
                <div class="espaco">
                  <div class="checkbox-taf2">
                    <p>Prioridade : XXXXX</p>
                    <p>Categoria : XXXXX</p>
                    <div class="checkbox-container">
                      <input type="checkbox" id="opcao3" name="opcao3" value="3" />
                      <h2>Segunda tarefa: XXXXXXXXX</h2>
                    </div>
                  </div>
                </div>
                <div class="checkbox-sub">
                  <input type="checkbox" id="opcao1" name="opcao1" value="1" />
                  <span>Tarefa 2.1</span>
                </div>
                <div class="checkbox-sub">
                  <input type="checkbox" id="opcao2" name="opcao2" value="2" />
                  <span>Tarefa 2.2</span>
                </div>
                <div class="checkbox-sub">
                  <input type="checkbox" id="opcao3" name="opcao3" value="3" />
                  <span>Tarefa 2.3</span>
                </div>
        
              </div>
          
            </div>
        </div>
    </div>
</body>

</html>
