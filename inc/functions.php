<?php
require_once __DIR__ . '/../config.php';

/**
 * Lista vagas com filtros (busca, localidade, tipo e ordenação)
 */
function getJobs($query = '', $location = '', $type = '', $sort = 'newest')
{
    global $pdo;

    $sql = "SELECT 
                j.*, 
                e.nome AS company, 
                c.nome AS categoria_nome
            FROM jobs j
            LEFT JOIN empresas e ON e.id = j.company_id
            LEFT JOIN categorias c ON c.id = j.categoria_id
            WHERE 1=1";
    $params = [];

    if ($query) {
        $sql .= " AND (j.title LIKE :q OR e.nome LIKE :q OR j.description LIKE :q OR c.nome LIKE :q)";
        $params[':q'] = "%$query%";
    }
    if ($location) {
        $sql .= " AND j.location = :loc";
        $params[':loc'] = $location;
    }
    if ($type) {
        $sql .= " AND j.type = :type";
        $params[':type'] = $type;
    }

    $sql .= ($sort === 'oldest')
        ? " ORDER BY j.postedAt ASC"
        : " ORDER BY j.postedAt DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retorna uma vaga pelo ID (com empresa e categoria)
 */
function getJobById($id)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            j.*, 
            e.nome AS company, 
            e.id AS company_id,
            c.nome AS categoria_nome,
            c.id AS categoria_id
        FROM jobs j
        LEFT JOIN empresas e ON e.id = j.company_id
        LEFT JOIN categorias c ON c.id = j.categoria_id
        WHERE j.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Cria ou atualiza vaga (função única)
 */
function saveJob($data)
{
    global $pdo;

    // Limpa o salário
    $salary = $data['salary'] ?? '';
    $salary = preg_replace('/[^\d,]/', '', $salary);
    $salary = str_replace(',', '.', $salary);
    $data['salary'] = $salary ?: null;

    if (!empty($data['id'])) {
        // Atualiza vaga existente
        $stmt = $pdo->prepare("
            UPDATE jobs 
            SET title=?, company_id=?, categoria_id=?, location=?, type=?, salary=?, description=? 
            WHERE id=?
        ");
        $stmt->execute([
            $data['title'],
            $data['company_id'],
            $data['categoria_id'],
            $data['location'],
            $data['type'],
            $data['salary'],
            $data['description'],
            $data['id']
        ]);
    } else {
        // Cria nova vaga
        $stmt = $pdo->prepare("
            INSERT INTO jobs (title, company_id, categoria_id, location, type, salary, description, postedAt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['title'],
            $data['company_id'],
            $data['categoria_id'],
            $data['location'],
            $data['type'],
            $data['salary'],
            $data['description'],
            date('Y-m-d')
        ]);
    }
}

/**
 * Atualiza uma vaga (usado pelo edit.php)
 */
function updateJob($data)
{
    global $pdo;

    $salary = $data['salary'] ?? '';
    $salary = preg_replace('/[^\d,]/', '', $salary);
    $salary = str_replace(',', '.', $salary);
    $data['salary'] = $salary ?: null;

    $stmt = $pdo->prepare("
        UPDATE jobs 
        SET title=?, company_id=?, categoria_id=?, location=?, type=?, salary=?, description=? 
        WHERE id=?
    ");
    $stmt->execute([
        $data['title'],
        $data['company_id'],
        $data['categoria_id'],
        $data['location'],
        $data['type'],
        $data['salary'],
        $data['description'],
        $data['id']
    ]);
}

/**
 * Exclui uma vaga
 */
function deleteJob($id)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->execute([$id]);
}

/**
 * Submete uma candidatura
 */
function applyJob($job_id, $name, $email, $cv_path = null)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO applications (job_id, name, email, cv_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$job_id, $name, $email, $cv_path]);
}

/**
 * Retorna todos os municípios
 */
function getMunicipios()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, estado FROM municipios ORDER BY estado ASC, nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retorna um município pelo ID
 */
function getMunicipioById($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, estado FROM municipios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Retorna todas as categorias
 */
function getCategorias()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, status FROM categorias ORDER BY nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retorna todos os tipos de trabalho (jobs_method)
 */
function getJobmethod()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, status FROM jobs_method ORDER BY nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retorna todas as empresas
 */
function getEmpresas()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome, status FROM empresas ORDER BY nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retorna uma empresa pelo ID
 */
function getEmpresaById($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome, status FROM empresas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
