<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Mês - <?= $nomeMes; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        header { background-color: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #ffc107; text-decoration: none; font-weight: bold; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
        .page-header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        
        .filter-container { display: flex; gap: 10px; margin-bottom: 15px; }
        .filter-btn { padding: 8px 16px; border: 1px solid #ced4da; background-color: white; border-radius: 20px; cursor: pointer; font-size: 14px; }
        .filter-btn.active { background-color: #343a40; color: white; border-color: #343a40; }
        
        .table-card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
        th { background-color: #f1f3f5; color: #495057; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-entrada { background-color: #d4edda; color: #155724; }
        .badge-saida { background-color: #f8d7da; color: #721c24; }
        .text-entrada { color: #28a745; font-weight: bold; }
        .text-saida { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h2>Finanças Pessoais v1.0</h2>
    <div>
        <span>Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></strong></span> | 
        <a href="/financas-app/logout">Sair</a>
    </div>
</header>

<div class="container">
    <a href="/financas-app/dashboard" class="back-link">← Voltar para o Balanço Anual</a>

    <div class="page-header">
        <div>
            <h2 style="margin:0;">Movimentações de <?= $nomeMes; ?></h2>
            <p style="color: #6c757d; margin: 5px 0 0 0;">Visualize e filtre seus lançamentos individuais.</p>
        </div>
    </div>

   <!-- Atualize os botões de filtro no seu arquivo mes.php -->
<div class="filter-container">
    <button class="filter-btn active" onclick="filtrarTransacoes('todos', this)">Todos</button>
    <button class="filter-btn" onclick="filtrarTransacoes('receita', this)">Entradas</button>
    <button class="filter-btn" onclick="filtrarTransacoes('despesa', this)">Saídas</button>
</div>



    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th style="text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody id="lista-transacoes">
                <?php if (empty($transacoes)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6c757d; padding: 30px;">Nenhuma transação encontrada para este mês.</td>
                    </tr>
                <?php else: ?>
                    <!-- Altere o trecho do foreach dentro da tabela em app/Views/dashboard/mes.php -->
<?php foreach ($transacoes as $item): ?>
    <tr class="transacao-item" data-tipo="<?= htmlspecialchars($item['tipo']); ?>">
        <td><?= date('d/m/Y', strtotime($item['data_transacao'])); ?></td>
        <td><?= htmlspecialchars($item['descricao']); ?></td>
        <td>
            <!-- Se for receita fica verde (Entrada), se for despesa fica vermelho (Saída) -->
            <span class="badge <?= $item['tipo'] === 'receita' ? 'badge-entrada' : 'badge-saida'; ?>">
                <?= $item['tipo'] === 'receita' ? 'Entrada' : 'Saída'; ?>
            </span>
        </td>
        <td style="text-align: right;" class="<?= $item['tipo'] === 'receita' ? 'text-entrada' : 'text-saida'; ?>">
            <?= $item['tipo'] === 'receita' ? '+' : '-'; ?> R$ <?= number_format($item['valor_total'], 2, ',', '.'); ?>
        </td>
    </tr>
<?php endforeach; ?>

                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function filtrarTransacoes(tipoAlvo, botaoClicado) {
        const botoes = document.querySelectorAll('.filter-btn');
        botoes.forEach(btn => btn.classList.remove('active'));
        botaoClicado.classList.add('active');

        const linhas = document.querySelectorAll('.transacao-item');
        linhas.forEach(linha => {
            const tipoLinha = linha.getAttribute('data-tipo');
            if (tipoAlvo === 'todos' || tipoLinha === tipoAlvo) {
                linha.style.display = '';
            } else {
                linha.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
