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
// 🧭 ROTAS ESPECIAIS
// =====================================================

// 🔁 Redirecionamentos externos (mantém o comportamento atual)
if (strtolower($segments[0]) === 'redirect') {
    require_once __DIR__ . '/../app/Controllers/RedirectController.php';
    $controller = new RedirectController();
    $controller->index(); // sempre chama index()
    exit;
}

// 📄 Geração de PDF
if (strtolower($segments[0]) === 'pdf') {
    require_once __DIR__ . '/../app/Controllers/PdfController.php';
    $pdfController = new PdfController();

    // 🔹 Visualizar no navegador (profissional)
    if (isset($segments[1]) && $segments[1] === 'view') {
        $pdfController->view();
    }
    // 🔹 Baixar o próprio currículo (profissional)
    elseif (isset($segments[1]) && $segments[1] === 'curriculo' && !isset($segments[2])) {
        $pdfController->download();
    }
    // 🔹 Visualizar o currículo de outro candidato (admin/empresa)
    elseif (isset($segments[1]) && $segments[1] === 'curriculo' && isset($segments[2])) {
        $id = (int)$segments[2];
        $pdfController->curriculo($id, true);
    } else {
        http_response_code(404);
        echo "<main class='text-center py-20 text-gray-600'>
                <h1 class='text-2xl font-bold mb-2'>Página de PDF não encontrada</h1>
                <p>Verifique a URL e tente novamente.</p>
              </main>";
    }
    exit;
}

// =====================================================
// 🚀 CONTROLLERS PADRÕES
// =====================================================

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        // ✅ Chama o método do controller (ex: VagasController->index())
        $controller->$method();
    } else {
        http_response_code(404);
        echo "<main class='text-center py-20 text-red-600'>
                <h1 class='text-2xl font-bold mb-2'>Erro 404</h1>
                <p>Método <strong>$method()</strong> não encontrado em <strong>$controllerName</strong>.</p>
              </main>";
    }
} else {
    http_response_code(404);
    echo "<main class='text-center py-20 text-gray-600'>
            <h1 class='text-2xl font-bold mb-2'>Erro 404 — Página não encontrada</h1>
            <p>O controller <strong>$controllerName</strong> não existe.</p>
          </main>";
}
