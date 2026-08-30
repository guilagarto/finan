<?php
namespace App\Core;

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $file = "../app/views/" . $view . ".php";
        if (file_exists($file)) {
            require_once $file;
        } else {
            die("View {$view} não encontrada.");
        }
    }
}
