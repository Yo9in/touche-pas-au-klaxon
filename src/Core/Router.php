<?php
namespace App\Core;

/**
 * Classe Router
 *
 * Routeur minimaliste gérant les routes GET et POST.
 * Permet de lier un chemin (URI) à une action de contrôleur,
 * puis d'exécuter cette action en fonction de la requête courante.
 */
class Router
{
    /**
     * Tableau des routes enregistrées.
     *
     * Exemple :
     * [
     *   'GET' => ['/login' => 'App\Controller\AuthController@loginForm'],
     *   'POST' => ['/login' => 'App\Controller\AuthController@login']
     * ]
     *
     * @var array<string, array<string,string>>
     */
    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Ajoute une route GET.
     *
     * @param string $path Chemin de la route (ex: "/login")
     * @param string $action Action à exécuter ("FQCNController@méthode")
     * @return void
     */
    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    /**
     * Ajoute une route POST.
     *
     * @param string $path Chemin de la route
     * @param string $action Action à exécuter ("FQCNController@méthode")
     * @return void
     */
    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    /**
     * Exécute la route correspondant à la requête courante.
     *
     * - Détermine la méthode HTTP (GET/POST)
     * - Détermine l'URI
     * - Cherche l'action correspondante
     * - Instancie le contrôleur et appelle la méthode
     *
     * @return void
     */
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
