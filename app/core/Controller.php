<?php
declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function render(string $view, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        include __DIR__ . '/../views/layout.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}


