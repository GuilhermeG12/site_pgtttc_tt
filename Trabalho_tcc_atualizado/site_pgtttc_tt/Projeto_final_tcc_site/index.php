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

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtém os dados do usuário
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT nome FROM usuarios WHERE id = '$user_id'";
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    die("Usuário não encontrado.");
}

// Recuperar as tarefas do usuário ordenadas por prioridade
$sql_tarefas = "SELECT * FROM tarefas_completas WHERE user_id = '$user_id' 
                ORDER BY FIELD(prioridade, 'Alta', 'Média', 'Baixa')";
$result_tarefas = $conn->query($sql_tarefas);
$tarefas = [];
if ($result_tarefas->num_rows > 0) {
    while ($row = $result_tarefas->fetch_assoc()) {
        $tarefas[] = $row;
    }
}

// Se houver tarefas, busque os status delas (apenas as não concluídas)
$statusData = [];
if (!empty($tarefas)) {
    $tarefaIds = array_column($tarefas, 'id');
    $ids = implode(',', $tarefaIds);
    $sql_status = "SELECT tarefa_id, status, concluida FROM tarefas_status 
                   WHERE tarefa_id IN ($ids) AND concluida = 0";
    $result_status = $conn->query($sql_status);
    if ($result_status && $result_status->num_rows > 0) {
        while ($row = $result_status->fetch_assoc()) {
            $statusData[$row['tarefa_id']] = $row;
        }
    }
}
$conn->close();

// Função para remover acentos e converter para minúsculas
function normalizePriority($string) {
    return strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $string));
}

// Mapeamento de prioridades para comparação e exibição
$priorityMapping = [
    'alta'  => 'Alta',
    'media' => 'Média',
    'baixa' => 'Baixa'
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>CHECKLIST</title>
  <style>
    /* Estilo para agrupar por prioridade e categoria */
    .priority-container {
      margin-bottom: 30px;
    }
    .priority-title {
      font-size: 1.8em;
      margin-bottom: 15px;
      padding: 10px;
      color: white;
      text-align: center;
      border-radius: 5px;
    }
    /* Cores para cada prioridade */
    .prioridade-alta { background-color: #e74c3c; }   /* Vermelho */
    .prioridade-media { background-color: #e67e22; }  /* Laranja */
    .prioridade-baixa { background-color: #3498db; }  /* Azul */

    /* Container para exibição horizontal das tarefas */
    .tasks-row {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
    }

    /* Agrupamento por categoria */
    .category-group {
      margin-bottom: 25px;
      width: 100%;
    }
    .category-title {
      font-size: 1.3em;
      font-weight: bold;
      margin-bottom: 8px;
      border-bottom: 2px solid #ccc;
      padding-bottom: 3px;
    }

    /* Estilo da caixa de cada tarefa */
    .box {
      border: 1px solid #ccc;
      padding: 12px;
      border-radius: 8px;
      width: 280px;
      background: #f9f9f9;
      box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
      position: relative;
    }
    .box h2 {
      font-size: 1.2em;
      margin-top: 8px;
      cursor: pointer;
    }
    
    /* Botão "Concluir Tarefa" inicialmente oculto */
    .btn-concluir {
      display: none;
      margin-top: 10px;
      padding: 8px 12px;
      background-color: #2ecc71;
      border: none;
      color: white;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1em;
    }
    
    /* Footer */
    footer {
      margin-top: 40px;
      padding: 20px;
      text-align: center;
      background: #f1f1f1;
      font-size: 0.9em;
      color: #555;
    }
    
    /* Cursor pointer para nomes de subtarefas */
    .checkbox-sub span {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <header>
    <div class="links">
      <ul class="menu">
        <li>
          <a href="cadastro.html"><img src="./img/logo_marca.png" id="logo" alt="Logo img"></a>
        </li>
        <li><a href="index.php">Tarefas Diarias ⮞</a></li>
        <li><a href="cadastrodetaref.php">Cadastro de Tarefas ⮟</a></li>
        <li><a href="notificacoes.php">Notificações ⮟</a></li>
        <li><a href="editartarefas.php">Editar ⮟</a></li>
        <li><a href="">Status de Tarefas ⮟</a></li>
      </ul>
    </div>
  </header>

  <main>
    <h1>Bem-vindo, <?php echo htmlspecialchars($user['nome']); ?>!</h1>
    <h2>Tarefas em andamento:</h2>

    <?php if (empty($tarefas)): ?>
      <p>Nenhuma tarefa encontrada.</p>
    <?php else: ?>
      <?php 
        // Itera pelo mapeamento das prioridades
        foreach ($priorityMapping as $normKey => $displayTitle):
          // Filtra as tarefas usando normalizePriority e trim, ignorando as já concluídas
          $tarefas_prioridade = array_filter($tarefas, function($tarefa) use ($normKey, $statusData) {
            return normalizePriority(trim($tarefa['prioridade'])) === $normKey 
                   && !(isset($statusData[$tarefa['id']]) && $statusData[$tarefa['id']]['concluida'] == 1);
          });
          if (!empty($tarefas_prioridade)):
      ?>
        <div class="priority-container">
          <div class="priority-title <?php echo 'prioridade-' . $normKey; ?>">
            <?php echo "Prioridade " . $displayTitle; ?>
          </div>
          <?php
            // Agrupa as tarefas por categoria dentro da prioridade
            $categorias = [];
            foreach ($tarefas_prioridade as $tarefa) {
              $categorias[$tarefa['categoria']][] = $tarefa;
            }
            foreach ($categorias as $categoria => $tarefas_categoria):
          ?>
            <div class="category-group">
              <div class="category-title"><?php echo htmlspecialchars($categoria); ?></div>
              <div class="tasks-row">
                <?php foreach ($tarefas_categoria as $tarefa): 
                  // Define se a tarefa principal está marcada
                  $isChecked = (isset($statusData[$tarefa['id']]) && $statusData[$tarefa['id']]['status'] == 1) ? 'checked' : '';
                ?>
                  <div class="box" id="box-<?php echo $tarefa['id']; ?>">
                    <div class="tarefa">
                      <div class="checkbox-taf1">
                        <p>Prioridade: <strong><?php echo htmlspecialchars($tarefa['prioridade']); ?></strong></p>
                        <p>Categoria: <?php echo htmlspecialchars($tarefa['categoria']); ?></p>
                        <p>Horário de início: <?php echo htmlspecialchars($tarefa['horario_inicio']); ?></p>
                        <p>Horário de término: <?php echo htmlspecialchars($tarefa['horario_termino']); ?></p>
                        <label for="principal-<?php echo $tarefa['id']; ?>"></label>
                        <div class="checkbox-container">
                          <input type="checkbox" id="principal-<?php echo $tarefa['id']; ?>" class="tarefa-principal" <?php echo $isChecked; ?> />
                          <!-- O título da tarefa tem cursor pointer para marcar/desmarcar -->
                          <h2><?php echo htmlspecialchars($tarefa['descricao']); ?></h2>
                        </div>
                      </div>
                      <!-- Exibir Subtarefas -->
                      <?php 
                        $subtarefas = explode(',', $tarefa['sub_atividade']);
                        foreach ($subtarefas as $index => $subtarefa): 
                          $subtarefa_id = "sub-{$tarefa['id']}-{$index}";
                      ?>
                        <div class="checkbox-sub">
                          <input type="checkbox" id="<?php echo $subtarefa_id; ?>" class="subtarefa" data-tarefa-id="<?php echo $tarefa['id']; ?>" />
                          <!-- O nome da subtarefa também tem cursor pointer -->
                          <span><?php echo htmlspecialchars(trim($subtarefa)); ?></span>
                        </div>
                      <?php endforeach; ?>
                      <!-- Botão de Concluir Tarefa (oculto, aparece se todas as caixas estiverem marcadas) -->
                      <button class="btn-concluir" data-tarefa-id="<?php echo $tarefa['id']; ?>">Concluir Tarefa</button>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div> <!-- tasks-row -->
            </div> <!-- category-group -->
          <?php endforeach; ?>
        </div> <!-- priority-container -->
      <?php 
          endif; 
        endforeach; 
      ?>
    <?php endif; ?>
  </main>

  <!-- Footer -->
  <footer>
    © 2025 Checklist Tarefas e Rotinas. Todos os direitos reservados.
  </footer>

  <script>
    // Função para atualizar o status da tarefa via AJAX (status da checkbox principal)
    function updateTaskStatus(taskId, status) {
      fetch('update_task.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'tarefa_id=' + encodeURIComponent(taskId) + '&status=' + (status ? '1' : '0')
      })
      .then(response => response.text())
      .then(data => {
        console.log('Status atualizado:', data);
      })
      .catch(error => {
        console.error('Erro:', error);
      });
    }

    // Função para marcar a tarefa como concluída via AJAX
    function markTaskCompleted(taskId) {
      fetch('update_task_completed.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'tarefa_id=' + encodeURIComponent(taskId)
      })
      .then(response => response.text())
      .then(data => {
        console.log('Tarefa concluída:', data);
        // Remove o box da tarefa concluída do index
        document.getElementById('box-' + taskId).remove();
      })
      .catch(error => {
        console.error('Erro:', error);
      });
    }

    // Função para verificar se todas as checkboxes (principal e subtarefas) estão marcadas
    function checkAndToggleConcludeButton(taskId) {
      const box = document.getElementById('box-' + taskId);
      const principalCheckbox = box.querySelector('.tarefa-principal');
      const subtasks = box.querySelectorAll('.subtarefa');
      let allChecked = principalCheckbox.checked;
      subtasks.forEach(sub => {
        if (!sub.checked) { allChecked = false; }
      });
      const btnConcluir = box.querySelector('.btn-concluir');
      if (allChecked) {
        btnConcluir.style.display = 'block';
      } else {
        btnConcluir.style.display = 'none';
      }
    }

    document.addEventListener("DOMContentLoaded", function () {
      // Para cada tarefa principal, vincula os eventos:
      document.querySelectorAll(".tarefa-principal").forEach(principalCheckbox => {
        const tarefaId = principalCheckbox.id.split("-")[1];
        // Seleciona o título (h2) adjacente à checkbox principal
        const descricaoTarefa = document.querySelector(`#principal-${tarefaId} + h2`);

        // Quando o título for clicado, alterna a checkbox principal
        descricaoTarefa.addEventListener("click", function() {
          principalCheckbox.checked = !principalCheckbox.checked;
          // Atualiza todas as subtarefas para seguir o estado da principal
          const subtarefas = document.querySelectorAll(`.subtarefa[data-tarefa-id='${tarefaId}']`);
          subtarefas.forEach(sub => sub.checked = principalCheckbox.checked);
          updateTaskStatus(tarefaId, principalCheckbox.checked);
          checkAndToggleConcludeButton(tarefaId);
        });

        // Quando a checkbox principal mudar, marca/desmarca as subtarefas
        principalCheckbox.addEventListener("change", function(){
          const subtarefas = document.querySelectorAll(`.subtarefa[data-tarefa-id='${tarefaId}']`);
          subtarefas.forEach(sub => sub.checked = principalCheckbox.checked);
          updateTaskStatus(tarefaId, principalCheckbox.checked);
          checkAndToggleConcludeButton(tarefaId);
        });
      });

      // Para cada subtarefa, vincula os eventos para que o clique no nome (span) também altere o checkbox
      document.querySelectorAll(".subtarefa").forEach(subCheckbox => {
        const descricaoSubtarefa = subCheckbox.nextElementSibling;
        descricaoSubtarefa.addEventListener("click", function () {
          subCheckbox.checked = !subCheckbox.checked;
          // Se todas as subtarefas estiverem marcadas, marca a principal
          const tarefaId = subCheckbox.getAttribute("data-tarefa-id");
          const box = document.getElementById("box-" + tarefaId);
          const principalCheckbox = box.querySelector(".tarefa-principal");
          const subtarefas = box.querySelectorAll(".subtarefa");
          let allChecked = principalCheckbox.checked;
          subtarefas.forEach(sub => {
            if (!sub.checked) { allChecked = false; }
          });
          principalCheckbox.checked = allChecked;
          updateTaskStatus(tarefaId, principalCheckbox.checked);
          checkAndToggleConcludeButton(tarefaId);
        });
        subCheckbox.addEventListener("change", function () {
          const tarefaId = subCheckbox.getAttribute("data-tarefa-id");
          const box = document.getElementById("box-" + tarefaId);
          const principalCheckbox = box.querySelector(".tarefa-principal");
          const subtarefas = box.querySelectorAll(".subtarefa");
          let allChecked = principalCheckbox.checked;
          subtarefas.forEach(sub => {
            if (!sub.checked) { allChecked = false; }
          });
          principalCheckbox.checked = allChecked;
          updateTaskStatus(tarefaId, principalCheckbox.checked);
          checkAndToggleConcludeButton(tarefaId);
        });
      });

      // Vincula o clique do botão "Concluir Tarefa"
      document.querySelectorAll(".btn-concluir").forEach(btn => {
        btn.addEventListener("click", function() {
          const tarefaId = btn.getAttribute("data-tarefa-id");
          markTaskCompleted(tarefaId);
        });
      });
    });
  </script>
</body>
</html>
