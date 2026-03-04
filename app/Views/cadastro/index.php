<script>
  <?php if (!empty($_POST['tipo'])): ?>
    localStorage.setItem('formType', '<?= $_POST['tipo'] ?>');
  <?php endif; ?>
</script>
<section class="min-h-screen bg-gray-50 py-14">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-gray-700 mb-2">Cadastre-se agora mesmo</h2>
    <p class="text-gray-500 mb-8">e tenha acesso ao maior serviço de estágio.</p>

    <?php if (!empty($success)): ?>

      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6">
        <?= htmlspecialchars($success) ?>
      </div>

    <?php elseif (!empty($errors) && is_iterable($errors)): ?>

      <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-4 px-6 mb-6">

        <p class="font-semibold mb-2">❌ Corrija os seguintes campos:</p>

        <ul class="list-inside space-y-1 text-sm">
          <?php foreach ($errors as $campo => $msg): ?>
            <li>
              <strong><?= ucfirst(str_replace('_', ' ', $campo)) ?>:</strong>
              <?= htmlspecialchars($msg) ?>
            </li>
          <?php endforeach; ?>
        </ul>

      </div>

    <?php endif; ?>

    <!-- Switch Tipo -->
    <div id="selecao" class="flex justify-center gap-6 mb-10">
      <button data-tipo="profissional"
        class="tipo-btn px-8 py-3 rounded-full font-semibold transition-all duration-300 bg-gray-200 text-gray-700 hover:bg-[#003366] hover:text-white shadow-sm hover:shadow-md active:scale-95">
        Profissional
      </button>

      <button data-tipo="empresa"
        class="tipo-btn px-8 py-3 rounded-full font-semibold transition-all duration-300 bg-gray-200 text-gray-700 hover:bg-[#003366] hover:text-white shadow-sm hover:shadow-md active:scale-95">
        Empresa
      </button>
    </div>

    <!-- ★ FORMULÁRIO PROFISSIONAL ★ -->
    <form id="form-profissional"
      class="hidden bg-white shadow-xl rounded-2xl p-8 text-left transition-all duration-300"
      method="POST" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="tipo" value="profissional">
      <input type="hidden" name="status" value="S">

      <div class="steps">
        <!-- Etapa 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Dados Pessoais</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <div class="flex flex-col">
              <label for="nome" class="text-sm font-semibold text-gray-600 mb-1">
                Nome Completo
              </label>
              <input
                id="nome"
                type="text"
                name="nome"
                value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 <?= isset($errors['nome']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"
                required>
            </div>

            <!-- CPF -->
            <div class="flex flex-col">
              <label for="cpf" class="text-sm font-semibold text-gray-600 mb-1">
                CPF
              </label>
              <input
                id="cpf"
                type="text"
                name="cpf"
                value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 <?= isset($errors['cpf']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"
                required>
              <p class="cpfStatus text-xs text-gray-500 mt-1"></p>
            </div>


            <!-- Email -->
            <div class="flex flex-col">
              <label for="emailProf" class="text-sm font-semibold text-gray-600 mb-1">
                E-mail
              </label>
              <input
                id="emailProf"
                type="email"
                name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 <?= isset($errors['email']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"
                required>
            </div>

            <!-- Telefone -->
            <div class="flex flex-col">
              <label for="telefoneProf" class="text-sm font-semibold text-gray-600 mb-1">
                Telefone / WhatsApp
              </label>
              <input
                id="telefoneProf"
                type="text"
                name="telefone"
                value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 <?= isset($errors['telefone']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"
                required>
            </div>

            <!-- Sexo -->
            <div class="flex flex-col">
              <label for="sexo" class="text-sm font-semibold text-gray-600 mb-1">
                Sexo
              </label>
              <select
                id="sexo"
                name="sexo"
                value="<?= htmlspecialchars($_POST['sexo'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 <?= isset($errors['sexo']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"
                required>
                <option value="">Selecione</option>
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
              </select>
            </div>

            <!-- Data -->
            <div class="flex flex-col">
              <label for="nascimento" class="text-sm font-semibold text-gray-600 mb-1">
                Data de Nascimento
              </label>
              <input
                id="nascimento"
                type="date"
                name="nascimento"
                value="<?= htmlspecialchars($_POST['nascimento'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition-all duration-200 <?= isset($errors['nascimento']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"
                required>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 1 Profissional -->

        <!-- Etapa 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <div class="flex flex-col">
              <label for="cepProf" class="text-sm font-semibold text-gray-600 mb-1">
                CEP
              </label>
              <input id="cepProf" name="cep" type="text"
                value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['cep']) ? 'input-error' : '' ?>"
                required>
              <p class="cepStatus text-xs text-gray-500 mt-1"></p>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Endereço</label>
              <input name="endereco" type="text"
                value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['endereco']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Número</label>
              <input name="numero" type="text"
                value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['numero']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Complemento</label>
              <input name="complemento" type="text"
                value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition">
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Bairro</label>
              <input name="bairro" type="text"
                value="<?= htmlspecialchars($_POST['bairro'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['bairro']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Cidade</label>
              <input name="cidade" type="text"
                value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['cidade']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Estado (UF)</label>
              <input name="estado" type="text"
                value="<?= htmlspecialchars($_POST['estado'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['estado']) ? 'input-error' : '' ?>"
                required>
            </div>
          </div>

          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 2 Profissional -->

        <!-- Etapa 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso & Foto</h3>

          <div class="grid md:grid-cols-2 gap-10">

            <!-- ================= SENHA ================= -->
            <div class="flex flex-col">

              <!-- Senha -->
              <label class="text-sm font-semibold text-gray-600 mb-1">
                Senha
              </label>

              <div class="relative mb-4">
                <input
                  type="password"
                  name="senha"
                  class="h-12 px-4 pr-12 rounded-full border border-gray-300
          focus:border-blue-600 focus:ring-2 focus:ring-blue-200
          outline-none transition w-full senha
          <?= isset($errors['senha']) ? 'input-error' : '' ?>"
                  required>

                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2
      text-gray-600 cursor-pointer select-none">
                  <i class="fa-solid fa-eye"></i>
                </span>
              </div>

              <!-- Confirmar Senha -->
              <label class="text-sm font-semibold text-gray-600 mb-1">
                Confirmar Senha
              </label>

              <div class="relative mb-4">
                <input
                  type="password"
                  name="senha_confirm"
                  class="h-12 px-4 pr-12 rounded-full border border-gray-300
          focus:border-blue-600 focus:ring-2 focus:ring-blue-200
          outline-none transition w-full senha-confirm
          <?= isset($errors['senha_confirm']) ? 'input-error' : '' ?>"
                  required>

                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2
      text-gray-600 cursor-pointer select-none">
                  <i class="fa-solid fa-eye"></i>
                </span>
              </div>

              <!-- Barra de força -->
              <div class="flex gap-2 mt-3">
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
              </div>

              <!-- Requisitos -->
              <ul class="requisitos text-sm mt-3 space-y-1 text-left font-semibold">
                <li data-rule="len">❌ Mínimo 8 caracteres</li>
                <li data-rule="upper">❌ Letra maiúscula</li>
                <li data-rule="num">❌ Número</li>
                <li data-rule="spe">❌ Símbolo (!@#$...)</li>
              </ul>

              <p class="confirmMsg text-sm mt-3 font-semibold"></p>

            </div>
            <!-- /Senha -->


            <!-- ================= FOTO ================= -->
            <div class="flex flex-col items-center">

              <label class="text-sm font-semibold text-gray-600 mb-2">
                Foto (opcional)
              </label>

              <input
                type="file"
                name="foto"
                id="foto"
                accept="image/*"
                class="mb-3 text-sm">

              <p class="text-xs text-gray-500 mb-3">
                Tamanho recomendado: <strong>400 × 400 px</strong>
              </p>

              <img
                id="fotoPreview"
                class="hidden rounded-lg w-24 h-24 object-cover
        border-4 border-blue-200 shadow-md">
            </div>
            <!-- /Foto -->

          </div>

          <div class="flex justify-between mt-8">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal finalizar opacity-50 cursor-not-allowed" disabled>Finalizar</button>
          </div>
        </div><!-- /Etapa 3 Profissional -->

      </div><!-- ✅ fechamento .steps -->
    </form><!-- ✅ fechamento form profissional -->

    <!-- ★ FORMULÁRIO EMPRESA ★ -->
    <form id="form-empresa"
      class="hidden bg-white shadow-xl rounded-2xl p-8 text-left transition-all duration-300"
      method="POST" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="tipo" value="empresa">

      <div class="steps">
        <!-- Etapa 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Dados da Empresa</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <!-- Razão Social -->
            <div class="flex flex-col">
              <label for="razao_social" class="text-sm font-semibold text-gray-600 mb-1">
                Razão Social
              </label>
              <input
                id="razao_social"
                name="razao_social"
                type="text"
                value="<?= htmlspecialchars($_POST['razao_social'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['razao_social']) ? 'input-error' : '' ?>"
                required>
            </div>

            <!-- Nome Fantasia -->
            <div class="flex flex-col">
              <label for="nome_fantasia" class="text-sm font-semibold text-gray-600 mb-1">
                Nome Fantasia
              </label>
              <input
                id="nome_fantasia"
                name="nome_fantasia"
                type="text"
                value="<?= htmlspecialchars($_POST['nome_fantasia'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition">
            </div>

            <!-- CNPJ -->
            <div class="flex flex-col">
              <label for="cnpj" class="text-sm font-semibold text-gray-600 mb-1">
                CNPJ
              </label>
              <input
                id="cnpj"
                name="cnpj"
                type="text"
                value="<?= htmlspecialchars($_POST['cnpj'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['cnpj']) ? 'input-error' : '' ?>"
                required>
              <p id="cnpjStatus" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <!-- Email -->
            <div class="flex flex-col">
              <label for="emailEmp" class="text-sm font-semibold text-gray-600 mb-1">
                E-mail Corporativo
              </label>
              <input
                id="emailEmp"
                name="email"
                type="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['email']) ? 'input-error' : '' ?>"
                required>
            </div>

            <!-- Telefone -->
            <div class="flex flex-col">
              <label for="telefoneEmp" class="text-sm font-semibold text-gray-600 mb-1">
                Telefone Comercial
              </label>
              <input
                id="telefoneEmp"
                name="telefone1"
                type="text"
                value="<?= htmlspecialchars($_POST['telefone1'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['telefone1']) ? 'input-error' : '' ?>"
                required>
            </div>

            <!-- Categoria -->
            <div class="flex flex-col">
              <label for="categoria" class="text-sm font-semibold text-gray-600 mb-1">
                Categoria
              </label>
              <select
                id="categoria"
                name="categoria"
                class="h-12 px-4 rounded-full border border-gray-300 bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['categoria']) ? 'input-error' : '' ?>"
                required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $c): ?>
                  <option value="<?= htmlspecialchars($c['id']) ?>"
                    <?= ($_POST['categoria'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Site -->
            <div class="flex flex-col">
              <label for="site" class="text-sm font-semibold text-gray-600 mb-1">
                Site (opcional)
              </label>
              <input
                id="site"
                name="site"
                type="url"
                value="<?= htmlspecialchars($_POST['site'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition">
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 1 Empresa -->

        <!-- Etapa 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <div class="flex flex-col">
              <label for="cepEmp" class="text-sm font-semibold text-gray-600 mb-1">
                CEP
              </label>
              <input id="cepEmp" name="cep" type="text"
                value="<?= htmlspecialchars($_POST['cep'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['cep']) ? 'input-error' : '' ?>"
                required>
              <p class="cepStatus text-xs text-gray-500 mt-1"></p>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Endereço</label>
              <input name="endereco" type="text"
                value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['endereco']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Número</label>
              <input name="numero" type="text"
                value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['numero']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Complemento</label>
              <input name="complemento" type="text"
                value="<?= htmlspecialchars($_POST['complemento'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition">
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Bairro</label>
              <input name="bairro" type="text"
                value="<?= htmlspecialchars($_POST['bairro'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['bairro']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Cidade</label>
              <input name="cidade" type="text"
                value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['cidade']) ? 'input-error' : '' ?>"
                required>
            </div>

            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-600 mb-1">Estado (UF)</label>
              <input name="estado" type="text"
                value="<?= htmlspecialchars($_POST['estado'] ?? '') ?>"
                class="h-12 px-4 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-200 outline-none transition <?= isset($errors['estado']) ? 'input-error' : '' ?>"
                required>
            </div>

          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 2 Empresa -->

        <!-- Etapa 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso & Logo</h3>

          <div class="grid md:grid-cols-2 gap-10">

            <!-- ================= SENHA ================= -->
            <div class="flex flex-col">

              <!-- Senha -->
              <label class="text-sm font-semibold text-gray-600 mb-1">
                Senha
              </label>

              <div class="relative mb-4">
                <input
                  type="password"
                  name="senha"
                  class="h-12 px-4 pr-12 rounded-full border border-gray-300
          focus:border-blue-600 focus:ring-2 focus:ring-blue-200
          outline-none transition w-full senha
          <?= isset($errors['senha']) ? 'input-error' : '' ?>"
                  required>

                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2
      text-gray-600 cursor-pointer select-none">
                  <i class="fa-solid fa-eye"></i>
                </span>
              </div>

              <!-- Confirmar Senha -->
              <label class="text-sm font-semibold text-gray-600 mb-1">
                Confirmar Senha
              </label>

              <div class="relative mb-4">
                <input
                  type="password"
                  name="senha_confirm"
                  class="h-12 px-4 pr-12 rounded-full border border-gray-300
          focus:border-blue-600 focus:ring-2 focus:ring-blue-200
          outline-none transition w-full senha-confirm
          <?= isset($errors['senha_confirm']) ? 'input-error' : '' ?>"
                  required>

                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2
      text-gray-600 cursor-pointer select-none">
                  <i class="fa-solid fa-eye"></i>
                </span>
              </div>

              <!-- Barra de força -->
              <div class="flex gap-2 mt-3">
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
              </div>

              <!-- Requisitos -->
              <ul class="requisitos text-sm mt-3 space-y-1 text-left font-semibold">
                <li data-rule="len">❌ Mínimo 8 caracteres</li>
                <li data-rule="upper">❌ Letra maiúscula</li>
                <li data-rule="num">❌ Número</li>
                <li data-rule="spe">❌ Símbolo (!@#$...)</li>
              </ul>

              <p class="confirmMsg text-sm mt-3 font-semibold"></p>

            </div>
            <!-- /Senha -->


            <!-- ================= LOGO ================= -->
            <div class="flex flex-col items-center">

              <label class="text-sm font-semibold text-gray-600 mb-2">
                Logo (opcional)
              </label>

              <input
                type="file"
                name="logo"
                id="logo"
                accept="image/*"
                class="mb-3 text-sm">

              <p class="text-xs text-gray-500 mb-3">
                Tamanho recomendado: <strong>500 × 500 px</strong>
              </p>

              <img
                id="logoPreview"
                class="hidden rounded-lg w-24 h-24 object-cover
        border-4 border-blue-200 shadow-md">
            </div>
            <!-- /Logo -->

          </div>

          <div class="flex justify-between mt-8">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal finalizar opacity-50 cursor-not-allowed" disabled>Finalizar</button>
          </div>
        </div><!-- /Etapa 3 Empresa -->

      </div><!-- ✅ fechamento .steps -->
    </form><!-- ✅ fechamento form empresa -->

  </div>
  <script>
    window.tipoComErro = "<?= $_POST['tipo'] ?? '' ?>";
    window.etapaComErro = <?= isset($etapaErro) ? $etapaErro : 1 ?>;
  </script>
  <!-- ===== LOADING OVERLAY ===== -->
  <div id="loadingOverlay"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm 
         flex items-center justify-center z-50 hidden">

    <div class="bg-white rounded-2xl shadow-xl p-8 flex flex-col items-center gap-4">

      <div class="spinner"></div>

      <p class="text-gray-700 font-semibold">
        Processando cadastro...
      </p>

    </div>
  </div>
</section>

<!-- CSS simples para botões (sem @apply) -->
<style>
  .btn-principal {
    background: #ca8a04;
    color: #fff;
    border-radius: 9999px;
    padding: 0.75rem 2.5rem;
    font-weight: 600;
    transition: box-shadow .2s, transform .05s, background .2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
  }

  .btn-principal:hover {
    background: #a16207;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
  }

  .btn-principal:active {
    transform: scale(.98);
  }

  .btn-secundario {
    background: #e5e7eb;
    /* gray-200 */
    color: #374151;
    /* gray-700 */
    border-radius: 9999px;
    padding: 0.75rem 2.5rem;
    font-weight: 600;
    transition: box-shadow .2s, transform .05s, background .2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
  }

  .btn-secundario:hover {
    background: #d1d5db;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
  }

  .btn-secundario:active {
    transform: scale(.98);
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

  .input-error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 1px #dc2626;
  }

  .spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #003366;
    border-radius: 50%;
    animation: girar 0.8s linear infinite;
  }

  @keyframes girar {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }

  .toggle-eye i {
    transition: transform .2s ease;
  }

  .toggle-eye:active i {
    transform: scale(0.9);
  }
</style>

<script src="https://unpkg.com/imask"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    /* ===== Selecão automática do formulário ===== */
    const savedForm = localStorage.getItem('formType') || 'profissional';
    const tipoBtns = document.querySelectorAll('.tipo-btn');
    const forms = {
      profissional: document.getElementById('form-profissional'),
      empresa: document.getElementById('form-empresa')
    };

    function ativarFormulario(tipo) {
      tipoBtns.forEach(b => {
        b.classList.toggle('bg-[#003366]', b.dataset.tipo === tipo);
        b.classList.toggle('text-white', b.dataset.tipo === tipo);
        b.classList.toggle('bg-gray-200', b.dataset.tipo !== tipo);
        b.classList.toggle('text-gray-700', b.dataset.tipo !== tipo);
      });
      Object.values(forms).forEach(f => f.classList.add('hidden'));
      forms[tipo].classList.remove('hidden');
      inicializarEtapas(forms[tipo]);
      localStorage.setItem('formType', tipo);
    }

    const tipoInicial = window.tipoComErro || savedForm;
    ativarFormulario(tipoInicial);

    tipoBtns.forEach(btn => {
      btn.addEventListener('click', () => ativarFormulario(btn.dataset.tipo));
    });

    /* ===== Controle de etapas ===== */
    function inicializarEtapas(form) {
      const steps = [...form.querySelectorAll('.step')];
      let current = 0;

      if (window.tipoComErro && form.querySelector('[name="tipo"]').value === window.tipoComErro) {
        current = (window.etapaComErro || 1) - 1;
      }

      function show(i) {
        steps.forEach((s, idx) => s.classList.toggle('hidden', idx !== i));
      }
      form.querySelectorAll('.next').forEach(btn => btn.onclick = () => {
        if (current < steps.length - 1) {
          current++;
          show(current);
        }
      });
      form.querySelectorAll('.prev').forEach(btn => btn.onclick = () => {
        if (current > 0) {
          current--;
          show(current);
        }
      });
      show(current);
    }
    Object.values(forms).forEach(inicializarEtapas);

    /* ===== Pré-visualização imagens ===== */
    function previewFile(input, img) {
      input?.addEventListener('change', () => {
        const f = input.files?.[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = e => {
          img.src = e.target.result;
          img.classList.remove('hidden');
        };
        r.readAsDataURL(f);
      });
    }
    previewFile(document.getElementById('foto'), document.getElementById('fotoPreview'));
    previewFile(document.getElementById('logo'), document.getElementById('logoPreview'));

    /* ===== Olho da senha ===== */
    document.querySelectorAll('.toggle-eye').forEach(eye => {
      eye.addEventListener('click', () => {
        const input = eye.previousElementSibling;
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        eye.innerHTML = isPass ? '<i class="fa-solid fa-eye-slash"></i>' : '<i class="fa-solid fa-eye"></i>';
      });
    });

    /* ===== Força + Match Senha ===== */
    function verificarSenhas(form) {
      const p = form.querySelector('.senha');
      const c = form.querySelector('.senha-confirm');
      const bars = form.querySelectorAll('.strength-bar');
      const reqs = form.querySelector('.requisitos');
      const msg = form.querySelector('.confirmMsg');
      const btn = form.querySelector('.finalizar');
      if (!p || !c) return;

      function val() {
        let s = p.value,
          e = {
            len: s.length >= 8,
            upper: /[A-Z]/.test(s),
            num: /\d/.test(s),
            spe: /[^A-Za-z0-9]/.test(s)
          };
        Object.entries(e).forEach(([k, v]) => {
          const li = reqs.querySelector(`[data-rule="${k}"]`);
          const text = li.textContent.replace(/^✅ |^❌ /, '');
          li.textContent = `${v?'✅':'❌'} ${text}`;
          li.classList.toggle('text-green-600', v);
          li.classList.toggle('text-red-600', !v);
        });
        let lvl = Object.values(e).filter(Boolean).length;
        bars.forEach((b, i) => b.className = `strength-bar w-16 h-2 rounded ${i<lvl?(lvl<3?'bg-yellow-500':'bg-green-600'):'bg-gray-300'}`);
        const match = s && s === c.value;
        msg.textContent = match ? "✅ Senhas coincidem" : "❌ Senhas não coincidem";
        msg.classList.toggle('text-green-600', match);
        msg.classList.toggle('text-red-600', !match);
        const ok = match && lvl >= 3;
        btn.disabled = !ok;
        btn.classList.toggle('opacity-50', !ok);
        btn.classList.toggle('cursor-not-allowed', !ok);
      }
      p.addEventListener('input', val);
      c.addEventListener('input', val);
      val();
    }
    verificarSenhas(forms.profissional);
    verificarSenhas(forms.empresa);

    /* ===== Validação CNPJ ===== */
    function validarCNPJ(c) {
      c = c.replace(/\D/g, '');
      if (c.length !== 14 || /^(\d)\1+$/.test(c)) return false;
      let t = 12,
        n = c.slice(0, t),
        d = c.slice(t),
        s = 0,
        p = t - 7;
      for (let i = t; i >= 1; i--) {
        s += n[t - i] * p--;
        if (p < 2) p = 9;
      }
      let r = s % 11 < 2 ? 0 : 11 - (s % 11);
      if (r != d[0]) return false;
      t = 13;
      n = c.slice(0, t);
      s = 0;
      p = t - 7;
      for (let i = t; i >= 1; i--) {
        s += n[t - i] * p--;
        if (p < 2) p = 9;
      }
      r = s % 11 < 2 ? 0 : 11 - (s % 11);
      return r == d[1];
    }
    const inCNPJ = document.getElementById('cnpj'),
      msgCNPJ = document.getElementById('cnpjStatus');
    inCNPJ?.addEventListener('input', () => {
      const v = inCNPJ.value.replace(/\D/g, '');
      if (v.length < 14) {
        msgCNPJ.textContent = 'Digite o CNPJ completo';
        msgCNPJ.className = 'text-xs text-gray-500 mt-1';
        return;
      }
      const ok = validarCNPJ(v);
      msgCNPJ.textContent = ok ? '✅ Válido' : '❌ Inválido';
      msgCNPJ.className = `text-xs mt-1 ${ok?'text-green-600':'text-red-600'}`;
    });

    /* ===== ViaCEP (profissional + empresa) ===== */
    const ceps = [{
        id: 'cepProf',
        form: forms.profissional
      },
      {
        id: 'cepEmp',
        form: forms.empresa
      }
    ];

    ceps.forEach(({
      id,
      form
    }) => {
      const input = document.getElementById(id);
      if (!input || !form) return;

      // 🔥 Correção aqui
      const statusEl = form.querySelector(`#${id} + .cepStatus`);

      const endereco = form.querySelector('[name="endereco"]');
      const bairro = form.querySelector('[name="bairro"]');
      const cidade = form.querySelector('[name="cidade"]');
      const estado = form.querySelector('[name="estado"]');

      input.addEventListener('input', async () => {
        const clean = input.value.replace(/\D/g, '');

        if (clean.length !== 8) {
          if (statusEl) {
            statusEl.textContent = '';
            statusEl.className = 'cepStatus text-xs text-gray-500 mt-1';
          }
          return;
        }

        if (statusEl) {
          statusEl.textContent = 'Buscando endereço...';
          statusEl.className = 'cepStatus text-xs text-gray-500 mt-1';
        }

        try {
          const resp = await fetch(`/cep/buscar?cep=${clean}`);
          const data = await resp.json();
          console.log(data);
          if (data.erro) throw new Error();

          endereco.value = data.logradouro || '';
          bairro.value = data.bairro || '';
          cidade.value = data.localidade || '';
          estado.value = data.uf || '';

          if (statusEl) {
            statusEl.textContent = '✅ Endereço encontrado';
            statusEl.className = 'cepStatus text-xs text-green-600 mt-1';
          }

        } catch {
          if (statusEl) {
            statusEl.textContent = '❌ CEP não encontrado';
            statusEl.className = 'cepStatus text-xs text-red-600 mt-1';
          }
        }
      });
    });

    /* ===== Validação CPF ===== */
    function inicializarCPF() {
      const input = document.getElementById('cpf');
      const status = document.querySelector('.cpfStatus');
      if (!input) return;

      function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');

        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

        let soma = 0;
        let resto;

        for (let i = 1; i <= 9; i++)
          soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);

        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;

        soma = 0;

        for (let i = 1; i <= 10; i++)
          soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);

        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;

        return resto === parseInt(cpf.substring(10, 11));
      }

      input.addEventListener('input', () => {
        const cpf = input.value.replace(/\D/g, '');

        if (cpf.length < 11) {
          status.textContent = 'Digite o CPF completo';
          status.className = 'cpfStatus text-xs text-gray-500 mt-1';
          input.classList.remove('input-error');
          return;
        }

        const valido = validarCPF(cpf);

        status.textContent = valido ? '✅ CPF válido' : '❌ CPF inválido';
        status.className = `cpfStatus text-xs mt-1 ${valido ? 'text-green-600' : 'text-red-600'}`;
        input.classList.toggle('input-error', !valido);
      });
    }

    /* ===== Máscaras ===== */
    if (window.IMask) {
      IMask(document.getElementById('cpf'), {
        mask: '000.000.000-00'
      });
      IMask(document.getElementById('cnpj'), {
        mask: '00.000.000/0000-00'
      });
      IMask(document.getElementById('telefoneProf'), {
        mask: '(00) 00000-0000'
      });
      IMask(document.getElementById('telefoneEmp'), {
        mask: '(00) 00000-0000'
      });
      IMask(document.getElementById('cepProf'), {
        mask: '00000-000'
      });
      IMask(document.getElementById('cepEmp'), {
        mask: '00000-000'
      });
    }

    inicializarCPF();

    /* ===== Loading Fullscreen ===== */
    const overlay = document.getElementById('loadingOverlay');

    function ativarLoading() {
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function desativarLoading() {
      overlay.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }

    // Ativar loading ao enviar qualquer formulário
    document.querySelectorAll('#form-profissional, #form-empresa')
      .forEach(form => {
        form.addEventListener('submit', () => {
          ativarLoading();
        });
      });

    // Segurança extra: desativa caso a página já venha com resposta
    window.addEventListener('pageshow', () => {
      desativarLoading();
    });
  });
</script>