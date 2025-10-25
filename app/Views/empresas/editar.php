<div class="md:col-span-3 bg-white p-6 rounded-lg shadow-sm">
  <h2 class="text-xl font-semibold mb-4">Editar vaga</h2>

  <?php if (!empty($error)): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-3">

    <input type="text" name="title" value="<?= htmlspecialchars($vaga['title']) ?>" class="p-2 border rounded" required>

    <select name="categoria_id" class="p-2 border rounded" required>
      <option value="" disabled>Selecione uma categoria</option>
      <?php foreach ($categorias as $cat): ?>
        <option value="<?= (int)$cat['id'] ?>" <?= ($vaga['categoria_id'] == $cat['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['nome']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="municipio_id" class="p-2 border rounded" required>
      <option value="" disabled>Selecione uma localidade</option>
      <?php foreach ($municipios as $mun): ?>
        <option value="<?= (int)$mun['id'] ?>" <?= ($vaga['municipio_id'] == $mun['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($mun['nome']) . ' - ' . htmlspecialchars($mun['estado']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="method_id" class="p-2 border rounded" required>
      <option value="" disabled>Selecione o tipo de trabalho</option>
      <?php foreach ($methods as $jm): ?>
        <option value="<?= (int)$jm['id'] ?>" <?= ($vaga['method_id'] == $jm['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($jm['nome']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="salary" value="<?= htmlspecialchars($vaga['salary']) ?>" class="p-2 border rounded md:col-span-2">
    <textarea name="description" class="p-2 border rounded md:col-span-2" rows="4"><?= htmlspecialchars($vaga['description']) ?></textarea>

    <div class="md:col-span-2 flex justify-end gap-2">
      <a href="/empresas/dashboard" class="px-3 py-2 border rounded">Cancelar</a>
      <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Salvar alterações</button>
    </div>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const salaryInput = document.querySelector('input[name="salary"]');
  if (!salaryInput) return;

  // Aplica máscara R$ ao digitar
  salaryInput.addEventListener('input', (e) => {
    let v = e.target.value.replace(/\D/g, "");
    if (!v) { e.target.value = ""; return; }
    v = (v / 100).toFixed(2) + "";
    v = v.replace(".", ",");
    v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    e.target.value = "R$ " + v;
  });

  // Remove máscara antes de enviar
  salaryInput.form.addEventListener('submit', () => {
    const raw = salaryInput.value;
    salaryInput.value = raw.replace(/[^\d,]/g, "").replace(",", ".");
  });
});
</script>
