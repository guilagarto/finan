<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Transação - Gestão Financeira</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        header { background-color: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #ffc107; text-decoration: none; font-weight: bold; }
        .container { max-width: 500px; margin: 40px auto; padding: 0 20px; }
        .form-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-row { display: flex; gap: 15px; margin-bottom: 20px; }
        .form-row .form-group { flex: 1; margin-bottom: 0; }
        .form-group label { display: block; margin-bottom: 5px; color: #495057; font-weight: 500; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .btn-submit { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background-color: #218838; }
        .back-link { display: inline-block; margin-bottom: 15px; color: #007bff; text-decoration: none; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<header>
    <h2>Finanças Pessoais v1.0</h2>
    <a href="/financas-app/dashboard">Voltar ao Painel</a>
</header>

<div class="container">
    <a href="/financas-app/dashboard" class="back-link">← Cancelar e Voltar</a>
    
    <div class="form-card">
        <h3 style="margin-top:0; margin-bottom: 20px; color: #333;">Cadastrar Lançamento</h3>
        
        <form action="/financas-app/dashboard/transacao/salvar" method="POST">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" id="descricao" name="descricao" required placeholder="Ex: Mercado, Salário, Internet...">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="valor_total">Valor Total (R$)</label>
                    <input type="number" step="0.01" id="valor_total" name="valor_total" required placeholder="0,00" oninput="calcularParcela()">
                </div>

                <div class="form-group">
                    <label for="total_parcelas">Qtd. Parcelas</label>
                    <!-- Padrão 1 se for à vista -->
                    <input type="number" id="total_parcelas" name="total_parcelas" required min="1" value="1" oninput="calcularParcela()">
                </div>
            </div>

            <div class="form-group">
                <label for="valor_parcela">Valor da Parcela (R$)</label>
                <input type="number" step="0.01" id="valor_parcela" name="valor_parcela" required placeholder="0,00">
            </div>

            <div class="form-row">
                <!-- Encontre e substitua o bloco do select por este no seu arquivo nova_transacao.php -->
<!-- Substitua o bloco do select por este no seu arquivo nova_transacao.php -->
<!-- Encontre e mude o select do seu arquivo nova_transacao.php para ficar exatamente assim: -->
<div class="form-group">
    <label for="tipo">Tipo de Movimentação</label>
    <select id="tipo" name="tipo" required>
        <option value="receita">Entrada (Ganho / Receita)</option>
        <option value="despesa" selected>Saída (Gasto / Despesa)</option>
    </select>
</div>




                <div class="form-group">
                    <label for="data_transacao">Data</label>
                    <input type="date" id="data_transacao" name="data_transacao" required value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
                <!-- Cole este bloco dentro do form, antes do botão submit -->
<div class="form-group">
    <label for="status">Status do Pagamento</label>
    <select id="status" name="status" required>
        <option value="pago" selected>Pago / Recebido</option>
        <option value="pendente">Pendente / Em aberto</option>
    </select>
</div>

            <button type="submit" class="btn-submit">Salvar Registro</button>
        </form>
    </div>
</div>

<script>
    /**
     * Função simples para ajudar o usuário calculando o valor da parcela automaticamente
     */
    function calcularParcela() {
        const total = parseFloat(document.getElementById('valor_total').value) || 0;
        const parcelas = parseInt(document.getElementById('total_parcelas').value) || 1;
        
        if (total > 0 && parcelas > 0) {
            const resultado = total / parcelas;
            document.getElementById('valor_parcela').value = resultado.toFixed(2);
        }
    }
</script>

</body>
</html>
