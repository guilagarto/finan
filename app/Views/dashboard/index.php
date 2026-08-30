<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestão Financeira</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        header { background-color: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #ffc107; text-decoration: none; font-weight: bold; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .welcome-box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .welcome-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .actions-layout { display: flex; flex-wrap: wrap; gap: 20px; }
        .card-menu { background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e3e6f0; flex: 1; min-width: 280px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .filter-section { display: flex; flex-direction: column; gap: 10px; }
        select { padding: 10px; border-radius: 4px; border: 1px solid #ced4da; font-size: 14px; width: 100%; }
        .btn-blue { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; text-align: center; text-decoration: none; }
        .btn-blue:hover { background-color: #0056b3; }
        .btn-green { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; text-align: center; text-decoration: none; display: block; box-sizing: border-box; }
        .btn-green:hover { background-color: #218838; }
        h3, h4 { margin: 0; color: #495057; }
    </style>
</head>
<body>

<header>
    <h2>Finanças Pessoais v1.0</h2>
    <div>
        <span>Olá, <strong><?php echo isset($_SESSION['usuario_nome']) ? htmlspecialchars($_SESSION['usuario_nome']) : 'Usuário'; ?></strong></span> | 
        <a href="/financas-app/logout">Sair</a>
    </div>
</header>

<div class="container">
    
    <div class="welcome-box">
        <div class="welcome-header">
            <div>
                <h3>Painel de Controle Financeiro</h3>
                <p style="color: #6c757d; margin: 5px 0 0 0;">Seja bem-vindo! Gerencie seus fluxos de caixa de forma simplificada.</p>
            </div>
        </div>

        <div class="actions-layout">
            <!-- Bloco 1: Filtro de Consultas por Mês -->
            <div class="card-menu">
                <h4 style="margin-bottom: 15px;">Visualizar Movimentações</h4>
                <form action="/financas-app/dashboard/mes" method="GET" class="filter-section">
                    <select name="id" id="id" required>
                        <option value="01">Janeiro</option>
                        <option value="02">Fevereiro</option>
                        <option value="03">Março</option>
                        <option value="04">Abril</option>
                        <option value="05">Maio</option>
                        <option value="06">Junho</option>
                        <option value="07">Julho</option>
                        <option value="08" selected>Agosto</option>
                        <option value="09">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                    <button type="submit" class="btn-blue">Abrir Mês Selecionado</button>
                </form>
            </div>

            <!-- Bloco 2: Lançamentos Rápidos -->
            <div class="card-menu" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h4 style="margin-bottom: 15px;">Ações Rápidas</h4>
                    <p style="color: #6c757d; font-size: 14px; margin-top: 0;">Adicione novas receitas ou despesas diretamente no seu saldo geral.</p>
                </div>
                <a href="/financas-app/dashboard/transacao/nova" class="btn-green">+ Novo Lançamento</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
