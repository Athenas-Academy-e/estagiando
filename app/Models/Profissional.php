<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/ImageProcessor.php';

class Profissional
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = Database::getInstance()->getConnection();
  }

  /**
   * Faz login de profissional.
   */
  public function login($email, $senha)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM profissionais WHERE email = :email AND status = 'S'");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
      return $user;
    }
    return false;
  }

  /**
   * Cadastra novo profissional (sem inserir município).
   */
  public function cadastrar($dados, $arquivoFoto)
  {
    try {
      // 🔐 Criptografa senha
      if (!empty($dados['senha'])) {
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
      }

      // 🧠 Busca o município
      $municipioId = null;
      if (!empty($dados['municipio_id'])) {
        $municipioId = (int)$dados['municipio_id'];
      } else {
        $stmt = $this->pdo->prepare("SELECT id FROM municipios WHERE nome = :nome AND estado = :estado LIMIT 1");
        $stmt->execute([
          ':nome' => $dados['cidade'] ?? '',
          ':estado' => $dados['estado'] ?? ''
        ]);
        $municipio = $stmt->fetch(PDO::FETCH_ASSOC);
        $municipioId = $municipio['id'] ?? null;
      }

      // 🖼️ Upload de foto (reutiliza método existente)
      $foto = null;
      if (!empty($arquivoFoto['name'])) {
        $foto = $this->uploadFoto($arquivoFoto);
      }

      // 🚦 Garante status padrão “S”
      $status = !empty($dados['status']) ? $dados['status'] : 'S';

      // 💾 Inserção
      $sql = "INSERT INTO profissionais (
              nome, cpf, sexo, nascimento, email, telefone, senha, cep, endereco,
              numero, bairro, cidade, estado, municipio_id, foto, status, data_cadastro
            ) VALUES (
              :nome, :cpf, :sexo, :nascimento, :email, :telefone, :senha, :cep, :endereco,
              :numero, :bairro, :cidade, :estado, :municipio_id, :foto, :status, NOW()
            )";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':nome' => $dados['nome'] ?? '',
        ':cpf' => $dados['cpf'] ?? '',
        ':sexo' => $dados['sexo'] ?? '',
        ':nascimento' => $dados['nascimento'] ?? null,
        ':email' => $dados['email'] ?? '',
        ':telefone' => $dados['telefone'] ?? '',
        ':senha' => $dados['senha'] ?? '',
        ':cep' => $dados['cep'] ?? '',
        ':endereco' => $dados['endereco'] ?? '',
        ':numero' => $dados['numero'] ?? '',
        ':bairro' => $dados['bairro'] ?? '',
        ':cidade' => $dados['cidade'] ?? '',
        ':estado' => $dados['estado'] ?? '',
        ':municipio_id' => $municipioId,
        ':foto' => $foto,
        ':status' => $status
      ]);

      // 🔁 Retorna ID do novo cadastro
      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      error_log("Erro ao cadastrar profissional: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Retorna todos os profissionais (para painel/admin)
   */
  public function listar($busca = '')
  {
    $sql = "SELECT p.*, m.nome AS municipio_nome, m.estado
            FROM profissionais p
            LEFT JOIN municipios m ON p.municipio_id = m.id
            WHERE 1=1";

    $params = [];
    if (!empty($busca)) {
      $sql .= " AND p.nome LIKE :busca";
      $params[':busca'] = '%' . $busca . '%';
    }

    $sql .= " ORDER BY p.data_cadastro DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function listarById($id)
  {
    $sql = "SELECT nome, email, telefone
            FROM profissionais 
            WHERE id=:id";

    $params = [];
    $params[':id'] = $id;

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  /**
   * 🔍 Retorna um profissional pelo ID
   */
  public function getById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM profissionais WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  /**
   * 📊 Conta o total de profissionais cadastrados
   */
  public function countAll()
  {
    $stmt = $this->pdo->query("SELECT COUNT(*) FROM profissionais WHERE `status` = 'S'");
    return (int)$stmt->fetchColumn();
  }

  /**
   * 🧩 Atualiza os dados básicos do profissional
   */
  public function updateProfissional($id, $dados)
  {
    $campos = [];
    $params = [':id' => $id];

    foreach ($dados as $coluna => $valor) {
      $campos[] = "$coluna = :$coluna";
      $params[":$coluna"] = $valor;
    }

    $sql = "UPDATE profissionais SET " . implode(", ", $campos) . ", atualizado_em = NOW() WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
  }

  /**
   * 🖼️ Upload de foto de perfil
   */
  public function uploadFoto($arquivo)
  {
    if (empty($arquivo['name']) || $arquivo['error'] !== UPLOAD_ERR_OK) {
      return null;
    }

    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extensao, $permitidas)) {
      return null;
    }

    $pastaDestino = __DIR__ . '/../../public_html/assets/img/fotos/';
    if (!is_dir($pastaDestino)) {
      mkdir($pastaDestino, 0777, true);
    }

    $novoNome = uniqid('foto_') . '.' . $extensao;
    $caminhoCompleto = $pastaDestino . $novoNome;

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
      // Caminho acessível via web
      return '/assets/img/fotos/' . $novoNome;
    }

    return null;
  }

  /**
   * 🔄 Atualiza apenas a foto do profissional
   */
  public function updateFoto($id, $fotoPath)
  {
    $stmt = $this->pdo->prepare("UPDATE profissionais SET foto = :foto, atualizado_em = NOW() WHERE id = :id");
    $stmt->execute([':foto' => $fotoPath, ':id' => $id]);
  }

  /**
   * 🏙️ Lista os municípios cadastrados (para dropdown)
   */
  public function getLocalidades()
  {
    $stmt = $this->pdo->query("SELECT id, nome, estado FROM municipios ORDER BY nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getAll()
  {
    $stmt = $this->pdo->query("SELECT id, nome, cpf, email, telefone, `status`, data_cadastro FROM profissionais ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function toggleStatus($id)
  {
    // 1️⃣ Busca o status atual
    $stmt = $this->pdo->prepare("SELECT status FROM profissionais WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $atual = $stmt->fetchColumn();

    if ($atual === false || $atual === null) {
      error_log("⚠️ toggleStatus: ID {$id} não encontrado ou status nulo");
      return false;
    }

    // 2️⃣ Garante valor válido ('S' ou 'N')
    $atual = strtoupper(trim($atual));
    $novo = ($atual === 'S') ? 'N' : 'S';

    // 3️⃣ Executa a atualização
    $update = $this->pdo->prepare("
        UPDATE profissionais 
        SET status = :novo, atualizado_em = NOW() 
        WHERE id = :id
    ");

    try {
      $update->execute([':novo' => $novo, ':id' => $id]);
      return $novo;
    } catch (PDOException $e) {
      error_log("❌ Erro ao atualizar status (ID {$id}): " . $e->getMessage());
      return false;
    }
  }

  public function updateProfissionalAdmin($id, $data, $file = null)
  {
    // Mapeia nomes diferentes
    $map = [
      'nome'         => 'nome',
      'cpf'          => 'cpf',
      'sexo'         => 'sexo',
      'nascimento'   => 'nascimento',
      'email'        => 'email',
      'telefone'     => 'telefone',
      'cep'          => 'cep',
      'endereco'     => 'endereco',
      'numero'       => 'numero',
      'bairro'       => 'bairro',
      'cidade'       => 'cidade',
      'estado'       => 'estado',
    ];

    foreach ($map as $formKey => $dbCol) {
      if (isset($data[$formKey])) {
        if ($formKey !== $dbCol) {
          $data[$dbCol] = $data[$formKey];
          unset($data[$formKey]);
        }
      }
    }

    // Upload da foto
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
      $uploadDir = '/assets/img/fotos/';
      $destDir = __DIR__ . '/../../public_html' . $uploadDir;

      if (!is_dir($destDir)) mkdir($destDir, 0777, true);

      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      $fileName = 'foto_' . uniqid() . '.' . $ext;
      $filePath = $destDir . $fileName;

      if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $data['foto'] = $uploadDir . $fileName;
      }
    }

    $cols = [];
    foreach ($data as $key => $value) {
      if (!is_string($key) || $key === '' || $value === '' || $value === null) continue;
      $cols[] = "$key = :$key";
    }

    // 🧠 Verifica se há algo para atualizar
    if (empty($cols)) {
      error_log("⚠️ Nenhum campo enviado para atualização em profissional ID $id");
      return false;
    }

    $sql = "UPDATE profissionais SET " . implode(', ', $cols) . ", atualizado_em = NOW() WHERE id = :id";
    error_log("SQL GERADO: " . $sql);
    error_log("DATA: " . print_r($data, true));
    $stmt = $this->pdo->prepare($sql);
    $data['id'] = $id;

    try {
      return $stmt->execute($data);
    } catch (PDOException $e) {
      error_log("Erro ao atualizar profissional: " . $e->getMessage());
      return false;
    }
  }

  public function getProfissionalDetalhado($id)
  {
    $sql = "SELECT p.nome, p.cpf, p.sexo, p.nascimento, p.email, p.telefone, p.cep, p.endereco, p.numero, p.bairro, p.cidade, p.estado, p.foto
            FROM profissionais p
            LEFT JOIN municipios c ON c.id = p.municipio_id
            WHERE p.id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function existeEmail($email)
  {
    $stmt = $this->pdo->prepare("SELECT id FROM profissionais WHERE email = :email AND status = 'S'");
    $stmt->execute([':email' => $email]);
    return $stmt->fetchColumn() !== false;
  }
}
