<?php
class RedirectController
{
    public function index()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        // Remove o prefixo "/redirect/"
        $path = parse_url($uri, PHP_URL_PATH);
        $redirectPath = preg_replace('#^/redirect/#', '', $path);
        $redirectPath = urldecode($redirectPath);

        if (empty($redirectPath)) {
            header("Location: /");
            exit;
        }

        header("Location: $redirectPath");
        exit;
    }
}
