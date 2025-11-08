<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $host = DB_HOST;
        $db   = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {

            // 🔹 Log interno (opcional)
            error_log("Erro de conexão ao banco: " . $e->getMessage());

            // 🔹 Exibe erro no console do navegador
            echo "<script>console.error('Erro na conexão com o banco de dados: " . addslashes($e->getMessage()) . "');</script>";

            // 🔹 Redireciona para página 500 (ajuste o caminho conforme seu projeto)
            header("Location: /500");
            exit;
        }
    }

    // Retorna instância única (Singleton)
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Retorna o objeto PDO
    public function getConnection()
    {
        return $this->pdo;
    }

    // 🔸 Testa conexão e redireciona se estiver OK
    public static function testConnection()
    {
        try {
            $pdo = self::getInstance()->getConnection();
            if ($pdo) {
                header("Location: /");
                exit;
            }
        } catch (Exception $e) {
            // Mesmo comportamento do erro de conexão
            echo "<script>console.error('Falha ao testar conexão: " . addslashes($e->getMessage()) . "');</script>";
            header("Location: /500");
            exit;
        }
    }
}
