<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Job.php';

class EmpresasController
{
    public function index()
    {
        $empresaModel = new Empresa();

        $categorias = $empresaModel->getCategorias();
        $locais = $empresaModel->getLocalidades();

        $search = $_GET['q'] ?? '';
        $categoria = $_GET['categoria'] ?? '';
        $local = $_GET['local'] ?? '';

        $limit = 9;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $empresas = $empresaModel->listarEmpresas($search, $categoria, $local, $limit, $offset);
        $totalEmpresas = $empresaModel->countEmpresas($search, $categoria, $local);
        $totalPages = ceil($totalEmpresas / $limit);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresas/index.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function dashboard()
    {
        Auth::check('empresa');
        $empresaModel = new Empresa();
        $empresa = new Empresa();
        $vagas = $empresaModel->getVagas($_SESSION['empresa_id']);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresas/dashboard.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function candidatos()
    {
        Auth::check('empresa');
        if (!isset($_GET['vaga'])) {
            header("Location: /empresas/dashboard");
            exit;
        }

        $empresaModel = new Empresa();
        $jobModel = new Job();
        $vaga = $jobModel->getById($_GET['vaga']);
        $candidatos = $empresaModel->getCandidaturas($_GET['vaga']);

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresas/candidatos.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    public function logout()
    {
        Auth::logout();
    }

    /**
     * Página para publicar nova vaga (somente empresas logadas)
     * URL: /empresas/publicar
     */
    public function publicar()
    {
        // 🔐 Protege a rota (só empresa logada pode acessar)
        Auth::check('empresa');

        $empresaModel = new Empresa();
        $jobModel     = new Job();

        // 🔹 Coleta dados para selects
        $empresas   = [$empresaModel->getById($_SESSION['empresa_id'])]; // apenas a empresa logada
        $municipios = $empresaModel->getLocalidades();
        $categorias = $empresaModel->getCategorias();
        $methods    = $jobModel->getWorkMethod();

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title'        => trim($_POST['title'] ?? ''),
                'company_id'   => $_SESSION['empresa_id'] ?? 0,
                'categoria_id' => (int)($_POST['categoria_id'] ?? 0),
                'municipio_id' => (int)($_POST['municipio_id'] ?? 0),
                'method_id'    => (int)($_POST['method_id'] ?? 0),
                'salary'       => $_POST['salary'] ?? '',
                'description'  => $_POST['description'] ?? ''
            ];

            if (!$data['title'] || !$data['company_id']) {
                $error = "Informe o título da vaga e selecione uma categoria.";
            } else {
                // 🔹 Monta campo location (Nome, UF)
                $municipio = $empresaModel->getMunicipioById($data['municipio_id']);
                $data['location'] = $municipio ? "{$municipio['nome']}, {$municipio['estado']}" : '';

                // 🔹 Limpa e normaliza salário
                $rawSalary = preg_replace('/[^\d,]/', '', $data['salary']); // remove tudo que não for número ou vírgula

                if (strpos($rawSalary, ',') !== false) {
                    $cleanSalary = str_replace(',', '.', str_replace('.', '', $rawSalary));
                } else {
                    $cleanSalary = ((float)$rawSalary) / 100;
                }

                $data['salary'] = number_format((float)$cleanSalary, 2, '.', '');

                // 🔹 Salva vaga no banco
                $jobModel->save($data);

                header("Location: /empresas/dashboard");
                exit;
            }
        }

        // 🔹 Renderiza view
        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresas/publicar.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    /**
     * ✏️ Editar vaga existente
     * URL: /empresas/editar/{id}
     */
    public function editar()
    {
        Auth::check('empresa'); // 🔐 Protege acesso
        $id = $_GET['id'] ?? null;
        if (!$id) {
            die("ID da vaga não informado.");
        }

        $jobModel     = new Job();
        $empresaModel = new Empresa();

        $vaga = $jobModel->getById($id);

        // 🔹 Garante que a vaga pertence à empresa logada
        if (!$vaga || $vaga['company_id'] != $_SESSION['empresa_id']) {
            die("Vaga não encontrada ou não pertence à sua empresa.");
        }

        $municipios = $empresaModel->getLocalidades();
        $categorias = $empresaModel->getCategorias();
        $methods    = $jobModel->getWorkMethod();

        $error = null;

        // 🧾 Processa formulário
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id'           => $id,
                'title'        => trim($_POST['title'] ?? ''),
                'company_id'   => $_SESSION['empresa_id'],
                'categoria_id' => (int)($_POST['categoria_id'] ?? 0),
                'municipio_id' => (int)($_POST['municipio_id'] ?? 0),
                'method_id'    => (int)($_POST['method_id'] ?? 0),
                'salary'       => $_POST['salary'] ?? '',
                'description'  => $_POST['description'] ?? ''
            ];

            if (!$data['title']) {
                $error = "O título da vaga é obrigatório.";
            } else {
                // 🔹 Corrige o salário para valor decimal
                $rawSalary = preg_replace('/[^\d,]/', '', $data['salary']);
                if (strpos($rawSalary, ',') !== false) {
                    $cleanSalary = str_replace(',', '.', str_replace('.', '', $rawSalary));
                } else {
                    $cleanSalary = ((float)$rawSalary) / 100;
                }
                $data['salary'] = number_format((float)$cleanSalary, 2, '.', '');

                // 🔹 Monta localização
                $m = $empresaModel->getMunicipioById($data['municipio_id']);
                $data['location'] = $m ? "{$m['nome']}, {$m['estado']}" : '';

                // 🔹 Atualiza a vaga
                $jobModel->update($data);

                header("Location: /empresas/dashboard");
                exit;
            }
        }

        // 🔹 Renderiza view
        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/empresas/editar.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }
    public function excluir()
    {
        Auth::check('empresa'); // 🔐 protege rota
        
        $id = $_GET['id'] ?? null;
       
        if (!$id) {
            die("ID da vaga não informado.");
        }

        $jobModel = new Job();
        $vaga = $jobModel->getById($id);

        // 🔹 garante que a vaga pertence à empresa logada
        if (!$vaga || $vaga['company_id'] != $_SESSION['empresa_id']) {
            die("Vaga não encontrada ou não pertence à sua empresa.");
        }

        // 🔥 Exclui a vaga
        $jobModel->delete($id);

        // Redireciona de volta ao dashboard
        header("Location: /empresas/dashboard");
        exit;
    }
}
