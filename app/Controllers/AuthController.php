<?php

namespace App\Controllers;

use App\Models\Usuario;

class AuthController {
    
    /**
     * Exibe a tela de login
     */
    public function showLogin(): void {
        // Se o usuário já estiver logado, redireciona direto para a dashboard
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . url('/dashboard'));
            exit;
        }

        // Carrega a View de login (usando o caminho atualizado com V maiúsculo)
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Processa o envio do formulário de login (POST)
     */
    public function login(): void {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!$email || !$senha) {
            $_SESSION['erro_login'] = "Preencha todos os campos.";
            header('Location: ' . url('/login'));
            exit;
        }

        // BUSCA REAL NO BANCO DE DADOS usando a classe Usuario importada
        $usuario = Usuario::findByEmail($email);

        // Valida se o usuário existe e se a senha confere com o hash criptográfico
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Define as variáveis de sessão essenciais
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            
            // Limpa erros antigos e joga para a Dashboard
            unset($_SESSION['erro_login']);
            header('Location: ' . url('/dashboard'));
            exit;
        } else {
            // Falha na autenticação
            $_SESSION['erro_login'] = "E-mail ou senha inválidos.";
            header('Location: ' . url('/login'));
            exit;
        }
    }

    /**
     * Processa o encerramento da sessão (Logout)
     */
    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session_use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header('Location: ' . url('/login'));
        exit;
    }

    /**
     * Exibe a tela de registro de novos usuários
     */
    public function showCadastro(): void {
        require_once __DIR__ . '/../Views/auth/cadastro.php';
    }

    /**
     * Processa o formulário de cadastro e salva no banco de dados
     */
    public function register(): void {
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senhaRaw = $_POST['senha'] ?? '';

        if (!$nome || !$email || !$senhaRaw) {
            echo "Preencha todos os campos obrigatórios.";
            return;
        }

        // Transforma a senha comum em um Hash Criptográfico seguro
        $senhaHash = password_hash($senhaRaw, PASSWORD_DEFAULT);

        try {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, criado_em) VALUES (:nome, :email, :senha, NOW())");
            
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'senha' => $senhaHash
            ]);
            
            // Cadastro realizado com sucesso! Redireciona para o login
            header('Location: ' . url('/login'));
            exit;
        } catch (\Exception $e) {
            echo "Erro ao cadastrar usuário no banco: " . $e->getMessage();
        }
    }

}
