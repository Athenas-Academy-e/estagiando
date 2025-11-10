<?php
require_once __DIR__ . '/../Core/Database.php';

class Admin
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /** Login do administrador */
    public function login($email, $senha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = :email AND status = 'S'");
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($senha, $admin['senha'])) {
            return $admin;
        }

        return false;
    }

    /** Retorna todos os administradores */
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM admins ORDER BY criado_em DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Busca por e-mail */
    public function getByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Cria novo administrador */
    public function create($nome, $email, $senhaHash)
    {
        $stmt = $this->pdo->prepare("INSERT INTO admins (nome, email, senha, status) VALUES (:nome, :email, :senha, 'S')");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash
        ]);
    }

    /** Desativa administrador */
    public function disable($id)
    {
        $stmt = $this->pdo->prepare("UPDATE admins SET status = 'N' WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /** Ativa administrador */
    public function enable($id)
    {
        $id = (int) $id;
        if ($id <= 0) return false;

        $stmt = $this->pdo->prepare("UPDATE admins SET status = 'S' WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /** Conta todos administradores ativos */
    public function countAll()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM admins WHERE status = 'S'");
        return $stmt->fetchColumn();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $nome, $email, $senhaHash)
    {
        $stmt = $this->pdo->prepare("UPDATE admins SET nome = :nome, email = :email, senha = :senha, criado_em= NOW() WHERE id = :id");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash,
            ':id' => $id
        ]);
        $linhas = $stmt->rowCount();

        return [
            'erro'     => $linhas === 0 ? 'Nenhum registro alterado.' : null,
            'sucesso'  => $linhas > 0 ? 'Atualização realizada com sucesso!' : null
        ];
    }

    public function gerarTokenRecuperacao($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = :email AND status = 'S'");
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) return false;

        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $this->pdo->prepare("UPDATE admins SET reset_token = :token, reset_expira = :expira WHERE id = :id");
        $stmt->execute([':token' => $token, ':expira' => $expira, ':id' => $admin['id']]);

        return $token;
    }

    public function redefinirSenha($token, $novaSenha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE reset_token = :token AND reset_expira > NOW()");
        $stmt->execute([':token' => $token]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) return false;

        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE admins SET senha = :senha, reset_token = NULL, reset_expira = NULL WHERE id = :id");
        return $stmt->execute([':senha' => $hash, ':id' => $admin['id']]);
    }
    
    public function existeEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM admins WHERE email = :email AND status = 'S'");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() !== false;
    }
}
