<?php
require_once __DIR__ . '/../Core/Database.php';

class Job
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Lista vagas com filtros (busca, localidade, método e ordenação)
     */
    public function getAll($query = '', $location = '', $type = '', $sort = 'newest', $empresaId = null, $categoriaID = '')
    {
        $sql = "SELECT 
                    j.*,
                    e.nome_fantasia AS empresa_nome,
                    e.razao_social AS empresa_razao,
                    e.logo AS empresa_logo,
                    e.cidade AS empresa_cidade,
                    e.estado AS empresa_estado,
                    c.nome AS categoria_nome,
                    jm.id AS method_id,
                    jm.nome AS method_nome
                FROM jobs j
                LEFT JOIN empresas e ON e.id = j.company_id
                LEFT JOIN categorias c ON c.id = j.categoria_id
                LEFT JOIN jobs_method jm ON jm.id = j.method_id
                WHERE 1=1";

        $params = [];

        // 🔍 Filtro de texto
        if (!empty($query)) {
            $sql .= " AND (j.title LIKE :q OR j.description LIKE :q1)";
            $params[':q'] = "%$query%";
            $params[':q1'] = "%$query%";
        }

        // 🔍 Filtro de texto
        if (!empty($categoriaID)) {
            $sql .= " AND c.id = :cat";
            $params[':cat'] = $categoriaID;
        }


        // 📍 Filtro de localidade
        if (!empty($location)) {
            $sql .= " AND (e.cidade LIKE :loc OR e.estado LIKE :loc1)";
            $params[':loc'] = "%$location%";
            $params[':loc1'] = "%$location%";
        }

        // 💼 Filtro de método (nome)
        if (!empty($type)) {
            $sql .= " AND jm.nome LIKE :type";
            $params[':type'] = "%$type%";
        }

        // 🏢 Filtro por empresa (caso esteja logada)
        if (!empty($empresaId)) {
            $sql .= " AND e.id = :empresaId";
            $params[':empresaId'] = $empresaId;
        }

        // ⏱ Ordenação
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY j.postedAt ASC";
                break;
            default:
                $sql .= " ORDER BY j.postedAt DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna uma vaga específica por ID
     */
    public function getById($id)
    {
        $sql = "SELECT 
                    j.*,
                    e.nome_fantasia AS empresa_nome,
                    e.razao_social AS empresa_razao,
                    e.logo AS empresa_logo,
                    e.cidade AS empresa_cidade,
                    e.estado AS empresa_estado,
                    c.nome AS categoria_nome,
                    m.nome AS municipio_nome,
                    jm.id AS method_id,
                    jm.nome AS method_nome
                FROM jobs j
                LEFT JOIN empresas e ON e.id = j.company_id
                LEFT JOIN categorias c ON c.id = j.categoria_id
                LEFT JOIN municipios m ON m.id = e.municipio_id
                LEFT JOIN jobs_method jm ON jm.id = j.method_id
                WHERE j.id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $vaga = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$vaga) return null;

        $vaga['empresa_display'] = $vaga['empresa_nome'] ?? $vaga['empresa_razao'] ?? 'Empresa não informada';
        $cidade = $vaga['empresa_cidade'] ?? $vaga['municipio_nome'] ?? '';
        $estado = $vaga['empresa_estado'] ?? '';
        $vaga['local_display'] = trim("$cidade - $estado");

        return $vaga;
    }

    /**
     * Conta o total de vagas
     */
    public function countAll()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM jobs");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    /**
     * Cria uma nova vaga
     */
    public function create($dados)
    {
        $sql = "INSERT INTO jobs 
                    (title, company_id, categoria_id, location, method_id, salary, description, postedAt)
                VALUES 
                    (:title, :company_id, :categoria_id, :location, :method_id, :salary, :description, NOW())";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $dados['title'] ?? '',
            ':company_id' => $dados['company_id'] ?? null,
            ':categoria_id' => $dados['categoria_id'] ?? null,
            ':location' => $dados['location'] ?? '',
            ':method_id' => $dados['method_id'] ?? null,
            ':salary' => $dados['salary'] ?? '',
            ':description' => $dados['description'] ?? '',
        ]);
    }

    /**
     * Deleta uma vaga
     */
    public function delete($id)
    {
        $this->pdo->prepare("DELETE FROM candidaturas WHERE vaga_id = :id")
            ->execute([':id' => $id]);

        // Depois remove a vaga
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Registra uma candidatura
     */
    public function applyToJob($jobId, $nome, $email, $telefone = '', $mensagem = '', $curriculo = null, $profissionalId = null)
    {
        $sql = "INSERT INTO candidaturas (
                    vaga_id, profissional_id, nome, email, telefone, mensagem, curriculo, data_envio
                )
                VALUES (
                    :vaga_id, :profissional_id, :nome, :email, :telefone, :mensagem, :curriculo, NOW()
                )";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':vaga_id' => $jobId,
            ':profissional_id' => $profissionalId,
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':mensagem' => $mensagem,
            ':curriculo' => $curriculo
        ]);
    }

    /**
     * Upload seguro de currículo
     */
    public function uploadCurriculo($file)
    {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public_html/assets/cv/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = time() . '_' . uniqid() . '.' . $ext;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/assets/cv/' . $fileName;
        }

        return null;
    }

    /**
     * Retorna candidaturas de um profissional
     */
    public function getApplicationsByProfessional($profissionalId)
    {
        $sql = "SELECT 
                    c.*, 
                    j.title AS vaga_titulo, 
                    e.nome_fantasia AS empresa_nome
                FROM candidaturas c
                LEFT JOIN jobs j ON j.id = c.vaga_id
                LEFT JOIN empresas e ON e.id = j.company_id
                WHERE c.profissional_id = :id
                ORDER BY c.data_envio DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $profissionalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna os métodos de trabalho ativos
     */
    public function getWorkMethod()
    {
        $sql = "SELECT id, nome FROM jobs_method WHERE status = 'ativo' ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO jobs 
            (title, company_id, categoria_id, municipio_id, method_id, location, salary, description, postedAt) 
            VALUES (:title, :company_id, :categoria_id, :municipio_id, :method_id, :location, :salary, :description, CURDATE())
        ");
        $stmt->execute([
            ':title'        => $data['title'],
            ':company_id'   => $data['company_id'],
            ':categoria_id' => $data['categoria_id'],
            ':municipio_id' => $data['municipio_id'],
            ':method_id'    => $data['method_id'],
            ':location'     => $data['location'],
            ':salary'       => $data['salary'],
            ':description'  => $data['description']
        ]);
    }

    public function update($data)
    {
        $sql = "UPDATE jobs SET 
                    title = :title,
                    company_id = :company_id,
                    categoria_id = :categoria_id,
                    municipio_id = :municipio_id,
                    method_id = :method_id,
                    location = :location,
                    salary = :salary,
                    description = :description
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'           => $data['id'],
            ':title'        => $data['title'],
            ':company_id'   => $data['company_id'],
            ':categoria_id' => $data['categoria_id'],
            ':municipio_id' => $data['municipio_id'],
            ':method_id'    => $data['method_id'],
            ':location'     => $data['location'],
            ':salary'       => $data['salary'],
            ':description'  => $data['description']
        ]);
    }
    public function getAvailableAreas()
    {
        $stmt = $this->pdo->query("
        SELECT c.id, c.nome, COUNT(j.id) AS total_vagas, c.imagempath, c.status
        FROM categorias c
        JOIN jobs j ON j.categoria_id = c.id
        WHERE j.status = 'S' AND c.status = 'ativo'
        GROUP BY c.id
        HAVING total_vagas > 0
        ORDER BY total_vagas DESC
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
