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

        /* --- Estilos Gerais da Tabela --- */
.table-card {
    width: 100%;
    margin-bottom: 1rem;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.responsive-table {
    width: 100%;
    border-collapse: collapse;
}

.responsive-table th, 
.responsive-table td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

/* Alinhamentos específicos para Desktop */
.text-right { text-align: right !important; }
.text-center { text-align: center !important; }

/* --- Responsividade (Mobile) --- */
@media screen and (max-width: 768px) {
    /* Esconde o cabeçalho original da tabela */
    .responsive-table thead {
        display: none;
    }
    
    /* Transforma a tabela, corpo e linhas em blocos cheios */
    .responsive-table, 
    .responsive-table tbody, 
    .responsive-table tr {
        display: block;
        width: 100%;
    }
    
    /* Transforma cada linha em um "card" individual */
    .responsive-table tr.transacao-item {
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px;
        background: #fff;
    }
    
    /* Transforma cada célula em uma linha de dados com rótulo */
    .responsive-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: right !important; /* Força o valor do dado para a direita */
        padding: 8px 5px;
        border-bottom: 1px dotted #eee;
    }
    
    /* Remove a borda pontilhada do último item do card */
    .responsive-table td:last-child {
        border-bottom: none;
        justify-content: center; /* Centraliza o botão de excluir no mobile */
        padding-top: 12px;
    }
    
    /* Injeta o nome da coluna antes do valor usando o atributo data-label */
    .responsive-table td::before {
        content: attr(data-label);
        font-weight: bold;
        text-align: left;
        color: #495057;
        padding-right: 10px;
    }
}
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
    <a href="<?= url('/dashboard') ?>">← Voltar para o Painel</a>

    <div class="page-header">
        <h2 style="margin:0;">Movimentações de <?= isset($nomeMes) ? $nomeMes : 'Mês'; ?></h2>
        <p style="color: #6c757d; margin: 5px 0 0 0;">Controle de lançamentos à vista e parcelados vigentes.</p>
    </div>

    <?php
        // Lógica em PHP para somar os totais do mês atual da tabela e verificar pendências
        $totalEntradas = 0;
        $totalSaidas = 0;
        $temPendente = false;

        foreach ($transacoes as $t) {
            if ($t['tipo'] === 'receita') {
                $totalEntradas += $t['valor_exibicao'];
            } else {
                $totalSaidas += $t['valor_exibicao'];
            }

            // Verifica se existe alguma despesa ou receita com status 'pendente'
            if (strtolower($t['status']) === 'pendente') {
                $temPendente = true;
            }
        }
        $saldoLiquido = $totalEntradas - $totalSaidas;
    ?>

    <!-- ========================================== -->
    <!-- CARD DE ALERTA DINÂMICO E INTELIGENTE      -->
    <!-- ========================================== -->
    <?php if ($temPendente): ?>
        <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; border-radius: 6px; margin: 20px 0; font-weight: bold; font-size: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            ⚠️ Atenção: Você ainda possui ativos e movimentações em aberto para este mês!
        </div>
    <?php else: ?>
        <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 6px; margin: 20px 0; font-weight: bold; font-size: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            🎉 Parabéns! Tudo foi quitado e você está rigorosamente em dia neste mês!
        </div>
    <?php endif; ?>
    <!-- ========================================== -->

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
    <table class="responsive-table"> 
        <thead> 
            <tr> 
                <th>Data</th> 
                <th>Descrição</th> 
                <th>Parcelamento</th> 
                <th>Tipo</th> 
                <th>Status</th> 
                <th class="text-right">Valor da Parcela</th> 
                <th class="text-center">Ações</th> 
            </tr> 
        </thead> 
        <tbody id="lista-transacoes"> 
            <?php if (empty($transacoes)): ?> 
                <tr> 
                    <td colspan="7" style="text-align: center; color: #6c757d; padding: 30px;">Nenhuma transação cadastrada ou activa neste mês.</td> 
                </tr> 
            <?php else: ?> 
                <?php foreach ($transacoes as $item): ?> 
                    <tr class="transacao-item" data-tipo="<?= htmlspecialchars($item['tipo']); ?>"> 
                        
                        <td data-label="Data" style="vertical-align: middle;"><?= date('d/m/Y', strtotime($item['data_transacao'])); ?></td> 
                        
                        <td data-label="Descrição" style="vertical-align: middle; word-wrap: break-word; max-width: 250px;"><?= htmlspecialchars($item['descricao']); ?></td> 
                        
                        <td data-label="Parcelamento" style="vertical-align: middle; color: #6c757d; font-size: 14px;"><?= htmlspecialchars($item['parcela_texto']); ?></td> 
                        
                        <td data-label="Tipo" style="vertical-align: middle;"> 
                            <span class="badge <?= $item['tipo'] === 'receita' ? 'badge-entrada' : 'badge-saida'; ?>"> 
                                <?= $item['tipo'] === 'receita' ? 'Entrada' : 'Saída'; ?> 
                            </span> 
                        </td> 
                        
                        <td data-label="Status" style="vertical-align: middle;"> 
                            <?php 
                            $statusClass = 'status-pendente'; 
                            if (strtolower($item['status']) === 'pago') $statusClass = 'status-pago'; 
                            if (strtolower($item['status']) === 'atrasado') $statusClass = 'status-atrasado'; 
                            ?> 
                            <span class="status-dot <?= $statusClass; ?>"> 
                                <?= htmlspecialchars($item['status']); ?> 
                            </span> 
                        </td> 
                        
                        <td data-label="Valor" style="vertical-align: middle;" class="<?= $item['tipo'] === 'receita' ? 'text-entrada text-right' : 'text-saida text-right'; ?>"> 
                            <?= $item['tipo'] === 'receita' ? '+' : '-'; ?> R$ <?= number_format($item['valor_exibicao'], 2, ',', '.'); ?> 
                        </td> 
                        
                        <td style="vertical-align: middle; white-space: nowrap; text-align: center;"> 
                            <!-- BOTÃO DE DAR BAIXA (Apenas se o status for diferente de 'pago') -->
                            <?php if (strtolower($item['status']) !== 'pago'): ?>
                                <a href="<?= url('/transacao/pagar?id=' . $item['id'] . '&mes_id=' . (isset($_GET['id']) ? $_GET['id'] : date('m'))); ?>" style="color: #ffffff; text-decoration: none; font-weight: bold; font-size: 13px; background: #007bff; padding: 6px 14px; border: 1px solid #0062cc; border-radius: 4px; display: inline-block; line-height: 1; margin-right: 5px;"> 
                                    Pagar 
                                </a> 
                            <?php endif; ?>

                            <!-- BOTÃO EXCLUIR DINÂMICO (Previnido contra Erro 404) -->
                            <a href="<?= url('/dashboard/transacao/excluir?id=' . $item['id'] . '&mes=' . (isset($_GET['id']) ? $_GET['id'] : date('m'))); ?>" onclick="return confirm('Atenção: Isso excluirá o lançamento em definitivo. Deseja continuar?')" style="color: #dc3545; text-decoration: none; font-weight: bold; font-size: 13px; background: #fdf2f2; padding: 6px 14px; border: 1px solid #fbc4c4; border-radius: 4px; display: inline-block; line-height: 1;"> 
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

