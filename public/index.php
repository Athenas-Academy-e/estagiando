<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Core/Auth.php';

// Função simples de autoload (carrega controllers e models automaticamente)
spl_autoload_register(function ($class) {
    $paths = ['../app/Controllers/', '../app/Models/'];
    foreach ($paths as $path) {
        $file = __DIR__ . "/$path$class.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Obter a rota (ex: /vagas, /cadastro)
$route = $_GET['url'] ?? 'home';
$route = trim($route, '/');
$route = ucfirst(strtolower($route)) . 'Controller';

// Caminho do controller
$controllerPath = __DIR__ . '/../app/Controllers/' . $route . '.php';

if (file_exists($controllerPath)) {
    $controller = new $route();
    if (method_exists($controller, 'index')) {
        $controller->index();
    } else {
        echo "Método index() não encontrado no controller $route.";
    }
} else {
    http_response_code(404);
    echo "Página não encontrada.";
}
