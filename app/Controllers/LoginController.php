<?php
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';

class LoginController
{
    public function index()
    {
        session_start();
        $erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $empresaModel = new Empresa();
            $profissionalModel = new Profissional();

            // Verifica se é empresa
            $empresa = $empresaModel->login($email, $senha);
            if ($empresa) {
                $_SESSION['usuario_tipo'] = 'empresa';
                $_SESSION['usuario_id'] = $empresa['id'];
                $_SESSION['usuario_nome'] = $empresa['nome'];
                header("Location: /empresas/dashboard");
                exit;
            }

            // Verifica se é profissional
            $prof = $profissionalModel->login($email, $senha);
            if ($prof) {
                $_SESSION['usuario_tipo'] = 'profissional';
                $_SESSION['usuario_id'] = $prof['id'];
                $_SESSION['usuario_nome'] = $prof['nome'];
                header("Location: /profissional/dashboard");
                exit;
            }

            // Caso não encontre
            $erro = "❌ E-mail ou senha inválidos.";
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/login/index.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
