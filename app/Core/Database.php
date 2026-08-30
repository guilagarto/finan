<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
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
                        // Configuração crucial para o XAMPP ler os tipos corretamente
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
