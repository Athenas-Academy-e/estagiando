<?php
require_once __DIR__ . '/../Core/Database.php';

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
    $stmt = $this->pdo->query("SELECT * FROM profissionais ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function toggleStatus($id)
{
    $stmt = $this->pdo->prepare("SELECT status FROM profissionais WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $atual = $stmt->fetchColumn();

    $novo = ($atual === 'S') ? 'N' : 'S';
    $update = $this->pdo->prepare("UPDATE profissionais SET status = :novo WHERE id = :id");
    $update->execute([':novo' => $novo, ':id' => $id]);

    return $novo;
}
}
