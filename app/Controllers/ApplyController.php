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

        $vaga = $jobModel->findById($_GET['id']);
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
        require_once __DIR__ . '/inc/apply.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function store($jobId)
    {
        $pdo = Database::getInstance()->getConnection();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $mensagem = trim($_POST['mensagem'] ?? '');

        // Upload de currículo (opcional)
        $cvPath = null;
        if (!empty($_FILES['curriculo']['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/cv/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['curriculo']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['curriculo']['tmp_name'], $targetFile)) {
                $cvPath = '/assets/cv/' . $fileName;
            }
        }

        // Gravação no banco
        $sql = "INSERT INTO candidaturas (vaga_id, nome, email, telefone, mensagem, curriculo, data_envio)
                VALUES (:vaga_id, :nome, :email, :telefone, :mensagem, :curriculo, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':vaga_id' => $jobId,
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':mensagem' => $mensagem,
            ':curriculo' => $cvPath
        ]);

        // Confirmação
        header("Location: /apply?success=1&id=$jobId");
        exit;
    }
}
