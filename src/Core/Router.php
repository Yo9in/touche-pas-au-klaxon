<?php
namespace App\Core;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "<h1>404</h1><p>Route introuvable : <code>{$uri}</code></p>";
            return;
        }

        // "App\Controller\HomeController@index"
        list($class, $methodName) = explode('@', $action);

        $controller = new $class();
        $controller->$methodName();
    }
}
