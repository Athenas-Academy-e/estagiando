<?php
require_once __DIR__ . '/../Models/Empresa.php';
require_once __DIR__ . '/../Models/Profissional.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Mailer.php';
require_once __DIR__ . '/../Emails/EmailTemplate.php';

class CadastroController
{
    public function index()
    {
        $pageTitle = "Estagiando - Cadastro";
        $success = '';
        $error   = '';
        $errors  = [];
        $old     = $_POST ?? [];

        $categoriaModel = new Empresa();
        $categorias = $categoriaModel->getCategorias();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $tipo = $_POST['tipo'] ?? '';

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $link = $protocol . '://' . $_SERVER['HTTP_HOST'] . "/login";

            /* ================= VALIDAÇÃO ================= */
            if ($tipo === 'empresa') {
                $errors = $this->validarEmpresa($_POST);
            }

            if ($tipo === 'profissional') {
                $errors = $this->validarProfissional($_POST);
            }

            $etapaErro = 1;

            if (!empty($errors)) {

                if ($tipo === 'profissional') {

                    $etapa1 = ['nome', 'cpf', 'email', 'telefone', 'sexo', 'nascimento'];
                    $etapa2 = ['cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado'];
                    $etapa3 = ['senha', 'senha_confirm'];
                } elseif ($tipo === 'empresa') {

                    $etapa1 = ['razao_social', 'nome_fantasia', 'cnpj', 'email', 'telefone1', 'categoria', 'site'];
                    $etapa2 = ['cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado'];
                    $etapa3 = ['senha', 'senha_confirm'];
                }

                foreach ($errors as $campo => $msg) {
                    if (in_array($campo, $etapa2)) {
                        $etapaErro = 2;
                        break;
                    }
                    if (in_array($campo, $etapa3)) {
                        $etapaErro = 3;
                        break;
                    }
                }
            }
            
            /* ================= SE NÃO HOUVER ERROS ================= */
            if (empty($errors)) {

                try {

                    if ($tipo === 'empresa') {

                        $empresa = new Empresa();
                        $resultado = $empresa->cadastrar($_POST, $_FILES['logo'] ?? null);

                        if ($resultado) {
                            $html = EmailTemplate::render('boas_vindas', [
                                'nome' => $_POST['razao_social'],
                                'tipo' => 'Empresa',
                                'link' => $link
                            ]);

                            Mailer::enviar($_POST['email'], 'Bem-vindo ao Estagiando!', $html);
                            $success = "✅ Empresa cadastrada com sucesso!";
                            $_POST = [];
                        }
                    }

                    if ($tipo === 'profissional') {

                        $profissional = new Profissional();
                        $resultado = $profissional->cadastrar($_POST, $_FILES['foto'] ?? null);

                        if ($resultado) {
                            $html = EmailTemplate::render('boas_vindas', [
                                'nome' => $_POST['nome'],
                                'tipo' => 'Profissional',
                                'link' => $link
                            ]);

                            Mailer::enviar($_POST['email'], 'Bem-vindo ao Estagiando!', $html);
                            $success = "✅ Profissional cadastrado com sucesso!";
                            $_POST = [];
                        }
                    }
                } catch (Exception $e) {
                    $error = "❌ " . $e->getMessage();
                }
            } else {
                $error = "❌ Corrija os campos destacados.";
            }
        }

        require_once __DIR__ . '/../Views/partials/head.php';
        require_once __DIR__ . '/../Views/partials/header.php';
        require_once __DIR__ . '/../Views/cadastro/index.php';
        require_once __DIR__ . '/../Views/partials/footer.php';
    }

    /* =====================================================
       VALIDAÇÃO EMPRESA
    ===================================================== */
    private function validarEmpresa($data)
    {
        $errors = [];

        // 1️⃣ Tipo
        if ($_POST['tipo'] !== 'empresa') {
            $errors['tipo'] = "Tipo inválido.";
        }

        // 2️⃣ Razão Social
        if (empty($_POST['razao_social'])) {
            $errors['razao_social'] = "Razão social é obrigatória.";
        }

        // 3️⃣ Nome Fantasia
        if (empty($_POST['nome_fantasia'])) {
            $errors['nome_fantasia'] = "Nome fantasia é obrigatório.";
        }

        // 4️⃣ CNPJ
        if (empty($_POST['cnpj'])) {
            $errors['cnpj'] = "CNPJ é obrigatório.";
        } elseif (!SELF::validarCNPJ($_POST['cnpj'])) {
            $errors['cnpj'] = "CNPJ inválido.";
        }

        // 5️⃣ Email
        if (empty($_POST['email'])) {
            $errors['email'] = "Email é obrigatório.";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email inválido.";
        }

        // 6️⃣ Telefone
        if (empty($_POST['telefone1'])) {
            $errors['telefone1'] = "Telefone é obrigatório.";
        }

        // 7️⃣ Categoria
        if (empty($_POST['categoria'])) {
            $errors['categoria'] = "Categoria é obrigatória.";
        }

        // 8️⃣ CEP
        if (empty($_POST['cep'])) {
            $errors['cep'] = "CEP é obrigatório.";
        }

        // 9️⃣ Endereço
        if (empty($_POST['endereco'])) {
            $errors['endereco'] = "Endereço é obrigatório.";
        }

        // 🔟 Número
        if (empty($_POST['numero'])) {
            $errors['numero'] = "Número é obrigatório.";
        }

        // 11️⃣ Bairro
        if (empty($_POST['bairro'])) {
            $errors['bairro'] = "Bairro é obrigatório.";
        }

        // 12️⃣ Cidade
        if (empty($_POST['cidade'])) {
            $errors['cidade'] = "Cidade é obrigatória.";
        }

        // 13️⃣ Estado
        if (empty($_POST['estado'])) {
            $errors['estado'] = "Estado é obrigatório.";
        }

        // 14️⃣ Senha
        if (empty($_POST['senha'])) {
            $errors['senha'] = "Senha é obrigatória.";
        } elseif (!self::validarSenha($_POST['senha'])) {
            $errors['senha'] = "Senha fraca.";
        }

        // 15️⃣ Confirmar Senha
        if ($_POST['senha'] !== $_POST['senha_confirm']) {
            $errors['senha_confirm'] = "As senhas não coincidem.";
        }

        return $errors;
    }

    /* =====================================================
       VALIDAÇÃO PROFISSIONAL
    ===================================================== */
    private function validarProfissional($data)
    {
        $errors = [];

        /* ================= DADOS PESSOAIS ================= */

        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }

        if (empty($data['cpf'])) {
            $errors['cpf'] = 'CPF é obrigatório';
        } elseif (!$this->validarCPF($data['cpf'])) {
            $errors['cpf'] = 'CPF inválido';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }

        if (empty($data['telefone'])) {
            $errors['telefone'] = 'Telefone é obrigatório';
        }

        if (empty($data['sexo'])) {
            $errors['sexo'] = 'Sexo é obrigatório';
        }

        if (empty($data['nascimento'])) {
            $errors['nascimento'] = 'Data de nascimento é obrigatória';
        } elseif (!$this->validarData($data['nascimento'])) {
            $errors['nascimento'] = 'Data inválida';
        }

        /* ================= ENDEREÇO ================= */

        if (empty($data['cep'])) {
            $errors['cep'] = 'CEP é obrigatório';
        }

        if (empty($data['endereco'])) {
            $errors['endereco'] = 'Endereço é obrigatório';
        }

        if (empty($data['numero'])) {
            $errors['numero'] = 'Número é obrigatório';
        }

        if (empty($data['bairro'])) {
            $errors['bairro'] = 'Bairro é obrigatório';
        }

        if (empty($data['cidade'])) {
            $errors['cidade'] = 'Cidade é obrigatória';
        }

        if (empty($data['estado'])) {
            $errors['estado'] = 'Estado é obrigatório';
        }

        /* ================= SENHA ================= */

        if (empty($_POST['senha'])) {
            $errors['senha'] = "Senha é obrigatória.";
        } elseif (!self::validarSenha($_POST['senha'])) {
            $errors['senha'] = "Senha fraca.";
        }

        // 15️⃣ Confirmar Senha
        if ($_POST['senha'] !== $_POST['senha_confirm']) {
            $errors['senha_confirm'] = "As senhas não coincidem.";
        }

        return $errors;
    }

    public function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    public function validarData($data)
    {
        $d = DateTime::createFromFormat('Y-m-d', $data);
        return $d && $d->format('Y-m-d') === $data;
    }

    public function validarSenha($senha)
    {
        $temLetra = preg_match('/[a-zA-Z]/', $senha);
        $temNumero = preg_match('/\d/', $senha);
        $temEspecial = preg_match('/[@$!%*?&]/', $senha);
        return strlen($senha) >= 8 && $temLetra && $temNumero && $temEspecial;
    }

    public function validarCNPJ($cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) != 14) return false;

        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        $tamanho = 12;
        $numeros = substr($cnpj, 0, $tamanho);
        $digitos = substr($cnpj, $tamanho);

        $soma = 0;
        $pos = $tamanho - 7;

        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }

        $resultado = ($soma % 11 < 2) ? 0 : 11 - $soma % 11;
        if ($resultado != $digitos[0]) return false;

        $tamanho = 13;
        $numeros = substr($cnpj, 0, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;

        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }

        $resultado = ($soma % 11 < 2) ? 0 : 11 - $soma % 11;
        return ($resultado == $digitos[1]);
    }
}
