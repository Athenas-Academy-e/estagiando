<?php
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Models/Admin.php'; // ✅ novo model para admin

class LoginController
{
    public function index()
    {
        $pageTitle = "Estagiando - Login";
        session_start();
        $redirect = $_GET['redirect'] ?? '';

        // ✅ Se já estiver logado
        if (isset($_SESSION['profissional_id']) || isset($_SESSION['empresa_id']) || isset($_SESSION['admin_id'])) {

            // Se houver redirect (ex: /apply?id=8)
            if (!empty($redirect)) {
                header("Location: $redirect");
                exit;
            }

            // Redirecionamentos por tipo
            if (isset($_SESSION['empresa_id'])) {
                header("Location: /empresas/dashboard");
                exit;
            }

            if (isset($_SESSION['profissional_id'])) {
                header("Location: /profissional/dashboard");
                exit;
            }

            if (isset($_SESSION['admin_id'])) {
                header("Location: /admin/dashboard");
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
            $adminModel = new Admin();

            // 🔹 Login Empresa
            $empresa = $empresaModel->login($email, $senha);
            if ($empresa) {
                $_SESSION['empresa_id'] = $empresa['id'];
                $_SESSION['empresa_nome'] = !empty($empresa['razao_social']) ? $empresa['nome_fantasia'] : $empresa['razao_social'];
                $_SESSION['empresa_logo'] = $empresa['logo'];
                $_SESSION['usuario_tipo'] = 'empresa';

                header("Location: " . (!empty($redirect) ? $redirect : "/empresas/dashboard"));
                exit;
            }

            // 🔹 Login Profissional
            $prof = $profModel->login($email, $senha);
            if ($prof) {
                $_SESSION['profissional_id'] = $prof['id'];
                $_SESSION['profissional_nome'] = $prof['nome'];
                $_SESSION['profissional_logo'] = $prof['foto'];
                $_SESSION['usuario_tipo'] = 'profissional';

                header("Location: " . (!empty($redirect) ? $redirect : "/profissional/dashboard"));
                exit;
            }

            // 🔹 Login Administrador
            $admin = $adminModel->login($email, $senha);
            // var_dump($admin); exit;
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nome'] = $admin['nome'];
                $_SESSION['usuario_tipo'] = 'admin';

                header("Location: /admin/dashboard");
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
}
