<?php
namespace App\Core;

class Environment {
    public static function load($dir) {
        if (!file_exists($dir . '/.env')) return false;
        
        $lines = file($dir . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Ignora linhas de comentário
            if (strpos(trim($line), '#') === 0) continue;
            
            // Verifica se a linha contém o sinal de igual
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                
                $name = trim($name);
                // Remove aspas simples ou duplas que possam estar no valor
                $value = trim(str_replace(['"', "'"], '', $value));
                
                // Injeta nos dois mapas globais do PHP para garantir compatibilidade
                $_ENV[$name] = $value;
                putenv("{$name}={$value}");
            }
        }
    }
}
