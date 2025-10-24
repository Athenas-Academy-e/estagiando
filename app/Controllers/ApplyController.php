<?php
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Core/Database.php';

class ApplyController
{
    public function index()
    {
        $jobModel = new Job();

        // Se não veio ID, redireciona
        if (!isset($_GET['id'])) {
            header("Location: /vagas");
            exit;
        }

        $vaga = $jobModel->getById($_GET['id']);
        if (!$vaga) {
            http_response_code(404);
            echo "Vaga não encontrada.";
            exit;
        }

        // Se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store($_GET['id']);
            return;
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/apply.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function store($jobId)
    {
        session_start();
        if ($_SESSION['profissional_id'] ?? false == false) {
            header("Location: /login?redirect=/apply?id=$jobId");
            exit;
        }
        $jobModel = new Job();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $mensagem = trim($_POST['mensagem'] ?? '');
        $cvPath = $jobModel->uploadCurriculo($_FILES['curriculo'] ?? []);

        // 🔐 Verifica se o profissional está logado
        $profissionalId = $_SESSION['profissional_id'] ?? null;

        $jobModel->applyToJob(
            $jobId,
            $nome,
            $email,
            $telefone,
            $mensagem,
            $cvPath,
            $profissionalId
        );

        header("Location: /apply?success=1&id=$jobId");
        exit;
    }
}
