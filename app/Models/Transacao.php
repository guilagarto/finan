<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Transacao {

    /**
     * Puxa a lista de transações de um mês específico com conversão rígida de chaves.
     */
    public static function getPorMes(int $usuarioId, string $mes, int $ano): array {
        $db = Database::getConnection();

        // Query limpa e direta usando as colunas idênticas à estrutura da tabela
                // CORREÇÃO EXPERIMENTAL: Remove o filtro de usuario_id para isolar o problema de tipo de campo
         $query = "
            SELECT id, data_transacao, descricao, tipo, valor_total, valor_parcela, total_parcelas, status
            FROM transacoes
            WHERE CAST(usuario_id AS UNSIGNED) = :usuario_id 
              AND (
                (total_parcelas = 1 AND MONTH(data_transacao) = :mes AND YEAR(data_transacao) = :ano)
                OR 
                (total_parcelas > 1 AND (YEAR(data_transacao) < :ano OR (YEAR(data_transacao) = :ano AND MONTH(data_transacao) <= :mes)))
              )
            ORDER BY data_transacao DESC
        ";

        $stmt = $db->prepare($query);
        $stmt->execute([
            'usuario_id' => (int)$usuarioId, // Garante que o PHP envie como inteiro puro
            'mes' => (int)$mes,
            'ano' => $ano
        ]);


        $resultados = $stmt->fetchAll() ?: [];
        $transacoesProcessadas = [];

        foreach ($resultados as $linhaBruta) {
            // BLINDAGEM: Força todas as chaves vindas do MySQL a ficarem em letras minúsculas
            $item = array_change_key_case($linhaBruta, CASE_LOWER);

            if ((int)$item['total_parcelas'] === 1) {
                $item['valor_exibicao'] = (float)$item['valor_total'];
                $item['parcela_texto'] = 'À Vista';
                $transacoesProcessadas[] = $item;
            } else {
                // Matemática de projeção de parcelas baseada na data de cadastro
                $anoCompra = (int)date('Y', strtotime($item['data_transacao']));
                $mesCompra = (int)date('m', strtotime($item['data_transacao']));
                
                $totalMesesFiltro = ($ano * 12) + (int)$mes;
                $totalMesesCompra = ($anoCompra * 12) + $mesCompra;
                
                $mesesDiferenca = $totalMesesFiltro - $totalMesesCompra;
                $parcelaAtualNoMes = $mesesDiferenca + 1;

                // Valida se a parcela pertence ao intervalo ativo da transação
                if ($parcelaAtualNoMes >= 1 && $parcelaAtualNoMes <= (int)$item['total_parcelas']) {
                    $item['valor_exibicao'] = (float)$item['valor_parcela'];
                    $item['parcela_texto'] = "Parc. {$parcelaAtualNoMes}/{$item['total_parcelas']}";
                    
                    // Ajuste de status de atraso para parcelas antigas em aberto
                    if ($item['status'] === 'pendente' && ($ano < (int)date('Y') || ($ano === (int)date('Y') && (int)$mes < (int)date('m')))) {
                        $item['status'] = 'atrasado';
                    }

                    $transacoesProcessadas[] = $item;
                }
            }
        }

        return $transacoesProcessadas;
    }
}
