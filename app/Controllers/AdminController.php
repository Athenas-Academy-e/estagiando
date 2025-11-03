<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Models/Job.php';

class AdminController
{
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

            case 'metodos_trabalho':
                require_once __DIR__ . '/../Models/Job.php';
                $model = new Job();
                $data = $model->getWorkMethod();
                break;

            case 'admins':
                require_once __DIR__ . '/../Models/Admin.php';
                $model = new Admin();
                $data = $model->getAll();
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
        header('Content-Type: application/json');

        $type = $_POST['type'] ?? '';
        $id = (int)($_POST['id'] ?? 0);

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
                echo json_encode(['error' => 'Tipo inválido']);
                exit;
        }

        $status = $model->toggleStatus($id);
        echo json_encode(['success' => true, 'status' => $status]);
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

        if (!$type || !$id) {
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
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método inválido']);
            exit;
        }

        $type = $_POST['type'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        unset($_POST['type'], $_POST['id']);

        if (!$type || !$id) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            exit;
        }

        switch ($type) {
            case 'empresas':
                $model = new Empresa();
                break;
            case 'profissionais':
                $model = new Profissional();
                break;
            case 'vagas':
                $model = new Job();
                break;
            case 'categorias':
                $model = new Categoria();
                break;
            case 'publicidade':
                $model = new Publicidade();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
                exit;
        }

        $ok = $model->updateGeneric($id, $_POST);
        echo json_encode(['success' => $ok]);
    }
}
