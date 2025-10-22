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

    public static function check($requiredType = null)
    {
        self::startSession();

        if (empty($_SESSION['usuario_id']) || empty($_SESSION['usuario_tipo'])) {
            header("Location: /login");
            exit;
        }

        // Se um tipo específico for exigido (empresa/profissional)
        if ($requiredType && $_SESSION['usuario_tipo'] !== $requiredType) {
            header("Location: /login");
            exit;
        }
    }

    public static function logout()
    {
        self::startSession();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
