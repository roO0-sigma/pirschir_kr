<?php
declare(strict_types=1);

namespace App\Core;

class Bootstrap
{
    public function run(): void
    {
        $controllerName = $_GET['controller'] ?? 'blog';
        $actionName = $_GET['action'] ?? 'index';

        $controllerClass = '\\App\\Controllers\\' . ucfirst($controllerName) . 'Controller';

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo 'Controller not found';
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            echo 'Action not found';
            return;
        }

        $controller->$actionName();
    }
}


