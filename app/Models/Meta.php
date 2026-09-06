<?php

namespace App\Models;

use App\Core\Database;
use Exception;
use PDO;

class Meta {

    /**
     * Busca as metas de um usuário específico
     */
    public static function getPorUsuario(int $usuarioId, int $limite = 0): array {
        try {
            $db = Database::getConnection();
            
            $sql = "SELECT * FROM metas WHERE usuario_id = :usuario_id ORDER BY criado_em DESC";
            if ($limite > 0) {
                $sql .= " LIMIT :limite";
            }
            
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
            
            if ($limite > 0) {
                $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return []; // Retorna vazio se der erro para não travar o sistema
        }
    }
}
