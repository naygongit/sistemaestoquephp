<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login - Alxomarifado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<div class="login-container">
    <form action="processar_login.php" method="POST">
    <h1>Login</h1>
        <label for="nome">Usuário:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
    
</div>
</body>
</html>
