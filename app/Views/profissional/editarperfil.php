<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-3xl mx-auto px-6 bg-white p-6 shadow rounded-2xl">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">
      Editar dados do profissional
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

    <form method="POST" class="space-y-4">

      <!-- Nome -->
      <div>
        <label class="block text-sm font-semibold mb-1">Nome completo *</label>
        <input type="text" name="nome" required
          value="<?= htmlspecialchars($profissional['nome'] ?? '') ?>"
          class="w-full border border-gray-300 rounded-md px-3 py-2">
      </div>

      <!-- CPF -->
      <div>
        <label class="block text-sm font-semibold mb-1">CPF *</label>
        <input type="text" id="cpf" name="cpf" required maxlength="14"
          value="<?= htmlspecialchars($profissional['cpf'] ?? '') ?>"
          class="w-full border border-gray-300 rounded-md px-3 py-2">
      </div>

      <!-- Contato -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold mb-1">Telefone *</label>
          <input type="text" id="telefone" name="telefone" required
            value="<?= htmlspecialchars($profissional['telefone'] ?? '') ?>"
            class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">E-mail *</label>
          <input type="email" name="email" required
            value="<?= htmlspecialchars($profissional['email'] ?? '') ?>"
            class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>

      <!-- Endereço -->
      <div class="grid md:grid-cols-3 gap-3">
        <div>
          <label class="text-sm font-semibold">CEP</label>
          <input id="cep" type="text" name="cep" maxlength="9"
            value="<?= htmlspecialchars($profissional['cep'] ?? '') ?>"
            class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>

        <div>
          <label class="text-sm font-semibold">Número</label>
          <input type="text" name="numero"
            value="<?= htmlspecialchars($profissional['numero'] ?? '') ?>"
            class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>

        <div>
          <label class="text-sm font-semibold">Bairro</label>
          <input id="bairro" type="text" name="bairro"
            value="<?= htmlspecialchars($profissional['bairro'] ?? '') ?>"
            class="w-full border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>

      <div>
        <label class="text-sm font-semibold">Endereço</label>
        <input id="endereco" type="text" name="endereco"
          value="<?= htmlspecialchars($profissional['endereco'] ?? '') ?>"
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
              <?php if (($profissional['municipio_id'] ?? '') == $mun['id']) echo 'selected'; ?>>
              <?= htmlspecialchars($mun['nome'] . " - " . $mun['estado']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Botão -->
      <button
        class="bg-[#97dd3a] text-white font-semibold px-6 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">
        Salvar Alterações
      </button>
      <a href="/profissional/dashboard"
        class="px-4 py-2 border rounded-md hover:bg-gray-100 transition">
        Cancelar
      </a>
    </form>

  </div>
</main>

<script>
  // === Toast disappear ===
  setTimeout(() => {
    document.querySelectorAll('.toast').forEach(t => t.style.display = 'none');
  }, 3500);

  // === CPF Mask ===
  const maskCPF = v => v.replace(/\D/g, '')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d)/, '$1.$2')
    .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
  document.getElementById('cpf').addEventListener('input', function() {
    this.value = maskCPF(this.value);
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