<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metas Financeiras</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .meta-card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .btn { padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px; display: inline-block; border: none; cursor: pointer; }
        .btn-blue { background: #007bff; color: white; }
        .btn-green { background: #28a745; color: white; }
    </style>
</head>
<body>
<div class="container">
    <a href="<?= url('/dashboard') ?>" style="color: #6c757d; text-decoration: none; font-weight: bold;">← Voltar ao Painel</a>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0;">
        <h2>🎯 Objetivos e Metas Financeiras</h2>
        <a href="<?= url('/dashboard/metas/nova') ?>" class="btn btn-green">+ Nova Meta</a>
    </div>

    <?php if (empty($metas)): ?>
        <p>Nenhuma meta cadastrada.</p>
    <?php else: ?>
        <?php foreach ($metas as $meta): 
            $pct = $meta['valor_objetivo'] > 0 ? ($meta['valor_poupado'] / $meta['valor_objetivo']) * 100 : 0;
            $pct = min($pct, 100);
        ?>
            <div class="meta-card">
                <h3><?= htmlspecialchars($meta['titulo']) ?></h3>
                <p>Objetivo: R$ <?= number_format($meta['valor_objetivo'], 2, ',', '.') ?> | Guardado: R$ <?= number_format($meta['valor_poupado'], 2, ',', '.') ?></p>
                
                <div style="background: #e9ecef; width: 100%; height: 16px; border-radius: 8px; overflow: hidden; margin-bottom: 15px;">
                    <div style="background: #28a745; width: <?= $pct ?>%; height: 100%;"></div>
                </div>

                <!-- Formulário rápido para poupar e somar dinheiro na meta -->
                <form action="<?= url('/dashboard/metas/atualizar-poupanca') ?>" method="POST" style="display: flex; gap: 10px; align-items: center;">
                    <input type="hidden" name="id" value="<?= $meta['id'] ?>">
                    <input type="number" step="0.01" name="valor_adicional" required placeholder="Adicionar R$" style="padding: 6px; border: 1px solid #ddd; border-radius: 4px; width: 130px;">
                    <button type="submit" class="btn btn-blue">Poupar Dinheiro</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
