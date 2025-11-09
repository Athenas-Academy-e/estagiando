<?php
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Models/Publicidade.php';

class HomeController
{
    public function index()
    {
        $jobModel = new Job();
        $empresaModel = new Empresa();
        $profissionalModel = new Profissional();
        $publicidadeModel = new Publicidade();

        $publicidades = $publicidadeModel->getAtivas();
        $areas = $jobModel->getAvailableAreas();

        // 🔹 Empresas completas (com categoria e município)
        $empresasParceiras = $empresaModel->listarComCategoriaMunicipio();

        // 🔹 Totais gerais
        $totalVagas = $jobModel->countAll();
        $totalEmpresas = $empresaModel->countAll();
        $totalProfissionais = $profissionalModel->countAll();

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/home.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
}
