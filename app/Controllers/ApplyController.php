<?php
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Core/Database.php';

class ApplyController
{
    public function index()
    {
        $pageTitle = "Estagiando - Aplicar para Vaga";
        session_start();
        $jobId = $_GET['id'] ?? null;

        // ✅ Verifica se veio o ID da vaga
        if (!$jobId) {
            header("Location: /vagas");
            exit;
        }

        // ✅ Verifica login antes de prosseguir
        if (empty($_SESSION['profissional_id'])) {
            header("Location: /login?redirect=/apply?id={$jobId}");
            exit;
        }

        $jobModel = new Job();
        $vaga = $jobModel->getById($jobId);
        $profissionaisModel = new Profissional();
        $profissional = $profissionaisModel->listarById($_SESSION['profissional_id']);

        // ✅ Verifica se a vaga existe
        if (!$vaga) {
            http_response_code(404);
            echo "Vaga não encontrada 😕";
            exit;
        }

        // ✅ Se o formulário foi enviado, processa candidatura
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store($jobId);
            return;
        }

        // ✅ Renderiza as views
        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/apply.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function store($jobId)
    {
        session_start();

        if (empty($_SESSION['profissional_id'])) {
            header("Location: /login?redirect=/apply?id={$jobId}");
            exit;
        }

        $jobModel = new Job();

        // ✅ Sanitiza e coleta os dados
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $mensagem = trim($_POST['mensagem'] ?? '');
        $cvPath = $jobModel->uploadCurriculo($_FILES['curriculo'] ?? []);

        $profissionalId = $_SESSION['profissional_id'];

        // ✅ Registra candidatura
        $jobModel->applyToJob(
            $jobId,
            $nome,
            $email,
            $telefone,
            $mensagem,
            $cvPath,
            $profissionalId
        );

        // ✅ Redireciona com mensagem de sucesso
        header("Location: /apply?id={$jobId}&success=1");
        exit;
    }
}
