<?php
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Core/Mailer.php';

class RecuperacaoController
{
    /**
     * Página "Esqueci minha senha"
     */
    public function esqueci()
    {
        $mensagem = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);

            // 🔎 Detecta automaticamente o tipo de usuário
            $empresaModel = new Empresa();
            $profModel = new Profissional();
            $adminModel = new Admin();

            $tipo = null;
            $model = null;

            if ($empresaModel->existeEmail($email)) {
                $tipo = 'empresa';
                $model = $empresaModel;
            } elseif ($profModel->existeEmail($email)) {
                $tipo = 'profissional';
                $model = $profModel;
            } elseif ($adminModel->existeEmail($email)) {
                $tipo = 'admin';
                $model = $adminModel;
            }

            if ($model && $tipo) {
                $token = $model->gerarTokenRecuperacao($email);
                if ($token) {
                    Mailer::enviarRecuperacao($email, $token, $tipo);
                    $mensagem = "✅ Um link de redefinição foi enviado para seu e-mail.";
                } else {
                    $mensagem = "❌ Não foi possível gerar o link de redefinição. Tente novamente.";
                }
            } else {
                $mensagem = "❌ E-mail não encontrado em nosso sistema.";
            }
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/login/esqueci_senha.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    /**
     * Página de redefinição de senha
     */
    public function redefinir()
    {
        $token = $_GET['token'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nova = $_POST['senha'];
            $tipo = $_POST['tipo'];

            switch ($tipo) {
                case 'empresa':
                    $model = new Empresa();
                    break;
                case 'admin':
                    $model = new Admin();
                    break;
                default:
                    $model = new Profissional();
            }

            if ($model->redefinirSenha($token, $nova)) {
                header("Location: /login?msg=Senha alterada com sucesso!");
                exit;
            } else {
                $erro = "❌ Token inválido ou expirado.";
            }
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/login/redefinir_senha.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
}
