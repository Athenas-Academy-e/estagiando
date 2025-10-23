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
$segments = explode('/', $route);

// Controller e método
$controllerName = ucfirst(strtolower($segments[0])) . 'Controller';
$method = $segments[1] ?? 'index';

// Caminho do controller
$controllerPath = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

// Verifica se o controller existe
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    // Verifica se o método existe dentro do controller
    if (method_exists($controller, $method)) {
        $controller->$method(); // Chama, ex: VagasController->detalhe()
    } else {
        echo "Método <strong>$method()</strong> não encontrado em $controllerName.";
    }
} else {
    http_response_code(404);
    echo "Página não encontrada: $controllerName";
}
