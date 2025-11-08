<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Models/Job.php';

class AdminController
{
    public function index()
    {
        Auth::check('admin');
        header("Location: /admin/dashboard");
        exit;
    }
    
    // 🏠 Dashboard principal
    public function dashboard()
    {
        Auth::check('admin');

        $empresaModel = new Empresa();
        $profModel = new Profissional();
        $jobModel = new Job();
        $publicidadeModel = new Publicidade();

        $totalPublicidade = $publicidadeModel->countPublicidades();
        $totalEmpresas = $empresaModel->countEmpresas();
        $totalProfissionais = count($profModel->listar());
        $totalVagas = $jobModel->countAll();

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/admin/dashboard.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    // ➕ Criar novo
    public function criar()
    {
        Auth::check('admin');
        $adminModel = new Admin();
        $success = $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);

            if ($adminModel->getByEmail($email)) {
                $error = "⚠️ Já existe um administrador com esse e-mail.";
            } else {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $adminModel->create($nome, $email, $hash);
                $success = "✅ Administrador criado com sucesso!";
            }
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/admin/criar.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    // 👥 Listagem de administradores
    public function gerenciar()
    {
        Auth::check('admin');

        $adminModel = new Admin();
        $admins = $adminModel->getAll();

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/admin/gerenciar.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    /** ✏️ Editar administrador */
    public function editar()
    {
        Auth::check('admin');
        $adminModel = new Admin();

        // Inicializa mensagens
        $success = $error = '';

        // Valida o método e o ID recebido via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        } else {
            header("Location: /admin/gerenciar");
            exit;
        }

        // Busca o administrador pelo ID
        $admin = $adminModel->getById($id);
        if (!$admin) {
            $_SESSION['flash_error'] = "❌ Administrador não encontrado.";
            header("Location: /admin/gerenciar");
            exit;
        }

        // Se o formulário foi enviado (edição)
        if (isset($_POST['nome']) && isset($_POST['email'])) {
            $nome  = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha'] ?? '');

            // Mantém a senha antiga se o campo estiver vazio
            $senhaHash = empty($senha) ? $admin['senha'] : password_hash($senha, PASSWORD_DEFAULT);

            // Atualiza os dados
            $resultado = $adminModel->update($id, $nome, $email, $senhaHash);

            if ($resultado) {
                $_SESSION['flash_success'] = "✅ Dados do administrador atualizados com sucesso!";
            } else {
                $_SESSION['flash_error'] = "⚠️ Nenhuma alteração realizada ou erro ao atualizar.";
            }

            // Redireciona de volta para a listagem
            header("Location: /admin/gerenciar");
            exit;
        }

        // Renderiza a view de edição se não houve POST com nome/email
        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/admin/editar.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }


    /** 🚫 Desativar administrador */
    public function desativar()
    {
        Auth::check('admin');
        $adminModel = new Admin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];

            // Impede desativar a si mesmo
            if ($id === (int)$_SESSION['admin_id']) {
                $_SESSION['flash_error'] = "⚠️ Você não pode desativar seu próprio usuário.";
                header("Location: /admin/gerenciar");
                exit;
            }

            $adminModel->disable($id);
            $_SESSION['flash_success'] = "✅ Administrador desativado com sucesso!";
        }

        header("Location: /admin/gerenciar");
        exit;
    }

    /** 🔄 Reativar administrador */
    public function ativar()
    {
        Auth::check('admin');
        $adminModel = new Admin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];

            $adminModel->enable($id);
            $_SESSION['flash_success'] = "✅ Administrador reativado com sucesso!";
        }

        header("Location: /admin/gerenciar");
        exit;
    }
    public function fetchData()
    {
        Auth::check('admin');
        header('Content-Type: application/json');

        $type = $_GET['type'] ?? '';
        $data = [];

        switch ($type) {
            case 'empresas':
                require_once __DIR__ . '/../Models/Empresa.php';
                $model = new Empresa();
                $data = $model->getAll();
                break;

            case 'localidade':
                require_once __DIR__ . '/../Models/Empresa.php';
                $model = new Empresa();
                $data = $model->getLocalidades();
                break;

            case 'profissionais':
                require_once __DIR__ . '/../Models/Profissional.php';
                $model = new Profissional();
                $data = $model->getAll();
                break;

            case 'vagas':
                require_once __DIR__ . '/../Models/Job.php';
                $model = new Job();
                $data = $model->getAllAdmin();
                break;

            case 'metodo':
                require_once __DIR__ . '/../Models/Job.php';
                $model = new Job();
                $data = $model->getWorkMethod();
                break;

            case 'publicidade':
                require_once __DIR__ . '/../Models/Publicidade.php';
                $model = new Publicidade();
                $data = $model->getPublicidades();
                break;

            case 'categoria':
                require_once __DIR__ . '/../Models/Categoria.php';
                $model = new Categoria();
                $data = $model->getCategorias();
                break;
        }

        echo json_encode($data);
        exit;
    }

    public function toggleStatus()
    {
        Auth::check('admin');
        header('Content-Type: application/json; charset=utf-8');

        $type = $_POST['type'] ?? '';
        $id = (int)($_POST['id'] ?? 0);

        if (!$type || !$id) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
            exit;
        }

        try {
            switch ($type) {
                case 'empresas':
                    require_once __DIR__ . '/../Models/Empresa.php';
                    $model = new Empresa();
                    break;
                case 'profissionais':
                    require_once __DIR__ . '/../Models/Profissional.php';
                    $model = new Profissional();
                    break;
                case 'vagas':
                    require_once __DIR__ . '/../Models/Job.php';
                    $model = new Job();
                    break;
                case 'publicidade':
                    require_once __DIR__ . '/../Models/Publicidade.php';
                    $model = new Publicidade();
                    break;
                case 'categoria':
                    require_once __DIR__ . '/../Models/Categoria.php';
                    $model = new Categoria();
                    break;
                case 'admins':
                    require_once __DIR__ . '/../Models/Admin.php';
                    $model = new Admin();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
                    exit;
            }

            $status = $model->toggleStatus($id);

            echo json_encode([
                'success' => true,
                'status' => $status,
                'message' => "Status atualizado para " . ($status === 'S' ? 'ativo' : 'inativo')
            ]);
        } catch (Throwable $e) {
            error_log('❌ Erro no toggleStatus: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }

        exit;
    }

    public function candidatos()
    {
        Auth::check('admin');
        if (!isset($_GET['vaga'])) {
            header("Location: /admin/dashboard");
            exit;
        }

        $empresaModel = new Empresa();
        $jobModel = new Job();
        $vaga = $jobModel->getById($_GET['vaga']);
        $candidatos = $empresaModel->getCandidaturas($_GET['vaga']);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/admin/candidatos.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
    public function getRegistroAjax()
    {
        Auth::check('admin');
        header('Content-Type: application/json');

        $type = $_GET['type'] ?? '';
        $id = (int)($_GET['id'] ?? 0);

        $tiposSemId = ['metodo'];

        if (!$type || (!in_array($type, $tiposSemId) && !$id)) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
            exit;
        }

        try {
            switch ($type) {

                case 'empresas':
                    require_once __DIR__ . '/../Models/Empresa.php';
                    $model = new Empresa();
                    $registro = $model->getEmpresaCompleta($id);
                    break;

                case 'profissionais':
                    require_once __DIR__ . '/../Models/Profissional.php';
                    $model = new Profissional();
                    $registro = $model->getProfissionalDetalhado($id);
                    break;

                case 'vagas':
                    require_once __DIR__ . '/../Models/Job.php';
                    $model = new Job();
                    $registro = $model->getVagaCompleta($id);
                    break;

                case 'categoria':
                    require_once __DIR__ . '/../Models/Categoria.php';
                    $model = new Categoria();
                    $registro = $model->getById($id);
                    break;

                case 'publicidade':
                    require_once __DIR__ . '/../Models/Publicidade.php';
                    $model = new Publicidade();
                    $registro = $model->getPublicidadeDetalhada($id);
                    break;

                default:
                    echo json_encode(['success' => false, 'message' => 'Tipo desconhecido']);
                    exit;
            }

            if (!$registro) {
                echo json_encode(['success' => false, 'message' => 'Registro não encontrado']);
                exit;
            }

            echo json_encode(['success' => true, 'data' => $registro]);
        } catch (Throwable $e) {
            echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
        }
    }

    public function editarAjax()
    {
        Auth::check('admin');
        header('Content-Type: application/json; charset=utf-8');

        // 🧩 Verifica método HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método inválido']);
            exit;
        }

        /**
         * =============================
         * 🔹 Captura segura dos dados
         * =============================
         * - Alguns navegadores não populam $_POST ao enviar FormData
         * - Então fazemos um fallback lendo o corpo cru
         */
        $dados = $_POST;

        if (empty($dados)) {
            $input = file_get_contents("php://input");
            if ($input) {
                parse_str($input, $dados);
            }
        }

        // 🔹 Recupera tipo e ID
        $type = $dados['type'] ?? ($_POST['type'] ?? '');
        $id   = (int)($dados['id'] ?? ($_POST['id'] ?? 0));
        unset($dados['type'], $dados['id']);

        // 🔹 Validação básica
        if (!$type || !$id) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
            exit;
        }

        // 🔹 Log de debug (opcional — útil durante desenvolvimento)
        error_log("🧠 editarAjax() - Tipo: {$type}, ID: {$id}");
        error_log("📦 Dados recebidos: " . print_r($dados, true));
        error_log("🖼️ Arquivos: " . print_r($_FILES, true));

        try {
            switch ($type) {
                case 'empresas':
                    $model = new Empresa();
                    $ok = $model->updateEmpresaAdmin($id, $dados, $_FILES['logo'] ?? null);
                    break;

                case 'profissionais':
                    $model = new Profissional();
                    $ok = $model->updateProfissionalAdmin($id, $dados, $_FILES['foto'] ?? null);
                    break;

                case 'vagas':
                    $model = new Job();
                    $ok = $model->updateVaga($id, $dados);
                    break;

                case 'categoria':
                    $model = new Categoria();
                    $ok = $model->updateCategoria($id, $dados, $_FILES['imagem'] ?? null);
                    break;

                case 'publicidade':
                    $model = new Publicidade();
                    $ok = $model->updatePublicidade($id, $dados, $_FILES['imagem'] ?? null);
                    break;

                default:
                    echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
                    exit;
            }

            // 🔹 Retorno final
            echo json_encode([
                'success' => (bool) $ok,
                'message' => $ok
                    ? '✅ Registro atualizado com sucesso!'
                    : '⚠️ Nenhuma alteração realizada ou erro ao atualizar.'
            ]);
        } catch (Throwable $e) {
            // 🔥 Log detalhado para depuração
            error_log('❌ Erro no editarAjax (' . $type . '): ' . $e->getMessage());
            error_log('📄 Trace: ' . $e->getTraceAsString());
            error_log('📦 Dados: ' . print_r($dados, true));

            echo json_encode([
                'success' => false,
                'message' => 'Erro interno ao atualizar registro. Verifique o log para mais detalhes.'
            ]);
        }
    }
    public function reativarVaga()
    {
        Auth::check('admin');
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            return;
        }

        $job = new Job();
        $stmt = $job->update(['id' => $id, 'status' => 'S', 'data_expiracao' => date('Y-m-d H:i:s', strtotime('+7 days'))]);
        echo json_encode(['success' => true, 'message' => 'Vaga reativada por mais 7 dias']);
    }
}
