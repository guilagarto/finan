<?php
namespace App\Services;

use App\Models\Usuario;

class AuthService {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Executa o processo de autenticação
    public function autenticar($email, $senha) {
        // Sanatização básica de dados
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $senha = trim($senha);

        if (empty($email) || empty($senha)) {
            return "Por favor, preencha todos os campos.";
        }

        // Busca o usuário no banco via Model
        $usuario = $this->usuarioModel->buscarPorEmail($email);

        // Verifica se o usuário existe e se a senha criptografada confere
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $this->criarSessao($usuario);
            return true;
        }

        return "E-mail ou senha incorretos.";
    }

        // Cria as variáveis de sessão seguras
            private function criarSessao($usuario) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['logado'] = true;
    }

    public static function estaLogado() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
    }



    // Destrói a sessão ao deslogar
    public function deslogar() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
        // Executa a lógica de validação e criação de novas contas
    public function criarConta($nome, $email, $senha, $confirmarSenha) {
        // 1. Limpeza e formatação de dados
        $nome = filter_var(trim($nome), FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $senha = trim($senha);

        // 2. Validações de campos vazios
        if (empty($nome) || empty($email) || empty($senha)) {
            return "Todos os campos são de preenchimento obrigatório.";
        }

        // 3. Validação do formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "O formato do e-mail inserido é inválido.";
        }

        // 4. Verificação de igualdade das senhas
        if ($senha !== $confirmarSenha) {
            return "As senhas digitadas não coincidem.";
        }

        // 5. Requisito mínimo de segurança da senha
        if (strlen($senha) < 6) {
            return "A senha deve conter no mínimo 6 caracteres.";
        }

        // 6. Regra de Negócio: Impedir e-mails duplicados no banco
        if ($this->usuarioModel->buscarPorEmail($email)) {
            return "Este e-mail já está cadastrado em nossa plataforma.";
        }

        // 7. Criptografia segura da senha (Bcrypt padrão)
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        $payload = [
            'nome'  => $nome,
            'email' => $email,
            'senha' => $senhaCriptografada
        ];

        // 8. Salva no banco de dados através do Model
        if ($this->usuarioModel->salvar($payload)) {
            return true;
        }

        return "Ocorreu um erro interno ao criar sua conta. Tente novamente.";
    }

}
