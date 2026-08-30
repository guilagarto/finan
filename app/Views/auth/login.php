<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestão Financeira</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 360px; }
        h2 { margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 10px; background-color: #28a745; border: none; border-radius: 4px; color: white; font-size: 16px; cursor: pointer; }
        .btn-login:hover { background-color: #218838; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-size: 14px; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Acessar o Sistema</h2>

    <!-- Exibe mensagens de erro se houver algo gravado na sessão -->
    <?php if (isset($_SESSION['erro_login'])): ?>
        <div class="alert-error">
            <?= $_SESSION['erro_login']; ?>
            <?php unset($_SESSION['erro_login']); // Limpa o erro para não repetir no próximo F5 ?>
        </div>
    <?php endif; ?>

    <!-- O action aponta para a rota POST '/login' que criamos no Router -->
    <form action="/financas-app/login" method="POST">

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required placeholder="exemplo@email.com">
        </div>

        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required placeholder="Sua senha">
        </div>

        <button type="submit" class="btn-login">Entrar</button>
    </form>
</div>

</body>
</html>
