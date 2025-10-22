<?php
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Empresa.php';

class HomeController
{
    public function index()
    {
        $jobModel = new Job();
        $empresaModel = new Empresa();

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
