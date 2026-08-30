<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            // AJUSTE: Mudado de 'localhost' para '127.0.0.1' para evitar travamento no Linux/XAMPP
            $host = '127.0.0.1';
            $dbname = 'finan'; 
            $user = 'root';    
            $password = '';    

            try {
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        // Adicionado timeout para o banco não travar a página se cair
                        PDO::ATTR_TIMEOUT => 5, 
                    ]
                );
            } catch (PDOException $e) {
                // Lança um erro legível em vez de travar a aplicação
                throw new \Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
