<?php
session_start();

// --- LEITOR NATIVO DE .ENV PARA HOSTINGER (Lê perfeitamente no Linux) ---
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignora comentários no arquivo .env
        if (strpos(trim($line), '#') === 0) continue;
        
        // Divide a linha entre o nome da chave e o valor
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Injeta os dados nas variáveis globais do PHP
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
// --------------------------------------------------------------------------------

/**
 * Ponto de entrada mestre para o padrão MVC na Hostinger
 */
require_once __DIR__ . '/public/index.php';
