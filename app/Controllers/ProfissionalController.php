<?php
require_once __DIR__ . '/../Core/Auth.php';

class ProfissionalController
{
    public function dashboard()
    {
        Auth::check('profissional'); // ✅ Protegido automaticamente

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
