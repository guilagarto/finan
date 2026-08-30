<?php

namespace App\Services;

class FinanceService {
    
    // ... mantenha o método getAcumuladoAno aqui ...

    /**
     * Simula a busca de transações detalhadas de um mês específico.
     */
    public function getTransacoesMes(string $mes): array {
        // Simulação de banco de dados: registros detalhados do mês escolhido
        return [
            ['id' => 1, 'data' => "2026-$mes-05", 'descricao' => 'Salário Empresa X', 'tipo' => 'entrada', 'valor' => 4500.00],
            ['id' => 2, 'data' => "2026-$mes-08", 'descricao' => 'Supermercado Central', 'tipo' => 'saida', 'valor' => 650.32],
            ['id' => 3, 'data' => "2026-$mes-10", 'descricao' => 'Freelance Design', 'tipo' => 'entrada', 'valor' => 350.00],
            ['id' => 4, 'data' => "2026-$mes-12", 'descricao' => 'Conta de Luz', 'tipo' => 'saida', 'valor' => 185.20],
            ['id' => 5, 'data' => "2026-$mes-15", 'descricao' => 'Assinatura Streaming', 'tipo' => 'saida', 'valor' => 55.90],
            ['id' => 6, 'data' => "2026-$mes-20", 'descricao' => 'Combustível Posto Shell', 'tipo' => 'saida', 'valor' => 120.00],
            ['id' => 7, 'data' => "2026-$mes-25", 'descricao' => 'Venda de Item Antigo', 'tipo' => 'entrada', 'valor' => 150.00],
        ];
    }
}
