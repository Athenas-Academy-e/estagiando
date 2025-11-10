<?php

/**
 * Middleware simples de autenticação.
 * Impede acesso a áreas restritas se o usuário não estiver logado.
 */

class Auth
{
    /** 🧩 Inicia a sessão com segurança (apenas uma vez) */
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * 🔒 Verifica se o usuário está logado conforme o tipo
     * @param string $tipo ('admin', 'empresa', 'profissional')
     */
    public static function check($tipo)
    {
        self::startSession();

        switch ($tipo) {
            case 'admin':
                if (empty($_SESSION['admin_id'])) {
                    header("Location: /login");
                    exit;
                }
                break;

            case 'empresa':
                if (empty($_SESSION['empresa_id'])) {
                    header("Location: /login");
                    exit;
                }
                break;

            case 'profissional':
                if (empty($_SESSION['profissional_id'])) {
                    header("Location: /login");
                    exit;
                }
                break;

            default:
                // Tipo inválido de autenticação
                header("Location: /login");
                exit;
        }

        // ✅ Autenticado corretamente
        return true;
    }

    /** 🚪 Logout seguro (encerra sessão e redireciona) */
    public static function logout()
    {
        self::startSession();

        // Limpa apenas as variáveis de sessão específicas
        $_SESSION = [];

        // Destrói a sessão
        session_destroy();

        header("Location: /login");
        exit;
    }
}
