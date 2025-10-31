<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-3xl mx-auto px-6 bg-white p-6 shadow rounded-2xl">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
      Editar dados da empresa
    </h1>

    <!-- Toast -->
    <?php if (!empty($success)): ?>
      <div class="toast bg-green-600 text-white px-5 py-3 rounded-lg shadow mb-6">
        ✅ <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="toast bg-red-600 text-white px-5 py-3 rounded-lg shadow mb-6">
        ❌ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">

      <!-- Razão Social -->
      <div>
        <label class="block text-sm font-semibold mb-1">Nome da Empresa *</label>
        <input type="text" name="razao_social" required
          value="<?= (!isset($empresa['razao_social']) ? htmlspecialchars($empresa['nome_fantasia']) : htmlspecialchars($empresa['razao_social'])) ?>"
          class="w-full border border-gray-300 rounded-md px-3 py-2">
      </div>
      <!-- Nome Fantasia -->
      <div>
        <label class="block text-sm font-semibold mb-1">Nome Fantasia *</label>
        <input type="text" name="nome_fantasia" required
          value="<?= (!isset($empresa['nome_fantasia']) ? htmlspecialchars($empresa['razao_social']) : htmlspecialchars($empresa['nome_fantasia'])) ?>"
          class="w-full border border-gray-300 rounded-md px-3 py-2">
      </div>

      <!-- CNPJ -->
      <div>
        <label class="block text-sm font-semibold mb-1">CNPJ *</label>
        <input type="text" id="cnpj" name="cnpj" required maxlength="18"
          value="<?= htmlspecialchars($empresa['cnpj']) ?>"
          class="w-full border border-gray-300 rounded-md px-3 py-2">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Telefone -->
        <div>
          <label class="block text-sm font-semibold mb-1">Telefone *</label>
          <input type="text" id="telefone" name="telefone" required
            value="<?= htmlspecialchars($empresa['telefone']) ?>"
            class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-semibold mb-1">E-mail *</label>
          <input type="email" name="email" required
            value="<?= htmlspecialchars($empresa['email']) ?>"
            class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

      </div>
      <!-- Endereço completo -->
      <div class="grid md:grid-cols-3 gap-3">
        <div>
          <label class="text-sm font-semibold">CEP</label>
          <input id="cep" type="text" name="cep" value="<?= htmlspecialchars($empresa['cep']) ?>" maxlength="9"
            class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>

        <div>
          <label class="text-sm font-semibold">Número</label>
          <input type="text" name="numero" value="<?= htmlspecialchars($empresa['numero']) ?>"
            class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>

        <div>
          <label class="text-sm font-semibold">Bairro</label>
          <input id="bairro" type="text" name="bairro" value="<?= htmlspecialchars($empresa['bairro']) ?>"
            class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>

      <div>
        <label class="text-sm font-semibold">Endereço</label>
        <input id="endereco" type="text" name="endereco" value="<?= htmlspecialchars($empresa['endereco']) ?>"
          class="w-full border-gray-300 rounded-md px-3 py-2">
      </div>

      <!-- Localidade -->
      <div>
        <label class="block text-sm font-semibold mb-1">Localização *</label>
        <select name="municipio_id" required
          class="w-full border border-gray-300 rounded-md px-3 py-2">
          <option value="">Selecione...</option>
          <?php foreach ($municipios as $mun): ?>
            <option value="<?= $mun['id'] ?>"
              <?php if ($empresa['municipio_id'] == $mun['id']) echo 'selected'; ?>>
              <?= htmlspecialchars($mun['nome'] . " - " . $mun['estado']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Logo -->
      <!-- <div>
        <label class="block text-sm font-semibold mb-1">Logo da Empresa</label>
        <input type="file" name="logo" id="logoInput" class="w-full">
        <img id="logoPreview"
          src="<?= !empty($empresa['logo']) ? htmlspecialchars($empresa['logo']) : '#' ?>"
          class="mt-3 w-24 h-24 object-cover rounded-full shadow <?= empty($empresa['logo']) ? 'hidden' : '' ?>">
      </div> -->

      <button
        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md shadow hover:bg-blue-700 transition-all">
        Salvar Alterações
      </button>

    </form>

  </div>
</main>

<script>
  // === Toast disappear ===
  setTimeout(() => {
    document.querySelectorAll('.toast').forEach(t => t.style.display = 'none');
  }, 3500);

  // === Logo preview ===
  document.getElementById('logoInput').addEventListener('change', function(ev) {
    const file = ev.target.files[0];
    if (file) {
      const url = URL.createObjectURL(file);
      const img = document.getElementById('logoPreview');
      img.src = url;
      img.classList.remove('hidden');
    }
  });

  // === CNPJ Mask ===
  const maskCNPJ = v => v.replace(/\D/g, '')
    .replace(/(\d{2})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1/$2')
    .replace(/(\d{4})(\d{1,2})$/, '$1-$2');

  document.getElementById('cnpj').addEventListener('input', function() {
    this.value = maskCNPJ(this.value);
  });

  // === Telefone Mask ===
  const tel = document.getElementById('telefone');
  tel.addEventListener('input', () => {
    let v = tel.value.replace(/\D/g, '');
    if (v.length > 10) {
      tel.value = v.replace(/(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else {
      tel.value = v.replace(/(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    }
  });
  // === ViaCEP AutoComplete ===
  document.getElementById('cep').addEventListener('blur', function() {
    let cep = this.value.replace(/\D/g, '');

    if (cep.length !== 8) return;

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(res => res.json())
      .then(data => {
        if (!data.erro) {
          document.getElementById('endereco').value = data.logradouro;
          document.getElementById('bairro').value = data.bairro;
        }
      });
  });

</script>