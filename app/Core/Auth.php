<?php

/**
 * Middleware simples de autenticação.
 * Impede acesso a áreas restritas se o usuário não estiver logado.
 */

class Auth
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check($tipo)
    {
        session_start();

        // ✅ Verifica se está logado e se o tipo é o correto
        if ($tipo === 'profissional' && empty($_SESSION['profissional_id'])) {
            header("Location: /login");
            exit;
        }

        if ($tipo === 'empresa' && empty($_SESSION['empresa_id'])) {
            header("Location: /login");
            exit;
        }

        // Tudo certo — permanece na página
        return true;
    }

    public static function logout()
    {
        self::startSession();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
