<?php
require_once __DIR__ . '/../Core/Auth.php';

class LogoutController
{
    public function index()
    {
        Auth::logout();
    }
}
