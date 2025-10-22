<?php
require_once __DIR__.'/../inc/functions.php';

$id = $_GET['id'] ?? null;
if ($id) {
    deleteJob($id);
}

header('Location: index.php');
exit;
