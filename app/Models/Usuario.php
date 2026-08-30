<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Usuario {
    
    /**
     * Busca um usuário pelo e-mail informado no login.
     */
    public static function findByEmail(string $email): ?array {
        $db = Database::getConnection();
        
        // Altere 'email' se a sua coluna tiver outro nome (ex: login, usuario_email)
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        
        $usuario = $stmt->fetch();
        return $usuario ? $usuario : null;
    }
}
