<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Checklist</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>
    <div class="form-container">
        <h1>Login</h1>

        <form action="bancodologin.php" method="POST">
            <div class="input-box">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>

            <div class="input-box">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn">Entrar</button>

            <p>Não tem uma conta? <a href="cadastro.html">Cadastre-se aqui.</a></p>
        </form>
    </div>
</body>

</html>
