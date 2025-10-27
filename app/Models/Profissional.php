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
      // 🔒 Criptografa a senha antes de salvar
      if (!empty($dados['senha'])) {
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
      }

      // 🧠 Busca o município existente
      $stmt = $this->pdo->prepare("SELECT id FROM municipios WHERE nome = :nome AND estado = :estado LIMIT 1");
      $stmt->execute([
        ':nome' => $dados['cidade'] ?? '',
        ':estado' => $dados['estado'] ?? ''
      ]);
      $municipio = $stmt->fetch(PDO::FETCH_ASSOC);

      // Se não encontrar, define como null
      $municipioId = $municipio['id'] ?? null;

      // 📸 Upload da foto de perfil (opcional)
      $foto = null;
      if (!empty($arquivoFoto['name'])) {
        $ext = pathinfo($arquivoFoto['name'], PATHINFO_EXTENSION);
        $foto = uniqid('foto_') . '.' . $ext;
        $destino = __DIR__ . '/../../public_html/assets/fotos/' . $foto;
        if (!is_dir(dirname($destino))) mkdir(dirname($destino), 0777, true);
        move_uploaded_file($arquivoFoto['tmp_name'], $destino);
      }

      // 🧾 Inserção do profissional
      $sql = "INSERT INTO profissionais (
                nome,
                cpf,
                sexo,
                nascimento,
                email,
                telefone,
                senha,
                cep,
                endereco,
                numero,
                bairro,
                cidade,
                estado,
                municipio_id,
                foto,
                status,
                data_cadastro
              ) VALUES (
                :nome,
                :cpf,
                :sexo,
                :nascimento,
                :email,
                :telefone,
                :senha,
                :cep,
                :endereco,
                :numero,
                :bairro,
                :cidade,
                :estado,
                :municipio_id,
                :foto,
                :status,
                NOW()
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
        ':status' => $dados['status'] ?? '',
      ]);

      return true;
    } catch (PDOException $e) {
      die("<b>Erro ao cadastrar profissional:</b> " . $e->getMessage());
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
}