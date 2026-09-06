<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Gestão Financeira</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .cadastro-container { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 360px; }
        h2 { margin-bottom: 20px; color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-cadastro { width: 100%; padding: 10px; background-color: #28a745; border: none; border-radius: 4px; color: white; font-size: 16px; cursor: pointer; }
        .btn-cadastro:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="cadastro-container">
    <h2>Criar Nova Conta</h2>

    <form action="<?= url('/cadastro/salvar') ?>" method="POST">
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required placeholder="Seu nome">
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required placeholder="exemplo@email.com">
        </div>

        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required placeholder="Crie uma senha segura">
        </div>

        <button type="submit" class="btn-cadastro">Cadastrar</button>
    </form>

    <div style="margin-top: 20px; text-align: center; font-size: 14px;">
        <a href="<?= url('/login') ?>" style="color: #007bff; text-decoration: none;">← Voltar para o Login</a>
    </div>
</div>

</body>
</html>
