<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

        public static function getConnection(): PDO {
        if (self::$instance === null) {
            
            // 1. Tenta ler do $_ENV (Preenchido pelo Environment.php)
            // 2. Se estiver vazio (bloqueio do servidor), usa os dados fixos da Hostinger como Plano B
            // 3. Se não encontrar nada, cai nos padrões do XAMPP local
            
            $host     = $_ENV['DB_HOST']     ?? '127.0.0.1';
            
            // Tratamento especial para a Hostinger caso o ambiente venha limpo pelo servidor
            if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
                $dbname   = $_ENV['DB_NAME']     ?? 'finan'; 
                $user     = $_ENV['DB_USER']     ?? 'root';    
                $password = $_ENV['DB_PASS']     ?? '';    
            } else {
                // Credenciais fixas de produção como contingência para a Hostinger
                $dbname   = $_ENV['DB_NAME']     ?? 'u738627255_finan_db'; 
                $user     = $_ENV['DB_USER']     ?? 'u738627255_yato_finandb';    
                $password = $_ENV['DB_PASS']     ?? 'iGui2026@';    
            }

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => true, 
                        PDO::ATTR_TIMEOUT => 5, 
                    ]
                );
            } catch (PDOException $e) {
                throw new \Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

}
