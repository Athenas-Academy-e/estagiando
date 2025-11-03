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
    public function getPublicidades()
    {
        $stmt = $this->pdo->query("SELECT * FROM publicidades ORDER BY data_publicacao DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPublicidades()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM publicidades WHERE `status` = 'S'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function toggleStatus($id)
    {
        $stmt = $this->pdo->prepare("SELECT `status` FROM publicidades WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $atual = $stmt->fetchColumn();

        $novo = ($atual === 'S') ? 'N' : 'S';
        $update = $this->pdo->prepare("UPDATE publicidades SET `status` = :novo WHERE id = :id");
        $update->execute([':novo' => $novo, ':id' => $id]);

        return $novo;
    }
    public function updateGeneric($id, $data)
    {
        $columns = [];
        foreach ($data as $key => $value) {
            $columns[] = "$key = :$key";
        }
        $sql = "UPDATE publicidades SET " . implode(',', $columns) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    public function getPublicidadeDetalhada($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM publicidades WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
