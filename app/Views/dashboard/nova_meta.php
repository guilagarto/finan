<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Meta</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; display: flex; justify-content: center; }
        .form-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        .form-group label { margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input, .form-group select { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 10px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
<div class="form-card">
    <h2>🎯 Criar Nova Meta</h2>
    <form action="<?= url('/dashboard/metas/salvar') ?>" method="POST">
        <div class="form-group">
            <label for="titulo">Nome do Objetivo</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Ex: Comprar Moto, Viagem">
        </div>
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
                <option value="poupar">Poupar Dinheiro</option>
                <option value="comprar">Comprar Bem / Produto</option>
                <option value="investir">Investimento</option>
                <option value="viagem">Viagem / Férias</option>
                <option value="outro">Outro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="valor_objetivo">Valor Total Alvo (R$)</label>
            <input type="number" step="0.01" id="valor_objetivo" name="valor_objetivo" required placeholder="0,00">
        </div>
        <div class="form-group">
            <label for="valor_poupado">Valor Inicial Já Guardado (R$)</label>
            <input type="number" step="0.01" id="valor_poupado" name="valor_poupado" placeholder="0,00">
        </div>
        <div class="form-group">
            <label for="prazo">Data Alvo Limite (Opcional)</label>
            <input type="date" id="prazo" name="prazo">
        </div>
        <button type="submit" class="btn-submit">Salvar Meta</button>
    </form>
    <br>
    <a href="<?= url('/dashboard/metas') ?>" style="color: #007bff; text-decoration: none; font-size: 14px;">← Cancelar</a>
</div>
</body>
</html>
