<div class="md:col-span-3 bg-white p-6 rounded-lg shadow-sm">
  <h2 class="text-xl font-semibold mb-4">Publicar nova vaga</h2>

  <?php if (!empty($error)): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-3">

    <input type="text" name="title" placeholder="Título da vaga" class="p-2 border rounded" required>

    <select name="municipio_id" class="p-2 border rounded" required>
      <option value="" disabled selected>Selecione a localidade</option>
      <?php foreach ($municipios as $m): ?>
        <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['nome']) . ' - ' . htmlspecialchars($m['estado']) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="method_id" class="p-2 border rounded" required>
      <option value="" disabled selected>Selecione o tipo de trabalho</option>
      <?php foreach ($methods as $jm): ?>
        <option value="<?= (int)$jm['id'] ?>"><?= htmlspecialchars($jm['nome']) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="categoria_id" class="p-2 border rounded" required>
      <option value="" disabled selected>Selecione a categoria</option>
      <?php foreach ($categorias as $c): ?>
        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="salary" placeholder="Salário" class="p-2 border rounded md:col-span-2">
    <textarea name="description" placeholder="Descrição" class="p-2 border rounded md:col-span-2" rows="4"></textarea>

    <div class="md:col-span-2 flex justify-end gap-2">
      <a href="/empresas/dashboard" class="px-3 py-2 border rounded">Cancelar</a>
      <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Salvar</button>
    </div>
  </form>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const salaryInput = document.querySelector('input[name="salary"]');
    if (!salaryInput) return;

    salaryInput.addEventListener('input', (e) => {
      let v = e.target.value.replace(/\D/g, "");
      if (!v) {
        e.target.value = "";
        return;
      }
      v = (v / 100).toFixed(2) + "";
      v = v.replace(".", ",");
      v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      e.target.value = "R$ " + v;
    });

    salaryInput.form.addEventListener('submit', () => {
      const raw = salaryInput.value;
      salaryInput.value = raw.replace(/[^\d,]/g, "").replace(",", ".");
    });
  });
</script>