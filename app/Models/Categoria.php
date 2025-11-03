<?php
require_once __DIR__ . '/../Core/Database.php';

class Categoria
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getCategorias()
    {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleStatus($id)
    {
        $stmt = $this->pdo->prepare("SELECT status FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $atual = $stmt->fetchColumn();

        $novo = ($atual === 'S') ? 'N' : 'S';
        $update = $this->pdo->prepare("UPDATE categorias SET status = :novo WHERE id = :id");
        $update->execute([':novo' => $novo, ':id' => $id]);

        return $novo;
    }
    public function updateGeneric($id, $data)
    {
        $columns = [];
        foreach ($data as $key => $value) {
            $columns[] = "$key = :$key";
        }
        $sql = "UPDATE categorias SET " . implode(',', $columns) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
