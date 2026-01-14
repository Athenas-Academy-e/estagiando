<?php
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Mailer.php';
require_once __DIR__ . '/../Emails/EmailTemplate.php';

class CadastroController
{
    public function index()
    {
        $pageTitle = "Estagiando - Cadastro";
        $success = $error = '';

        // 🔹 Carrega categorias para o select de empresas
        $categoriaModel = new Empresa();
        $categorias = $categoriaModel->getCategorias();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'] ?? '';

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $link = $protocol . '://' . $_SERVER['HTTP_HOST'] . "/login";

            // === Cadastro de Empresa ===
            if ($tipo === 'empresa') {
                try {
                    $empresa = new Empresa();
                    $resultado = $empresa->cadastrar($_POST, $_FILES['logo'] ?? null);

                    if ($resultado) {
                        $html = EmailTemplate::render('boas_vindas', [
                            'nome' => $_POST['nome_empresa'] ?? 'Empresa',
                            'tipo' => 'Empresa',
                            'link' => $link
                        ]);

                        Mailer::enviar(
                            $_POST['email'],
                            'Bem-vindo ao Estagiando!',
                            $html
                        );

                        $success = "✅ Empresa cadastrada com sucesso!";
                    } else {
                        $error = "❌ Erro ao cadastrar empresa. Verifique os campos e tente novamente.";
                    }
                } catch (Exception $e) {
                    $error = "❌ Erro ao cadastrar empresa: " . $e->getMessage();
                }
            }

            // === Cadastro de Profissional ===
            if ($tipo === 'profissional') {
                try {
                    $profissional = new Profissional();
                    $resultado = $profissional->cadastrar($_POST, $_FILES['foto'] ?? null);

                    if ($resultado) {
                        $html = EmailTemplate::render('boas_vindas', [
                            'nome' => $_POST['nome'] ?? 'Profissional',
                            'tipo' => 'Profissional',
                            'link' => $link
                        ]);

                        Mailer::enviar(
                            $_POST['email'],
                            'Bem-vindo ao Estagiando!',
                            $html
                        );

                        $success = "✅ Profissional cadastrado com sucesso!";
                    } else {
                        $error = "❌ Erro ao cadastrar profissional. Verifique os campos e tente novamente.";
                    }
                } catch (Exception $e) {
                    $error = "❌ Erro ao cadastrar profissional: " . $e->getMessage();
                }
            }
        }

        // === Renderiza as Views (estrutura MVC com Partials) ===
        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/cadastro/index.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
}
