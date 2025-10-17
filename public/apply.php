<?php
require_once __DIR__.'/../inc/functions.php';

// Verifica se foi enviado POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cv_path = null;

    if (!$job_id || !$name || !$email) {
        die('Dados incompletos.');
    }

    // Upload de CV
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $target = __DIR__ . '/../uploads/' . $filename;
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $target)) {
            $cv_path = 'uploads/' . $filename;
        }
    }

    applyJob($job_id, $name, $email, $cv_path);

    header('Location: index.php?success=1');
    exit;
}
