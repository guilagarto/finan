<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Transacao {

    /**
     * Busca os totais de entradas e saídas de cada mês.
     * Retorna uma estrutura limpa direto do banco.
     */
    public static function getAcumuladoAno(int $usuarioId, int $ano): array {
        try {
            $db = Database::getConnection();

            $query = "
                SELECT 
                    MONTH(data_transacao) as mes_num,
                    SUM(CASE WHEN tipo = 'entrada' THEN valor_total ELSE 0 END) as entradas,
                    SUM(CASE WHEN tipo = 'saida' THEN valor_total ELSE 0 END) as saidas
                FROM transacoes
                WHERE usuario_id = :usuario_id AND YEAR(data_transacao) = :ano
                GROUP BY MONTH(data_transacao)
            ";

            $stmt = $db->prepare($query);
            $stmt->execute([
                'usuario_id' => $usuarioId,
                'ano' => $ano
            ]);

            return $stmt->fetchAll() ?: [];
        } catch (\Exception $e) {
            return []; // Retorna vazio em caso de erro para não travar o PHP
        }
    }

    /**
     * Puxa a lista completa de transações de um mês específico.
     */
    public static function getPorMes(int $usuarioId, string $mes, int $ano): array {
        try {
            $db = Database::getConnection();

            $query = "
                SELECT id, data_transacao, descricao, tipo, valor_total 
                FROM transacoes
                WHERE usuario_id = :usuario_id 
                  AND MONTH(data_transacao) = :mes 
                  AND YEAR(data_transacao) = :ano
                ORDER BY data_transacao DESC
            ";

            $stmt = $db->prepare($query);
            $stmt->execute([
                'usuario_id' => $usuarioId,
                'mes' => (int)$mes,
                'ano' => $ano
            ]);

            return $stmt->fetchAll() ?: [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
