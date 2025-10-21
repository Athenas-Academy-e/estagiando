<?php
require_once __DIR__ . '/../../inc/functions.php';
include __DIR__ . '/../../templates/header.php';

$success = $error = '';
$categoria = getCategorias();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tipo = $_POST['tipo'] ?? '';

  if ($tipo === 'empresa') {
    $dados = [
      'nomesocial' => $_POST['razao_social'],
      'nomefantasia' => $_POST['nome_fantasia'],
      'categoria' => $_POST['categoria'],
      'cnpj' => $_POST['cnpj'],
      'telefone' => $_POST['telefone1'],
      'celular' => $_POST['celular'],
      'email' => $_POST['email'],
      'site' => $_POST['site'],
      'senha' => $_POST['senha'],
      'cep' => $_POST['cep'],
      'endereco' => $_POST['endereco'],
      'numero' => $_POST['numero'],
      'bairro' => $_POST['bairro'],
      'estado' => $_POST['estado'],
      'cidade' => $_POST['cidade'],
    ];
    if (cadastrarEmpresa($dados, $_FILES['logo'])) {
      $success = "✅ Empresa cadastrada com sucesso!";
    } else {
      $error = "❌ Erro ao cadastrar empresa.";
    }
  }

  if ($tipo === 'profissional') {
    $dados = [
      'nome' => $_POST['nome'],
      'cpf' => $_POST['cpf'],
      'email' => $_POST['email'],
      'telefone' => $_POST['telefone'],
      'sexo' => $_POST['sexo'],
      'nascimento' => $_POST['nascimento'],
      'escolaridade' => $_POST['escolaridade'],
      'ocupacao' => $_POST['ocupacao'],
      'cep' => $_POST['cep'],
      'endereco' => $_POST['endereco'],
      'numero' => $_POST['numero'],
      'bairro' => $_POST['bairro'],
      'estado' => $_POST['estado'],
      'cidade' => $_POST['cidade'],
    ];
    if (cadastrarProfissional($dados, $_FILES['foto'])) {
      $success = "✅ Profissional cadastrado com sucesso!";
    } else {
      $error = "❌ Erro ao cadastrar profissional.";
    }
  }
}
?>

<section class="min-h-screen bg-gray-50 py-14">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-gray-700 mb-2">Cadastre-se agora mesmo</h2>
    <p class="text-gray-500 mb-8">e tenha acesso ao maior serviço de estagio.</p>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6 animate-fade-in"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-3 px-5 mb-6 animate-fade-in"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Escolha -->
    <div id="selecao" class="flex justify-center gap-6 mb-10">
      <button data-tipo="profissional" class="tipo-btn bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold hover:bg-[#003366] hover:text-white transition">Profissional</button>
      <button data-tipo="empresa" class="tipo-btn bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold hover:bg-[#003366] hover:text-white transition">Empresa</button>
    </div>

    <!-- Formulário Profissional -->
    <form id="form-profissional" class="hidden bg-white shadow-lg rounded-2xl p-8 text-left" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="tipo" value="profissional">
      <div class="steps">
        <!-- ETAPA 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Dados Pessoais</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" name="nome" placeholder="Nome completo" class="input" required>
            <input type="text" id="cpf" name="cpf" placeholder="CPF" class="input" required>
            <input type="email" name="email" placeholder="Email" class="input" required>
            <input type="text" id="telefoneProf" name="telefone" placeholder="Telefone" class="input">
            <select name="sexo" class="input">
              <option value="">Sexo</option>
              <option>Masculino</option>
              <option>Feminino</option>
            </select>
            <input type="date" name="nascimento" class="input">
          </div>
          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" id="cepProf" name="cep" placeholder="CEP" class="input" required>
            <input type="text" name="endereco" placeholder="Endereço" class="input" required>
            <input type="text" name="numero" placeholder="Número" class="input" required>
            <input type="text" name="bairro" placeholder="Bairro" class="input" required>
            <input type="text" name="cidade" placeholder="Cidade" class="input" required>
            <input type="text" name="estado" placeholder="Estado (UF)" class="input" required>
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso e Foto</h3>
          <div class="grid md:grid-cols-2 gap-4 mb-6">
            <input type="password" name="senha" placeholder="Senha" class="input" required>
            <input type="password" name="senha_confirm" placeholder="Confirmar Senha" class="input" required>
          </div>
          <div class="text-center">
            <h4 class="font-semibold text-gray-700 mb-3">Foto de Perfil</h4>
            <input type="file" name="foto" id="foto" accept="image/*" class="block mx-auto mb-2">
            <img id="fotoPreview" class="hidden mx-auto rounded-full w-32 h-32 object-cover border-4 border-blue-200 mb-3">
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal">Finalizar</button>
          </div>
        </div>
      </div>
    </form>

    <!-- Formulário Empresa -->
    <form id="form-empresa" class="hidden bg-white shadow-lg rounded-2xl p-8 text-left" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="tipo" value="empresa">
      <div class="steps">
        <!-- ETAPA 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold mb-6 text-center">Dados da Empresa</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" name="razao_social" placeholder="Razão Social" class="input" required>
            <input type="text" name="nome_fantasia" placeholder="Nome Fantasia" class="input" required>
            <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ" class="input" required>
            <input type="email" name="email" placeholder="E-mail" class="input" required>
            <input type="text" id="telefoneEmp" name="telefone1" placeholder="Telefone" class="input">
            <select name="categoria" class="input" required>
              <option value="">Selecione uma categoria</option>
              <?php foreach ($categoria as $c): ?>
                <option value="<?= htmlspecialchars($c['id']) ?>">
                  <?= htmlspecialchars($c['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="text" id="site" name="site" placeholder="Site" class="input">
          <div id="cnpjStatus" class="text-xs text-black my-4"></div>
          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" id="cepEmp" name="cep" placeholder="CEP" class="input" required>
            <input type="text" name="endereco" placeholder="Endereço" class="input" required>
            <input type="text" name="numero" placeholder="Número" class="input" required>
            <input type="text" name="bairro" placeholder="Bairro" class="input" required>
            <input type="text" name="cidade" placeholder="Cidade" class="input" required>
            <input type="text" name="estado" placeholder="Estado (UF)" class="input" required>
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso e Logo</h3>
          <div class="grid md:grid-cols-2 gap-4 mb-6">
            <input type="password" name="senha" placeholder="Senha" class="input" required>
            <input type="password" name="senha_confirm" placeholder="Confirmar Senha" class="input" required>
          </div>
          <div class="text-center">
            <h4 class="font-semibold text-gray-700 mb-3">Logo da Empresa</h4>
            <input type="file" name="logo" id="logo" accept="image/*" class="block mx-auto mb-2">
            <img id="logoPreview" class="hidden mx-auto rounded-lg w-32 h-32 object-cover border-4 border-blue-200 mb-3">
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal">Finalizar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>

<style>
  .input {
    @apply border border-black rounded-full px-4 py-2 text-sm text-black focus:ring-2 focus:ring-blue-600 focus:outline-none w-full placeholder:text-red-500;
  }

  .btn-principal {
    @apply bg-yellow-600 hover:bg-yellow-700 text-white px-10 py-3 rounded-full font-semibold transition;
  }

  .btn-secundario {
    @apply bg-gray-300 hover:bg-gray-400 text-gray-700 px-10 py-3 rounded-full font-semibold transition;
  }

  .animate-fade-in {
    animation: fadeIn .6s ease forwards;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<script src="https://unpkg.com/imask"></script>
<script src="/assets/js/register.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>