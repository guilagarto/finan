<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Mês - <?= isset($nomeMes) ? $nomeMes : 'Mês'; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        header { background-color: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #ffc107; text-decoration: none; font-weight: bold; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
        .page-header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px; }
        
        /* Layout dos Cards de Saldo */
        .cards-resumo { display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); flex: 1; min-width: 220px; border-left: 5px solid #ced4da; }
        .card-entradas { border-left-color: #28a745; }
        .card-saidas { border-left-color: #dc3545; }
        .card-saldo-positivo { border-left-color: #007bff; }
        .card-saldo-negativo { border-left-color: #dc3545; }
        .card h4 { margin: 0 0 5px 0; color: #6c757d; font-size: 14px; text-transform: uppercase; }
        .card p { margin: 0; font-size: 24px; font-weight: bold; }
        
        /* Filtros e Tabela */
        .filter-container { display: flex; gap: 10px; margin-bottom: 15px; }
        .filter-btn { padding: 8px 16px; border: 1px solid #ced4da; background-color: white; border-radius: 20px; cursor: pointer; font-size: 14px; font-weight: 500; }
        .filter-btn.active { background-color: #343a40; color: white; border-color: #343a40; }
        
        .table-card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; }
        th { background-color: #f1f3f5; color: #495057; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-entrada { background-color: #d4edda; color: #155724; }
        .badge-saida { background-color: #f8d7da; color: #721c24; }
        
        .status-dot { padding: 4px 10px; border-radius: 12px; font-size: 13px; font-weight: 500; display: inline-block; text-transform: capitalize; }
        .status-pago { background-color: #d4edda; color: #155724; }
        .status-pendente { background-color: #fff3cd; color: #856404; }
        .status-atrasado { background-color: #f8d7da; color: #721c24; font-weight: bold; }

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
    <a href="/financas-app/dashboard" class="back-link">← Voltar para o Painel</a>

    <div class="page-header">
        <h2 style="margin:0;">Movimentações de <?= isset($nomeMes) ? $nomeMes : 'Mês'; ?></h2>
        <p style="color: #6c757d; margin: 5px 0 0 0;">Controle de lançamentos à vista e parcelados vigentes.</p>
    </div>

    <?php
        // Lógica em PHP para somar os totais do mês atual da tabela
        $totalEntradas = 0;
        $totalSaidas = 0;
        foreach ($transacoes as $t) {
            if ($t['tipo'] === 'receita') {
                $totalEntradas += $t['valor_exibicao'];
            } else {
                $totalSaidas += $t['valor_exibicao'];
            }
        }
        $saldoLiquido = $totalEntradas - $totalSaidas;
    ?>

    <!-- Cards Superiores com o Resumo Real -->
    <div class="cards-resumo">
        <div class="card card-entradas">
            <h4>Total Entradas</h4>
            <p class="text-entrada">R$ <?= number_format($totalEntradas, 2, ',', '.'); ?></p>
        </div>
        <div class="card card-saidas">
            <h4>Total Saídas</h4>
            <p class="text-saida">R$ <?= number_format($totalSaidas, 2, ',', '.'); ?></p>
        </div>
        <div class="card <?= $saldoLiquido >= 0 ? 'card-saldo-positivo' : 'card-saldo-negativo'; ?>">
            <h4>Saldo Líquido</h4>
            <p style="color: <?= $saldoLiquido >= 0 ? '#007bff' : '#dc3545'; ?>;">
                R$ <?= number_format($saldoLiquido, 2, ',', '.'); ?>
            </p>
        </div>
    </div>

    <!-- Botões de Filtro JavaScript -->
    <div class="filter-container">
        <button class="filter-btn active" onclick="filtrarTransacoes('todos', this)">Todos</button>
        <button class="filter-btn" onclick="filtrarTransacoes('receita', this)">Entradas</button>
        <button class="filter-btn" onclick="filtrarTransacoes('despesa', this)">Saídas</button>
    </div>

    <!-- Tabela de Dados -->
    <div class="table-card">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead>
                <tr>
                    <th style="width: 15%;">Data</th>
                    <th style="width: 25%;">Descrição</th>
                    <th style="width: 15%;">Parcelamento</th>
                    <th style="width: 10%;">Tipo</th>
                    <th style="width: 12%;">Status</th>
                    <th style="text-align: right; width: 13%;">Valor da Parcela</th>
                    <th style="text-align: center; width: 10%;">Ações</th>
                </tr>
            </thead>
            <tbody id="lista-transacoes">
                <?php if (empty($transacoes)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #6c757d; padding: 30px;">Nenhuma transação cadastrada ou ativa neste mês.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transacoes as $item): ?>
                        <tr class="transacao-item" data-tipo="<?= htmlspecialchars($item['tipo']); ?>">
                            <td style="vertical-align: middle;"><?= date('d/m/Y', strtotime($item['data_transacao'])); ?></td>
                            <td style="vertical-align: middle; word-wrap: break-word;"><?= htmlspecialchars($item['descricao']); ?></td>
                            <td style="vertical-align: middle; color: #6c757d; font-size: 14px;"><?= htmlspecialchars($item['parcela_texto']); ?></td>
                            <td style="vertical-align: middle;">
                                <span class="badge <?= $item['tipo'] === 'receita' ? 'badge-entrada' : 'badge-saida'; ?>">
                                    <?= $item['tipo'] === 'receita' ? 'Entrada' : 'Saída'; ?>
                                </span>
                            </td>
                            <td style="vertical-align: middle;">
                                <?php 
                                    $statusClass = 'status-pendente';
                                    if ($item['status'] === 'pago') $statusClass = 'status-pago';
                                    if ($item['status'] === 'atrasado') $statusClass = 'status-atrasado';
                                ?>
                                <span class="status-dot <?= $statusClass; ?>">
                                    <?= htmlspecialchars($item['status']); ?>
                                </span>
                            </td>
                            <td style="text-align: right; vertical-align: middle;" class="<?= $item['tipo'] === 'receita' ? 'text-entrada' : 'text-saida'; ?>">
                                <?= $item['tipo'] === 'receita' ? '+' : '-'; ?> R$ <?= number_format($item['valor_exibicao'], 2, ',', '.'); ?>
                            </td>
                            
                            <!-- CÉLULA ALINHADA E PROTEGIDA CONTRA QUEBRAS DE LINHA -->
                            <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                <a href="/financas-app/dashboard/transacao/excluir?id=<?= $item['id']; ?>&mes=<?= isset($_GET['id']) ? $_GET['id'] : date('m'); ?>" 
                                   onclick="return confirm('Atenção: Isso excluirá o lançamento em definitivo. Deseja continuar?')" 
                                   style="color: #dc3545; text-decoration: none; font-weight: bold; font-size: 13px; background: #fdf2f2; padding: 6px 14px; border: 1px solid #fbc4c4; border-radius: 4px; display: inline-block; line-height: 1;">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    /**
     * Filtra as linhas da tabela dinamicamente em tempo real
     */
    function filtrarTransacoes(tipoAlvo, botaoClicado) {
        const botoes = document.querySelectorAll('.filter-btn');
        botoes.forEach(btn => btn.classList.remove('active'));
        botaoClicado.classList.add('active');

        const linhas = document.querySelectorAll('.transacao-item');
        linhas.forEach(linha => {
            const tipoLinha = WebKitCSSMatrix ? linha.getAttribute('data-tipo') : linha.dataset.tipo;
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

