<?php
require_once __DIR__ . '/../Core/Database.php';

class Empresa
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = Database::getInstance()->getConnection();
  }

  /**
   * 🔐 Login seguro da empresa
   */
  public function login($email, $senha)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE email = :email AND status = 'S'");
    $stmt->execute([':email' => $email]);
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empresa && password_verify($senha, $empresa['senha'])) {
      return $empresa;
    }

    return false;
  }

  /**
   * 📊 Contagem de candidaturas (geral)
   */
  public function countAll()
  {
    $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM candidaturas");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
  }

  /**
   * 📋 Retorna as vagas da empresa
   */
  public function getVagas($empresa_id)
  {
    $sql = "SELECT j.*, 
                   (SELECT COUNT(*) FROM candidaturas c WHERE c.vaga_id = j.id) AS total_candidatos
            FROM jobs j
            WHERE j.company_id = :empresa_id
            ORDER BY j.postedAt DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':empresa_id' => $empresa_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 📬 Retorna candidaturas de uma vaga
   */
  public function getCandidaturas($vaga_id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM candidaturas WHERE vaga_id = :vaga_id ORDER BY data_envio DESC");
    $stmt->execute([':vaga_id' => $vaga_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 📂 Lista de categorias
   */
  public function getCategorias()
  {
    $stmt = $this->pdo->query("SELECT id, nome FROM categorias ORDER BY nome");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 🏢 Cadastro de empresa com logo, hash de senha e vínculo com município
   */
  public function cadastrar($dados, $arquivoLogo)
  {
    try {
      // 🔒 Criptografa a senha
      if (!empty($dados['senha'])) {
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
      }

      // 📁 Upload da logo (opcional)
      $logo = null;
      if (!empty($arquivoLogo['name'])) {
        $ext = pathinfo($arquivoLogo['name'], PATHINFO_EXTENSION);
        $logo = uniqid('logo_') . '.' . $ext;
        $destino = __DIR__ . '/../../public/assets/logos/' . $logo;
        if (!is_dir(dirname($destino))) mkdir(dirname($destino), 0777, true);
        move_uploaded_file($arquivoLogo['tmp_name'], $destino);
      }

      // 🏙️ Busca ou cria município
      $stmt = $this->pdo->prepare("SELECT id FROM municipios WHERE nome = :nome AND estado = :estado LIMIT 1");
      $stmt->execute([
        ':nome' => $dados['cidade'] ?? '',
        ':estado' => $dados['estado'] ?? ''
      ]);
      $municipio = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$municipio) {
        $insert = $this->pdo->prepare("INSERT INTO municipios (nome, estado) VALUES (:nome, :estado)");
        $insert->execute([
          ':nome' => $dados['cidade'] ?? '',
          ':estado' => $dados['estado'] ?? ''
        ]);
        $municipioId = $this->pdo->lastInsertId();
      } else {
        $municipioId = $municipio['id'];
      }

      // 🧾 Inserção da empresa
      $sql = "INSERT INTO empresas (
                    razao_social,
                    nome_fantasia,
                    categoria_id,
                    cnpj,
                    telefone,
                    celular,
                    email,
                    site,
                    senha,
                    cep,
                    endereco,
                    numero,
                    bairro,
                    estado,
                    cidade,
                    municipio_id,
                    logo,
                    data_criacao
                ) VALUES (
                    :razao_social,
                    :nome_fantasia,
                    :categoria,
                    :cnpj,
                    :telefone,
                    :celular,
                    :email,
                    :site,
                    :senha,
                    :cep,
                    :endereco,
                    :numero,
                    :bairro,
                    :estado,
                    :cidade,
                    :municipio_id,
                    :logo,
                    NOW()
                )";

      $stmt = $this->pdo->prepare($sql);

      $stmt->execute([
        ':razao_social' => $dados['razao_social'] ?? '',
        ':nome_fantasia' => $dados['nome_fantasia'] ?? '',
        ':categoria' => $dados['categoria'] ?? null,
        ':cnpj' => $dados['cnpj'] ?? '',
        ':telefone' => $dados['telefone1'] ?? '',
        ':celular' => $dados['celular'] ?? '',
        ':email' => $dados['email'] ?? '',
        ':site' => $dados['site'] ?? '',
        ':senha' => $dados['senha'] ?? '',
        ':cep' => $dados['cep'] ?? '',
        ':endereco' => $dados['endereco'] ?? '',
        ':numero' => $dados['numero'] ?? '',
        ':bairro' => $dados['bairro'] ?? '',
        ':estado' => $dados['estado'] ?? '',
        ':cidade' => $dados['cidade'] ?? '',
        ':municipio_id' => $municipioId,
        ':logo' => $logo
      ]);

      return true;
    } catch (PDOException $e) {
      die("<b>Erro ao cadastrar empresa:</b> " . $e->getMessage());
    }
  }

  /**
   * 🖼️ Atualiza a logo da empresa
   */
  private function uploadLogo($arquivo, $empresaId)
  {
    $dir = __DIR__ . '/../../public/assets/logos/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $nomeArquivo = time() . '_' . basename($arquivo['name']);
    $destino = $dir . $nomeArquivo;

    if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
      $stmt = $this->pdo->prepare("UPDATE empresas SET logo = :logo WHERE id = :id");
      $stmt->execute([':logo' => '/assets/logos/' . $nomeArquivo, ':id' => $empresaId]);
    }
  }

  /**
   * 🔢 Valida CNPJ
   */
  private function validarCNPJ($cnpj)
  {
    $cnpj = preg_replace('/\D/', '', $cnpj);
    if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) return false;

    for ($t = 12; $t < 14; $t++) {
      $d = 0;
      for ($c = 0, $p = $t - 7; $c < $t; $c++) {
        $d += $cnpj[$c] * $p--;
        if ($p < 2) $p = 9;
      }
      $d = ((10 * $d) % 11) % 10;
      if ($cnpj[$c] != $d) return false;
    }
    return true;
  }

  /**
   * 🗺️ Lista de municípios disponíveis
   */
  public function getLocalidades()
  {
    $stmt = $this->pdo->query("SELECT id, nome, estado FROM municipios ORDER BY nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 📃 Lista de empresas com total de vagas e localidade vinculada
   */
  public function listarEmpresas($search = '', $categoria = '', $local = '', $limit = 9, $offset = 0)
  {
    try {
      $sql = "SELECT 
                    e.id,
                    e.razao_social,
                    e.nome_fantasia,
                    e.email,
                    e.site,
                    e.logo,
                    e.telefone,
                    e.celular,
                    e.cidade,
                    e.estado,
                    c.nome AS categoria_nome,
                    m.nome AS municipio_nome,
                    m.estado AS municipio_estado,
                    COUNT(j.id) AS total_vagas
                FROM empresas e
                LEFT JOIN categorias c ON e.categoria_id = c.id
                LEFT JOIN municipios m ON e.municipio_id = m.id
                LEFT JOIN jobs j ON j.company_id = e.id
                WHERE 1=1";

      $params = [];

      if (!empty($search)) {
        $sql .= " AND (e.razao_social LIKE :search OR e.nome_fantasia LIKE :search1)";
        $params[':search'] = '%' . $search . '%';
        $params[':search1'] = '%' . $search . '%';
      }

      if (!empty($categoria)) {
        $sql .= " AND e.categoria_id = :categoria";
        $params[':categoria'] = $categoria;
      }

      if (!empty($local)) {
        $sql .= " AND (e.cidade LIKE :local OR m.nome LIKE :local1)";
        $params[':local'] = '%' . $local . '%';
        $params[':local1'] = '%' . $local . '%';
      }

      $sql .= " GROUP BY e.id 
                  ORDER BY total_vagas DESC, e.nome_fantasia ASC
                  LIMIT :limit OFFSET :offset";

      $stmt = $this->pdo->prepare($sql);

      // 🔹 Precisa definir manualmente os tipos inteiros para LIMIT/OFFSET
      foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
      }
      $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
      $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log('Erro ao listar empresas: ' . $e->getMessage());
      return [];
    }
  }

  public function countEmpresas($search = '', $categoria = '', $local = '')
  {
    try {
      $sql = "SELECT COUNT(DISTINCT e.id) AS total
                FROM empresas e
                LEFT JOIN categorias c ON e.categoria_id = c.id
                LEFT JOIN municipios m ON e.municipio_id = m.id
                WHERE 1=1";
      $params = [];

      if (!empty($search)) {
        $sql .= " AND (e.razao_social LIKE :search OR e.nome_fantasia LIKE :search)";
        $params[':search'] = '%' . $search . '%';
      }

      if (!empty($categoria)) {
        $sql .= " AND e.categoria_id = :categoria";
        $params[':categoria'] = $categoria;
      }

      if (!empty($local)) {
        $sql .= " AND (e.cidade LIKE :local OR m.nome LIKE :local)";
        $params[':local'] = '%' . $local . '%';
      }

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return (int)$result['total'];
    } catch (PDOException $e) {
      error_log('Erro ao contar empresas: ' . $e->getMessage());
      return 0;
    }
  }
  public function getById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getMunicipioById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM municipios WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
