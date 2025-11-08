<?php
class ErrorController
{
  private function render($code, $title, $message)
  {
    http_response_code($code);
    $errorCode = $code;
    $errorTitle = $title;
    $errorMessage = $message;

    require_once __DIR__ . '/../Views/partials/head.php';
    require_once __DIR__ . '/../Views/partials/header.php';
    require_once __DIR__ . '/../Views/errors/generic.php';
    require_once __DIR__ . '/../Views/partials/footer.php';
  }

  public function notFound()
  {
    $this->render(404, "Página não encontrada", "Desculpe, não conseguimos encontrar a página que você procurava.");
  }

  public function forbidden()
  {
    $this->render(403, "Acesso negado", "Você não tem permissão para acessar esta página.");
  }

  public function unauthorized()
  {
    $this->render(401, "Não autorizado", "Você precisa fazer login para continuar.");
  }

  public function serverError()
  {
    $this->render(500, "Erro interno do servidor", "Algo deu errado em nosso lado. Tente novamente mais tarde.");
  }

  public function show($code = 400, $title = "Erro", $message = "Ocorreu um erro inesperado.")
  {
    $this->render($code, $title, $message);
  }
}
