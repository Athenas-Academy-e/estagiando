<?php
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Core/Mailer.php';
require_once __DIR__ . '/../Emails/EmailTemplate.php';


class RecuperacaoController
{
    /**
     * Página "Esqueci minha senha"
     */

    private function resolverModel(string $tipo)
    {
        return match ($tipo) {
            'empresa' => new Empresa(),
            'admin' => new Admin(),
            default => new Profissional(),
        };
    }


    public function esqueci()
    {
        $mensagem = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);

            $empresa = new Empresa();
            $prof    = new Profissional();
            $admin   = new Admin();

            if ($empresa->existeEmail($email)) {
                $tipo = 'empresa';
                $model = $empresa;
            } elseif ($prof->existeEmail($email)) {
                $tipo = 'profissional';
                $model = $prof;
            } elseif ($admin->existeEmail($email)) {
                $tipo = 'admin';
                $model = $admin;
            } else {
                $mensagem = "❌ E-mail não encontrado em nosso sistema.";
                goto view;
            }

            $token = $model->gerarTokenRecuperacao($email);
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

            if ($token) {
                $link = $protocol . '://' . $_SERVER['HTTP_HOST'] . "/redefinir-senha?token={$token}&tipo={$tipo}";

                $html = EmailTemplate::render('recuperacao_senha', [
                    'link' => $link
                ]);

                Mailer::enviar(
                    $email,
                    'Recuperação de Senha - Estagiando',
                    $html
                );

                $mensagem = "✅ Um link de redefinição foi enviado para seu e-mail.";
            } else {
                $mensagem = "❌ Não foi possível gerar o link de redefinição.";
            }
        }

        view:
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
        $tipo  = $_GET['tipo'] ?? '';
        $erro  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nova = $_POST['senha'];
            $tipo = $_POST['tipo'];

            $model = $this->resolverModel($tipo);

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
