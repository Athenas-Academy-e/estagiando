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
        $stmt = $this->pdo->query("SELECT id, nome, data_criacao,status FROM categorias ORDER BY nome ASC");
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
    public function updateCategoria($id, $data, $file = null)
    {
        // 🔹 Limpa e ajusta o nome da categoria
        if (isset($data['nome'])) {
            $data['nome'] = trim($data['nome']);
        }

        // 🖼️ Upload da imagem (caso enviada)
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '/assets/img/areas/';
            $destDir = __DIR__ . '/../../public_html' . $uploadDir;

            // Cria diretório se não existir
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }

            // Extensão e nome do arquivo
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $permitidas)) {
                error_log("Extensão de imagem inválida em categoria: $ext");
            } else {
                $fileName = 'cat_' . uniqid() . '.' . $ext;
                $filePath = $destDir . $fileName;

                // Move o arquivo e salva caminho relativo
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $data['imagem'] = $uploadDir . $fileName;
                }
            }
        }

        // 🔹 Monta SQL dinamicamente (para suportar atualização de imagem e nome)
        $cols = [];
        foreach ($data as $key => $value) {
            $cols[] = "$key = :$key";
        }

        $sql = "UPDATE categorias SET " . implode(', ', $cols) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        // Adiciona o ID no array de parâmetros
        $data['id'] = $id;

        try {
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT nome, imagempath As caminho_da_imagem FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategoria($data, $file = null)
    {
        $nome = trim($data['nome'] ?? '');
        $status = $data['status'] ?? 'S';
        $imagemPath = null;

        // Upload da imagem (opcional)
        if ($file && isset($file['tmp_name']) && !empty($file['tmp_name'])) {
            $dir = __DIR__ . '/../../public_html/assets/img/areas/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nomeArquivo = 'cat_' . uniqid() . '.' . strtolower($ext);
            $destino = $dir . $nomeArquivo;

            if (move_uploaded_file($file['tmp_name'], $destino)) {
                $imagemPath = '/assets/img/areas/' . $nomeArquivo;
            }
        }

        $sql = "INSERT INTO categorias (nome, imagem, status, criado_em) VALUES (:nome, :imagem, :status, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $nome,
            ':imagem' => $imagemPath,
            ':status' => $status
        ]);
    }
}
