<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Core/Auth.php';

/**
 * =====================================================
 * 🚀 ROTEADOR MVC — ESTAGIANDO
 * =====================================================
 * Responsável por interpretar a URL e direcionar para o
 * controller e método corretos. Também trata rotas
 * especiais como PDF e Redirect.
 * =====================================================
 */

// 🧩 Autoload automático de Controllers e Models
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

// ⚠️ Carrega o controlador de erros (sempre disponível)
require_once __DIR__ . '/../app/Controllers/ErrorController.php';
$errorController = new ErrorController();

// 🔍 Obter rota (ex: /vagas, /cadastro, /redirect/https://...)
$route = $_GET['url'] ?? 'home';
$route = trim($route, '/');
$segments = explode('/', $route);

// Nome do controller e método
$controllerName = ucfirst(strtolower($segments[0])) . 'Controller';
$method = $segments[1] ?? 'index';

// Caminho do controller
$controllerPath = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

// =====================================================
// ⚠️ ROTA DIRETA DE ERROS (ex: /404, /403, /500, /401)
// =====================================================
if (is_numeric($segments[0]) && in_array($segments[0], ['401', '403', '404', '500'])) {
    switch ($segments[0]) {
        case '401':
            $errorController->unauthorized();
            break;
        case '403':
            $errorController->forbidden();
            break;
        case '404':
            $errorController->notFound();
            break;
        case '500':
            $errorController->serverError();
            break;
    }
    exit;
}

// =====================================================
// 🧭 ROTAS ESPECIAIS
// =====================================================

// 🔁 Redirecionamentos externos
if (strtolower($segments[0]) === 'redirect') {
    require_once __DIR__ . '/../app/Controllers/RedirectController.php';
    $controller = new RedirectController();
    $controller->index();
    exit;
}

// 📄 Geração de PDF
if (strtolower($segments[0]) === 'pdf') {
    require_once __DIR__ . '/../app/Controllers/PdfController.php';
    $pdfController = new PdfController();

    if (isset($segments[1]) && $segments[1] === 'view') {
        $pdfController->view();
    } elseif (isset($segments[1]) && $segments[1] === 'curriculo' && !isset($segments[2])) {
        $pdfController->download();
    } elseif (isset($segments[1]) && $segments[1] === 'curriculo' && isset($segments[2])) {
        $id = (int)$segments[2];
        $pdfController->curriculo($id, true);
    } else {
        $errorController->notFound();
    }
    exit;
}

// 🔐 Recuperação de Senha (empresas, profissionais e admins)
if (strtolower($segments[0]) === 'esqueci-senha' || strtolower($segments[0]) === 'redefinir-senha') {
    require_once __DIR__ . '/../app/Controllers/RecuperacaoController.php';
    $recuperacaoController = new RecuperacaoController();

    if (strtolower($segments[0]) === 'esqueci-senha') {
        $recuperacaoController->esqueci();
    } elseif (strtolower($segments[0]) === 'redefinir-senha') {
        $recuperacaoController->redefinir();
    }
    exit;
}

// =====================================================
// 🚀 CONTROLLERS PADRÕES
// =====================================================
try {
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        $controller = new $controllerName();

        if (method_exists($controller, $method)) {
            // ✅ Chama o método do controller
            $controller->$method();
        } else {
            // Método não encontrado
            $errorController->notFound();
        }
    } else {
        // Controller não encontrado
        $errorController->notFound();
    }
} catch (Exception $e) {
    // ⚠️ Qualquer erro interno → página 500
    error_log($e->getMessage());
    $errorController->serverError();
}
