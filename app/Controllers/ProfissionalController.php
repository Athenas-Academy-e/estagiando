<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Job.php';

class ProfissionalController
{
    public function dashboard()
    {
        // ✅ Garante que o usuário é profissional
        Auth::check('profissional');

        $jobModel = new Job();

        // ✅ Usa a sessão correta (profissional_id)
        $candidaturas = $jobModel->getApplicationsByProfessional($_SESSION['profissional_id']);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/profissional/dashboard.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function logout()
    {
        Auth::logout();
    }
}
