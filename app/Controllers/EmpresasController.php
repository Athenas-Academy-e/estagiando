<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Job.php';

class EmpresasController
{
    public function index()
    {
        $empresaModel = new Empresa();

        $categorias = $empresaModel->getCategorias();
        $locais = $empresaModel->getLocalidades();

        $search = $_GET['q'] ?? '';
        $categoria = $_GET['categoria'] ?? '';
        $local = $_GET['local'] ?? '';

        $limit = 9;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $empresas = $empresaModel->listarEmpresas($search, $categoria, $local, $limit, $offset);
        $totalEmpresas = $empresaModel->countEmpresas($search, $categoria, $local);
        $totalPages = ceil($totalEmpresas / $limit);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresas/index.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function dashboard()
    {
        Auth::check('empresa');
        $empresaModel = new Empresa();
        $vagas = $empresaModel->getVagas($_SESSION['usuario_id']);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresa/dashboard.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function candidatos()
    {
        Auth::check('empresa');
        if (!isset($_GET['vaga'])) {
            header("Location: /empresa/dashboard");
            exit;
        }

        $empresaModel = new Empresa();
        $jobModel = new Job();
        $vaga = $jobModel->getById($_GET['vaga']);
        $candidatos = $empresaModel->getCandidaturas($_GET['vaga']);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresa/candidatos.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function logout()
    {
        Auth::logout();
    }
}
