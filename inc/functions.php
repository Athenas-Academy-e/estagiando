<?php
require_once __DIR__ . '/database.php';

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

    // 🔍 Busca por texto (usa placeholders diferentes!)
    if (!empty($query)) {
        $sql .= " AND (j.title LIKE :q1 OR e.nome LIKE :q2 OR j.description LIKE :q3 OR c.nome LIKE :q4)";
        $params[':q1'] = "%{$query}%";
        $params[':q2'] = "%{$query}%";
        $params[':q3'] = "%{$query}%";
        $params[':q4'] = "%{$query}%";
    }

    // Filtro de localidade
    if (!empty($location)) {
        $sql .= " AND j.location = :loc";
        $params[':loc'] = $location;
    }

    // Filtro de tipo
    if (!empty($type)) {
        $sql .= " AND j.type = :type";
        $params[':type'] = $type;
    }

    // Ordenação
    $sql .= ($sort === 'oldest')
        ? " ORDER BY j.postedAt ASC"
        : " ORDER BY j.postedAt DESC";

    // Prepara e executa
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

/**
 * Realiza login seguro de uma empresa
 */
function loginEmpresa($email, $senha)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM empresas WHERE email = ? AND status = 'ativo'");
    $stmt->execute([strtolower($email)]);
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empresa && password_verify($senha, $empresa['senha_hash'])) {
        $_SESSION['empresa_id'] = $empresa['id'];
        $_SESSION['empresa_nome'] = $empresa['nome'];
        return ['success' => true];
    }

    return ['success' => false, 'message' => 'E-mail ou senha incorretos.'];
}

/**
 * Cadastra uma nova empresa (nível de acesso 2)
 */
function cadastrarEmpresa($dados, $arquivo = null)
{
    global $pdo;

    $uploadDir = __DIR__ . '/../assets/img/empresas/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $logoPath = null;
    if (!empty($arquivo['name'])) {
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('empresa_') . '.' . $ext;
        $target = $uploadDir . $fileName;

        if (move_uploaded_file($arquivo['tmp_name'], $target)) {
            $logoPath = 'assets/img/empresas/' . $fileName;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO empresas 
        (nome, cnpj, telefone, email, senha_hash, nivel_de_acesso, status, data_criacao, logo)
        VALUES 
        (:nome, :cnpj, :telefone, :email, :senha, 2, 'ativo', NOW(), :logo)
    ");

    return $stmt->execute([
        ':nome' => $dados['nome'],
        ':cnpj' => $dados['cnpj'],
        ':telefone' => $dados['telefone'],
        ':email' => $dados['email'],
        ':senha' => password_hash($dados['senha'], PASSWORD_BCRYPT),
        ':logo' => $logoPath
    ]);
}

/**
 * Cadastra um novo profissional (nível de acesso 3)
 */
function cadastrarProfissional($dados, $arquivo = null)
{
    global $pdo;

    $uploadDir = __DIR__ . '/../assets/img/profissionais/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $fotoPath = null;
    if (!empty($arquivo['name'])) {
        $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $fileName = uniqid('prof_') . '.' . $ext;
        $target = $uploadDir . $fileName;

        if (move_uploaded_file($arquivo['tmp_name'], $target)) {
            $fotoPath = 'assets/img/profissionais/' . $fileName;
        }
    }

    // Garante que a tabela exista com campo de nível
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS profissionais (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            cpf VARCHAR(14) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            telefone VARCHAR(20),
            sexo VARCHAR(20),
            nascimento DATE,
            escolaridade VARCHAR(100),
            ocupacao VARCHAR(100),
            foto VARCHAR(255),
            nivel_de_acesso INT DEFAULT 3,
            status ENUM('ativo','inativo') DEFAULT 'ativo',
            data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    $stmt = $pdo->prepare("
        INSERT INTO profissionais 
        (nome, cpf, email, telefone, sexo, nascimento, escolaridade, ocupacao, foto, nivel_de_acesso, status, data_criacao)
        VALUES 
        (:nome, :cpf, :email, :telefone, :sexo, :nascimento, :escolaridade, :ocupacao, :foto, 3, 'ativo', NOW())
    ");

    return $stmt->execute([
        ':nome' => $dados['nome'],
        ':cpf' => $dados['cpf'],
        ':email' => $dados['email'],
        ':telefone' => $dados['telefone'],
        ':sexo' => $dados['sexo'],
        ':nascimento' => $dados['nascimento'],
        ':escolaridade' => $dados['escolaridade'],
        ':ocupacao' => $dados['ocupacao'],
        ':foto' => $fotoPath
    ]);
}
