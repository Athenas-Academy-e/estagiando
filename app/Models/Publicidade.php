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

    public function updatePublicidade($id, $data, $file = null)
    {
        $map = [
            'titulo' => 'titulo',
            'link'   => 'url',
            'empresa' => 'empresa_id',
        ];

        foreach ($map as $formKey => $dbCol) {
            if (isset($data[$formKey])) {
                if ($formKey !== $dbCol) {
                    $data[$dbCol] = $data[$formKey];
                    unset($data[$formKey]);
                }
            }
        }

        // Upload da imagem
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/assets/img/pubs/';
            $destDir = __DIR__ . '/../../public_html' . $uploadDir;

            if (!is_dir($destDir)) mkdir($destDir, 0777, true);

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $fileName = 'pub_' . uniqid() . '.' . $ext;
            $filePath = $destDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $data['imagem'] = $uploadDir . $fileName;
            }
        }

        $cols = [];
        foreach ($data as $key => $value) {
            $cols[] = "$key = :$key";
        }

        $sql = "UPDATE publicidades SET " . implode(', ', $cols) . ", data_publicacao = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar publicidade: " . $e->getMessage());
            return false;
        }
    }

    public function getPublicidadeDetalhada($id)
    {
        $stmt = $this->pdo->prepare("SELECT nome, path AS caminho, site FROM publicidades WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createPublicidade($data, $file = null)
    {
        $nome = trim($data['nome'] ?? '');
        $site = trim($data['site'] ?? '');
        $status = $data['status'] ?? 'S';
        $path = null;

        if (!$file || empty($file['tmp_name'])) {
            throw new Exception("A imagem da publicidade é obrigatória.");
        }

        $dir = __DIR__ . '/../../public_html/assets/img/pubs/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nomeArquivo = 'pub_' . uniqid() . '.' . strtolower($ext);
        $destino = $dir . $nomeArquivo;

        if (move_uploaded_file($file['tmp_name'], $destino)) {
            $path = '/assets/img/pubs/' . $nomeArquivo;
        } else {
            throw new Exception("Erro ao enviar a imagem.");
        }

        $sql = "INSERT INTO publicidades (nome, site, path, status, criado_em) 
                VALUES (:nome, :site, :path, :status, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':site' => $site,
            ':path' => $path,
            ':status' => $status
        ]);
    }
}
