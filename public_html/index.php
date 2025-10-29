<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Core/Auth.php';

// 🔄 Autoload automático de controllers e models
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

// 🔍 Obter rota ex: /vagas, /cadastro, /redirect/https://...
$route = $_GET['url'] ?? 'home';
$route = trim($route, '/');
$segments = explode('/', $route);

// 🧭 Nome do controller e método
$controllerName = ucfirst(strtolower($segments[0])) . 'Controller';
$method = $segments[1] ?? 'index';

// Caminho do controller
$controllerPath = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

// ✅ Tratamento especial para /redirect
if (strtolower($segments[0]) === 'redirect') {
    require_once __DIR__ . '/../app/Controllers/RedirectController.php';
    $controller = new RedirectController();
    $controller->index(); // Sempre chama index()
    exit;
}

// 🚀 Controller normal
if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    // Verifica se o método existe
    if (method_exists($controller, $method)) {
        $controller->$method(); // ex: VagasController->index()
    } else {
        echo "Método <strong>$method()</strong> não encontrado em $controllerName.";
    }
} else {
    http_response_code(404);
    echo "Página não encontrada: $controllerName";
}
