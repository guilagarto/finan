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
            header("Location: " . url('/dashboard/mes?id=' . $mesId));

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
        /**
     * Carrega a listagem do mês específico vinda do banco
     */
    /**
     * Carrega a listagem do mês específico vinda do banco
     */
     public function mes(): void {
        $anoAtual = 2026; // Mantém o ano base dos seus lançamentos
        
        // CORREÇÃO: Remove o ID fixo e adota o ID real da sessão do usuário logado
        $usuarioId = (int)$_SESSION['usuario_id']; 
        
        $mesSelecionado = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? date('m');
        $mesSelecionado = str_pad($mesSelecionado, 2, "0", STR_PAD_LEFT);

        // ... O restante do código do seu método mes() continua igual abaixo ...


        // ... mantenha o restante do código do método mes() igual ...


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
        /**
     * Processa os dados recebidos e salva a transação no MySQL com suporte a Status e Parcelas
     */
    public function salvarTransacao(): void {
        $usuarioId = (int)$_SESSION['usuario_id'];
        
        // Captura e higieniza todos os dados vindos do formulário via POST
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $valorTotal = filter_input(INPUT_POST, 'valor_total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $valorParcela = filter_input(INPUT_POST, 'valor_parcela', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $totalParcelas = filter_input(INPUT_POST, 'total_parcelas', FILTER_SANITIZE_NUMBER_INT);
        $tipoRaw = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'pago';
        $dataTransacao = $_POST['data_transacao'] ?? date('Y-m-d');

        if (!$descricao || !$valorTotal || !$tipoRaw || !$valorParcela || !$totalParcelas) {
            echo "Por favor, preencha todos os campos obrigatórios.";
            return;
        }

        // Mapeia qualquer texto vindo do formulário para o ENUM exato do banco ('receita' ou 'despesa')
        $tipoRaw = strtolower(trim($tipoRaw));
        if ($tipoRaw === 'receita' || $tipoRaw === 'entrada' || $tipoRaw === 'ganho') {
            $tipoDefinitivo = 'receita';
        } else {
            $tipoDefinitivo = 'despesa';
        }

        try {
            $db = \App\Core\Database::getConnection();
            
            // DECLARAÇÃO FIXA: Definindo a query com todas as colunas obrigatórias
            $query = "
                INSERT INTO transacoes (
                    usuario_id, descricao, valor_total, valor_parcela, 
                    parcela_atual, total_parcelas, tipo, status, 
                    data_transacao, criado_em
                ) 
                VALUES (
                    :usuario_id, :descricao, :valor_total, :valor_parcela, 
                    1, :total_parcelas, :tipo, :status, 
                    :data_transacao, NOW()
                )
            ";
            
            // Prepara o comando SQL de forma segura contra injeção de código
            $stmt = $db->prepare($query);
            
            // Executa passando as variáveis mapeadas nos marcadores
            $stmt->execute([
                'usuario_id' => $usuarioId,
                'descricao' => $descricao,
                'valor_total' => $valorTotal,
                'valor_parcela' => $valorParcela,
                'total_parcelas' => $totalParcelas,
                'tipo' => $tipoDefinitivo,
                'status' => $status,
                'data_transacao' => $dataTransacao
            ]);

            // Redireciona com sucesso total para o painel de controle principal
            header('Location: /financas-app/dashboard');
            exit;
        } catch (\Exception $e) {
            echo "Erro definitivo ao salvar no banco: " . $e->getMessage();
        }
    }
        /**
     * Remove uma transação do banco de dados com segurança
     */
    public function excluirTransacao(): void {
        // Usa o ID 4 se você ainda estiver testando com ele fixo, ou adote o ID da sessão:
        $usuarioId = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 4; 
        
        $transacaoId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $mesRetorno = filter_input(INPUT_GET, 'mes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '08';

        if (!$transacaoId) {
            header('Location: /financas-app/dashboard');
            exit;
        }

        try {
            $db = \App\Core\Database::getConnection();
            
            // Remove a linha baseando-se no ID da transação
            $query = "DELETE FROM transacoes WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute([
                'id' => $transacaoId
            ]);

            // Força o retorno do navegador para a página do mês onde o usuário já estava
            header("Location: /financas-app/dashboard/mes?id=" . $mesRetorno);
            exit;
        } catch (\Exception $e) {
            echo "Erro ao excluir o lançamento: " . $e->getMessage();
        }

        

    }
    public function marcarComoPaga(): void {
    // Pega o ID da transação vindo do link
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    // Guarda o ID do mês atual para onde o usuário deve voltar após atualizar
    $mesId = filter_input(INPUT_GET, 'mes_id', FILTER_VALIDATE_INT) ?? date('m');

    if ($id) {
        try {
            $db = \App\Core\Database::getConnection();
            // Atualiza o status para 'Pago' no banco de dados
            $stmt = $db->prepare("UPDATE transacoes SET status = 'Pago' WHERE id = :id");
            $stmt->execute(['id' => $id]);
        } catch (\Exception $e) {
            // Tratamento de erro se necessário
        }
    }

    // Redireciona de volta para a mesma página do mês de onde o usuário clicou
    header("Location: " . url('/dashboard/mes?id=' . $mesId));
    exit;
}




}
