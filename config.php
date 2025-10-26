<?php
/**
 * Configuração global do projeto Estagiando
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__FILE__));
}

/**
 * Detecta automaticamente o ambiente e define a URL base
 * Exemplo:
 * - XAMPP → http://localhost/estagiando/
 * - BrowserSync → http://localhost:3000/
 * - Produção → https://seudominio.com/
 */
$serverName = $_SERVER['HTTP_HOST'] ?? 'localhost';
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

if (strpos($serverName, '3000') !== false) {
    // Ambiente de desenvolvimento com BrowserSync
    define('BASE_URL', '/');
} elseif (strpos($serverName, 'localhost') !== false) {
    // Ambiente local no XAMPP
    define('BASE_URL', '/');
} else {
    // Ambiente de produção
    define('BASE_URL', '/');
}

/**
 * Banco de dados (ajuste conforme seu ambiente)
 */
define('DB_HOST', '192.168.0.246');
define('DB_NAME', 'jobboard');
define('DB_USER', 'root');
define('DB_PASS', '417782');

/**
 * Caminho global para assets
 */
define('ASSETS_URL', BASE_URL . 'public/assets/');
define('CSS_URL', BASE_URL . 'public/css/');
define('JS_URL', BASE_URL . 'public/js/');

date_default_timezone_set('America/Sao_Paulo');
