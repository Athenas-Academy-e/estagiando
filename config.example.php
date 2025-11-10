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
define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');

define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_FROM', '');
define('SMTP_FROM_NAME', 'Estagiando');

date_default_timezone_set('America/Sao_Paulo');
