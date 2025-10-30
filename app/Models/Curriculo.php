<?php
require_once __DIR__ . '/../Core/Database.php';

class Curriculo
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = Database::getInstance()->getConnection();
  }

  /**
   * Salva ou atualiza o currículo do profissional.
   */
  public function salvar($profissional_id, $dados)
  {
    $stmt = $this->pdo->prepare("SELECT id FROM curriculos WHERE profissional_id = :id");
    $stmt->execute([':id' => $profissional_id]);
    $curriculo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($curriculo) {
      // Atualiza
      $sql = "UPDATE curriculos SET nome = :nome,
      resumo = :resumo, experiencia = :experiencia,
      formacao = :formacao, cursos = :cursos,
      habilidades = :habilidades,adicionais = :adicionais,
      atualizado_em = NOW() WHERE profissional_id = :id";
    } else {
      // Insere
      $sql = "INSERT INTO curriculos (profissional_id, nome, resumo, experiencia, formacao, cursos, habilidades,adicionais, criado_em, atualizado_em)
              VALUES (:id, :nome, :resumo, :experiencia, :formacao, :cursos, :habilidades, :adicionais, NOW(), NOW())";
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      ':id' => $profissional_id,
      ':nome' => trim($dados['nome']),
      ':resumo' => trim($dados['resumo']),
      ':experiencia' => trim($dados['experiencia']),
      ':formacao' => trim($dados['formacao']),
      ':cursos' => trim($dados['cursos']),
      ':habilidades' => trim($dados['habilidades']),
      ':adicionais' => trim($dados['adicionais'])
    ]);
  }

  public function buscar($profissional_id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM curriculos WHERE profissional_id = :id");
    $stmt->execute([':id' => $profissional_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function buscarprofissional($profissional_id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM profissionais WHERE id = :id");
    $stmt->execute([':id' => $profissional_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
