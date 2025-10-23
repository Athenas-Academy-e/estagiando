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
   * Lista vagas com filtros (busca, localidade, tipo e ordenação)
   */
  public function getAll($query = '', $location = '', $type = '', $sort = 'newest')
    {
        $sql = "SELECT 
                    j.*,
                    e.nome_fantasia AS empresa_nome,
                    e.razao_social AS empresa_razao,
                    e.logo AS empresa_logo,
                    e.cidade AS empresa_cidade,
                    e.estado AS empresa_estado,
                    c.nome AS categoria_nome
                FROM jobs j
                LEFT JOIN empresas e ON e.id = j.company_id
                LEFT JOIN categorias c ON c.id = j.categoria_id
                WHERE 1=1";

        $params = [];

        if (!empty($query)) {
            $sql .= " AND (j.title LIKE :q OR j.description LIKE :q1)";
            $params[':q'] = "%$query%";
            $params[':q1'] = "%$query%";
        }

        if (!empty($location)) {
            $sql .= " AND (e.cidade LIKE :loc OR e.estado LIKE :loc1)";
            $params[':loc'] = "%$location%";
            $params[':loc1'] = "%$location%";
        }

        if (!empty($type)) {
            $sql .= " AND j.type = :type";
            $params[':type'] = $type;
        }

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
     * 🔹 Retorna uma vaga específica por ID
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
                    m.nome AS municipio_nome
                FROM jobs j
                LEFT JOIN empresas e ON e.id = j.company_id
                LEFT JOIN categorias c ON c.id = j.categoria_id
                LEFT JOIN municipios m ON m.id = e.municipio_id
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
     * Conta o total de vagas (para dashboard / home)
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
                    (title, company_id, categoria_id, location, type, salary, description, postedAt)
                    VALUES 
                    (:title, :company_id, :categoria_id, :location, :type, :salary, :description, NOW())";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $dados['title'] ?? '',
            ':company_id' => $dados['company_id'] ?? null,
            ':categoria_id' => $dados['categoria_id'] ?? null,
            ':location' => $dados['location'] ?? '',
            ':type' => $dados['type'] ?? '',
            ':salary' => $dados['salary'] ?? '',
            ':description' => $dados['description'] ?? '',
        ]);
    }

    /**
     * Deleta uma vaga pelo ID
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
