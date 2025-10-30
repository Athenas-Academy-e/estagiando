<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Curriculo.php';

class ProfissionalController
{
    public function dashboard()
    {
        Auth::check('profissional');

        $jobModel = new Job();
        $curriculoModel = new Curriculo();

        $profissionalId = $_SESSION['profissional_id'];
        $mensagem = '';

        // Salva currículo
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $curriculoModel->salvar($profissionalId, $_POST);
            $mensagem = "Currículo salvo com sucesso!";
        }

        $curriculo = $curriculoModel->buscar($profissionalId);
        $candidaturas = $jobModel->getApplicationsByProfessional($profissionalId);
        $totalCandidaturas = count($jobModel->getApplicationsByProfessional($profissionalId));

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/profissional/dashboard.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function alterarLogo()
    {
        Auth::check('profissional');
        $profissionalModel = new Profissional();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profissional = $profissionalModel->getById($_SESSION['profissional_id']);
            $fotoAtual = $profissional['foto'] ?? null;

            if (isset($_POST['action']) && $_POST['action'] === 'remover') {
                // 🧹 Remove a foto antiga
                if (!empty($fotoAtual)) {
                    $arquivoAntigo = __DIR__ . '/../../public_html' . $fotoAtual;
                    if (file_exists($arquivoAntigo)) {
                        unlink($arquivoAntigo);
                    }
                    $profissionalModel->updateFoto($_SESSION['profissional_id'], null);
                    unset($_SESSION['profissional_logo']);
                }
                $success = "Foto removida com sucesso!";
            }

            if (!empty($_FILES['foto']['name'])) {
                // 🖼️ Upload da nova foto
                $novaFotoPath = $profissionalModel->uploadFoto($_FILES['foto']);

                if ($novaFotoPath) {
                    // Remove a antiga, se existir
                    if (!empty($fotoAtual)) {
                        $arquivoAntigo = __DIR__ . '/../../public_html' . $fotoAtual;
                        if (file_exists($arquivoAntigo)) {
                            unlink($arquivoAntigo);
                        }
                    }

                    $profissionalModel->updateFoto($_SESSION['profissional_id'], $novaFotoPath);
                    $_SESSION['profissional_logo'] = $novaFotoPath; // ✅ mantém o padrão
                    $success = "Foto atualizada com sucesso!";
                } else {
                    $error = "Erro ao enviar a imagem. Tente outra foto.";
                }
            }
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/profissional/alterarlogo.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    /**
     * ✏️ Editar dados do perfil do profissional
     */
    public function editarPerfil()
    {
        Auth::check('profissional');

        $profissionalModel = new Profissional();
        $profissional = $profissionalModel->getById($_SESSION['profissional_id']);

        if (!$profissional) {
            die("Profissional não encontrado.");
        }

        $municipios = $profissionalModel->getLocalidades();
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $cpf = trim($_POST['cpf'] ?? '');
            $municipio_id = (int)($_POST['municipio_id'] ?? 0);

            if (!$nome || !$email) {
                $error = "Nome e Email são obrigatórios!";
            } else {
                $profissionalModel->updateProfissional($_SESSION['profissional_id'], [
                    'nome' => $nome,
                    'email' => $email,
                    'telefone' => $telefone,
                    'cpf' => $cpf,
                    'cep' => $profissional['cep'],
                    'endereco' => $profissional['endereco'],
                    'numero' => $profissional['numero'],
                    'bairro' => $profissional['bairro'],
                    'estado' => $profissional['estado'],
                    'cidade' => $profissional['cidade'],
                    'municipio_id' => $municipio_id
                ]);

                $_SESSION['profissional_nome'] = $nome;
                $success = "Dados atualizados com sucesso! ✅";

                // Atualiza foto se enviada
                if (!empty($_FILES['foto']['name'])) {
                    $novaFotoPath = $profissionalModel->uploadFoto($_FILES['foto']);
                    if ($novaFotoPath) {
                        $profissionalModel->updateFoto($_SESSION['profissional_id'], $novaFotoPath);
                        $_SESSION['profissional_foto'] = $novaFotoPath;
                    }
                }

                // Recarrega dados
                $profissional = $profissionalModel->getById($_SESSION['profissional_id']);

                // 📜 LOG DETALHADO
                $logDir = __DIR__ . '/../../logs/';
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0777, true);
                }

                $logFile = $logDir . 'profissionais_updates.log';
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP não identificado';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Navegador desconhecido';

                $logMsg = "[" . date('d/m/Y H:i:s') . "] "
                    . "Profissional ID: {$_SESSION['profissional_id']} | "
                    . "Nome: {$nome} | "
                    . "IP: {$ip} | "
                    . "Navegador: {$userAgent}" . PHP_EOL;

                file_put_contents($logFile, $logMsg, FILE_APPEND);
            }
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/profissional/editarperfil.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
}
