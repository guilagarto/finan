<?php

namespace App\Controllers;

use App\Models\Transacao;
use Exception;

class DashboardController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Bloqueio de segurança contra acessos deslogados
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /financas-app/login');
            exit;
        }
    }

    /**
     * Carrega a página principal leve
     */
    public function index(): void {
        require_once __DIR__ . '/../Views/dashboard/index.php';
    }

    /**
     * Carrega a listagem do mês específico vinda do banco
     */
    public function mes(): void {
        $anoAtual = (int)date('Y');
        $usuarioId = (int)$_SESSION['usuario_id'];
        
        $mesSelecionado = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? date('m');
        $mesSelecionado = str_pad($mesSelecionado, 2, "0", STR_PAD_LEFT);

        try {
            $transacoes = Transacao::getPorMes($usuarioId, $mesSelecionado, $anoAtual);
        } catch (Exception $e) {
            $transacoes = [];
        }

        $mesesExtenso = [
            '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
            '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
            '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
        ];
        $nomeMes = $mesesExtenso[$mesSelecionado] ?? 'Mês Desconhecido';

        require_once __DIR__ . '/../Views/dashboard/mes.php';
    }

    /**
     * Exibe o formulário de cadastro de nova movimentação
     */
    public function novaTransacao(): void {
        require_once __DIR__ . '/../Views/dashboard/nova_transacao.php';
    }

    /**
     * Processa os dados recebidos e salva a transação no MySQL
     */
       /**
     * Processa os dados recebidos e força o tipo correto contra erros de cache
     */
    public function salvarTransacao(): void {
        $usuarioId = (int)$_SESSION['usuario_id'];
        
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $valorTotal = filter_input(INPUT_POST, 'valor_total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $valorParcela = filter_input(INPUT_POST, 'valor_parcela', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $totalParcelas = filter_input(INPUT_POST, 'total_parcelas', FILTER_SANITIZE_NUMBER_INT);
        $tipoRaw = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
        $dataTransacao = $_POST['data_transacao'] ?? date('Y-m-d');

        if (!$descricao || !$valorTotal || !$tipoRaw || !$valorParcela || !$totalParcelas) {
            echo "Por favor, preencha todos os campos obrigatórios.";
            return;
        }

        // FORÇAR TRATAMENTO: Mapeia qualquer texto vindo do formulário para o ENUM exato do banco
        $tipoRaw = strtolower(trim($tipoRaw));
        
        if ($tipoRaw === 'receita' || $tipoRaw === 'entrada' || $tipoRaw === 'ganho') {
            $tipoDefinitivo = 'receita'; // Padrão exato da sua tabela
        } else {
            $tipoDefinitivo = 'despesa'; // Padrão exato da sua tabela
        }

        try {
            $db = \App\Core\Database::getConnection();
            
            $query = "
                INSERT INTO transacoes (
                    usuario_id, descricao, valor_total, valor_parcela, 
                    parcela_atual, total_parcelas, tipo, status, 
                    data_transacao, criado_em
                ) 
                VALUES (
                    :usuario_id, :descricao, :valor_total, :valor_parcela, 
                    1, :total_parcelas, :tipo, 'pago', 
                    :data_transacao, NOW()
                )
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'usuario_id' => $usuarioId,
                'descricao' => $descricao,
                'valor_total' => $valorTotal,
                'valor_parcela' => $valorParcela,
                'total_parcelas' => $totalParcelas,
                'tipo' => $tipoDefinitivo, // Envia estritamente 'receita' ou 'despesa'
                'data_transacao' => $dataTransacao
            ]);

            // Redireciona com sucesso total para o painel de controle
            header('Location: /financas-app/dashboard');
            exit;
        } catch (Exception $e) {
            echo "Erro definitivo ao salvar no banco: " . $e->getMessage();
        }
    }

}
