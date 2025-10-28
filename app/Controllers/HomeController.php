<?php
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Publicidade.php';

class HomeController
{
    public function index()
    {
        $jobModel = new Job();
        $empresaModel = new Empresa();
        $publicidadeModel = new Publicidade();
        $publicidades = $publicidadeModel->getAtivas();
        $areas = $jobModel->getAvailableAreas();

        // Obtém totais para exibir na home
        $totalVagas = $jobModel->countAll();
        $totalEmpresas = $empresaModel->countAll();

        // Inclui as views
        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/home.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
}
