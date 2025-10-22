<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/Core/Database.php';

// Função de compatibilidade (para código legado)
function getConnection() {
    return Database::getInstance()->getConnection();
}
