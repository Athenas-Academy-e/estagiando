<?php
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';

class LoginController
{
    public function index()
    {
        session_start();
        $redirect = $_GET['redirect'] ?? '';

        // ✅ Se já estiver logado E NÃO houver redirect, vai pro dashboard
        if (isset($_SESSION['profissional_id']) || isset($_SESSION['empresa_id'])) {

            // Se houver redirect (ex: /apply?id=8)
            if (!empty($redirect)) {
                header("Location: $redirect");
                exit;
            }

            // Se for empresa
            if (isset($_SESSION['empresa_id'])) {
                header("Location: /empresas/dashboard");
                exit;
            }

            // Se for profissional
            if (isset($_SESSION['profissional_id'])) {
                header("Location: /profissional/dashboard");
                exit;
            }
        }

        $erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $redirect = $_POST['redirect'] ?? $redirect;

            $empresaModel = new Empresa();
            $profModel = new Profissional();

            // 🔹 Login Empresa
            $empresa = $empresaModel->login($email, $senha);
            if ($empresa) {
                $_SESSION['empresa_id'] = $empresa['id'];
                $_SESSION['empresa_nome'] = !empty($empresa['razao_social']) ? $empresa['nome_fantasia'] : $empresa['razao_social'];
                $_SESSION['empresa_logo']= $empresa['logo'];
                $_SESSION['usuario_tipo'] = 'empresa';

                // ✅ Evita loop se a dashboard já estiver protegida
                if (!empty($redirect)) {
                    header("Location: $redirect");
                } else {
                    header("Location: /empresas/dashboard");
                }
                exit;
            }

            // 🔹 Login Profissional
            $prof = $profModel->login($email, $senha);
            if ($prof) {
                $_SESSION['profissional_id'] = $prof['id'];
                $_SESSION['profissional_nome'] = $prof['nome'];
                $_SESSION['profissional_logo'] = $prof['foto'];
                $_SESSION['usuario_tipo'] = 'profissional';

                if (!empty($redirect)) {
                    header("Location: $redirect");
                } else {
                    header("Location: /profissional/dashboard");
                }
                exit;
            }

            // ❌ Falha no login
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
