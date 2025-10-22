<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Empresa.php';

class VagasController
{
    public function index()
    {
        $jobModel = new Job();
        $empresaModel = new Empresa();

        $query = $_GET['q'] ?? '';
        $location = $_GET['loc'] ?? '';
        $type = $_GET['type'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';

        $vagas = $jobModel->getAll($query, $location, $type, $sort);
        $totalVagas = count($vagas);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/vagas.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function detalhe()
    {
        if (!isset($_GET['id'])) {
            header("Location: /vagas");
            exit;
        }

        $id = (int) $_GET['id'];
        $jobModel = new Job();
        $vaga = $jobModel->getById($id);

        if (!$vaga) {
            http_response_code(404);
            echo "<h1>Vaga não encontrada</h1>";
            exit;
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/vagas/detalhe.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
}
