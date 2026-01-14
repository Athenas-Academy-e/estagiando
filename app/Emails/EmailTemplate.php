<?php

class EmailTemplate
{
    public static function render(string $template, array $dados = []): string
    {
        $path = __DIR__ . "/templates/{$template}.php";

        if (!file_exists($path)) {
            throw new Exception("Template de e-mail não encontrado: {$template}");
        }

        extract($dados);
        ob_start();
        require $path;
        return ob_get_clean();
    }
}
