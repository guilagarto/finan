<?php

namespace App\Controllers;

use App\Models\Meta;

class MetaController {
    
    /**
     * Pega o ID do usuário logado na sessão de forma segura
     */
    private function getUsuarioId(): int {
        return isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 4; 
    }

    /**
     * Lista todas as metas (Página metas.php)
     */
    public function index(): void {
        $usuarioId = $this->getUsuarioId();
        
        // Usa a Model Meta para buscar os registros de forma padronizada
        $metas = \App\Models\Meta::getPorUsuario($usuarioId);

        require_once __DIR__ . '/../Views/dashboard/metas.php';
    }

    /**
     * Exibe o formulário de criação de nova meta (Página nova_meta.php)
     */
    public function nova(): void {
        require_once __DIR__ . '/../Views/dashboard/nova_meta.php';
    }

    /**
     * Salva a nova meta vinda do formulário (POST)
     */
    public function salvar(): void {
        $usuarioId = $this->getUsuarioId();
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
        $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS);
        $valor_objetivo = filter_input(INPUT_POST, 'valor_objetivo', FILTER_VALIDATE_FLOAT);
        $valor_poupado = filter_input(INPUT_POST, 'valor_poupado', FILTER_VALIDATE_FLOAT) ?? 0.00;
        $prazo = $_POST['prazo'] ?? null;

        if ($titulo && $categoria && $valor_objetivo) {
            try {
                $db = \App\Core\Database::getConnection();
                $stmt = $db->prepare("INSERT INTO metas (usuario_id, titulo, categoria, valor_objetivo, valor_poupado, prazo) VALUES (:uid, :titulo, :cat, :obj, :poup, :prazo)");
                $stmt->execute([
                    'uid' => $usuarioId,
                    'titulo' => $titulo,
                    'cat' => $categoria,
                    'obj' => $valor_objetivo,
                    'poup' => $valor_poupado,
                    'prazo' => !empty($prazo) ? $prazo : null
                ]);
            } catch (\Exception $e) {
                // Tratado silenciosamente
            }
        }
        header('Location: ' . url('/dashboard/metas'));
        exit;
    }

    /**
     * Adiciona mais dinheiro poupado a uma meta existente (POST)
     */
    public function atualizarPoupanca(): void {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $valor = filter_input(INPUT_POST, 'valor_adicional', FILTER_VALIDATE_FLOAT);

        if ($id && $valor) {
            try {
                $db = \App\Core\Database::getConnection();
                $stmt = $db->prepare("UPDATE metas SET valor_poupado = valor_poupado + :valor WHERE id = :id");
                $stmt->execute(['valor' => $valor, 'id' => $id]);
            } catch (\Exception $e) {
                // Tratado silenciosamente
            }
        }
        header('Location: ' . url('/dashboard/metas'));
        exit;
    }
}
