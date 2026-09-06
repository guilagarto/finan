<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Gestão Financeira</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .recuperar-container { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 360px; }
        h2 { margin-bottom: 10px; color: #333; text-align: center; }
        p { color: #666; font-size: 14px; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #666; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-recuperar { width: 100%; padding: 10px; background-color: #007bff; border: none; border-radius: 4px; color: white; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>

<div class="recuperar-container">
    <h2>Esqueceu a Senha?</h2>
    <p>Insira o seu e-mail cadastrado. Para este ambiente de teste, o sistema exibirá uma simulação na tela.</p>

    <form action="<?= url('/recuperar-senha/enviar') ?>" method="POST">
        <div class="form-group">
            <label for="email">E-mail Cadastrado</label>
            <input type="email" id="email" name="email" required placeholder="seu-email@exemplo.com">
        </div>

        <button type="submit" class="btn-recuperar">Recuperar Acesso</button>
    </form>

    <div style="margin-top: 20px; text-align: center; font-size: 14px;">
        <a href="<?= url('/login') ?>" style="color: #666; text-decoration: none;">← Voltar para o Login</a>
    </div>
</div>

</body>
</html>
