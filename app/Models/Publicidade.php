<?php
require_once __DIR__ . '/../Core/Database.php';

class Publicidade
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAtivas()
    {
        $sql = "SELECT id, nome, path, site
                FROM publicidades
                WHERE status = 'S'
                AND (data_expiracao IS NULL OR data_expiracao >= NOW())
                ORDER BY data_publicacao DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
