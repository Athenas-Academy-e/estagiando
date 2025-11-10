<main class="bg-gray-50 min-h-screen py-10">
  <div class="max-w-3xl mx-auto bg-white shadow-md rounded-xl p-8">
    <h1 class="text-2xl font-bold text-[#0a1837] mb-6"><?= htmlspecialchars($title) ?></h1>

    <?php if (!empty($success)): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
      <input type="hidden" name="type" value="<?= htmlspecialchars($currentType ?? '') ?>">

      <!-- Nome -->
      <div>
        <label for="nome" class="block font-medium text-gray-700">Nome</label>
        <input type="text" id="nome" name="nome" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a] focus:outline-none">
      </div>

      <!-- Imagem -->
      <div>
        <label for="imagem" class="block font-medium text-gray-700">
          <?= ($currentType ?? '') === 'publicidade' ? 'Imagem da Publicidade (obrigatória)' : 'Imagem da Categoria (opcional)' ?>
        </label>
        <input type="file" id="imagem" name="imagem" accept="image/*"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 file:mr-3 file:py-2 file:px-3 file:border-0 file:bg-[#97dd3a] file:text-white file:rounded-lg file:cursor-pointer focus:ring-2 focus:ring-[#97dd3a]">
      </div>

      <!-- Status -->
      <div>
        <label for="status" class="block font-medium text-gray-700">Status</label>
        <select name="status" id="status"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
          <option value="S" selected>Ativo</option>
          <option value="N">Inativo</option>
        </select>
      </div>

      <!-- URL (somente publicidade) -->
      <?php if (($currentType ?? '') === 'publicidade'): ?>
        <div>
          <label for="site" class="block font-medium text-gray-700">URL de redirecionamento</label>
          <input type="url" id="site" name="site" placeholder="https://exemplo.com"
                 class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
        </div>
      <?php endif; ?>

      <div class="flex justify-end gap-3 pt-6 border-t">
        <a href="/admin/dashboard" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Cancelar</a>
        <button type="submit" class="bg-[#97dd3a] hover:bg-[#aafc4d] text-white px-5 py-2 rounded-lg font-medium shadow">
          Salvar
        </button>
      </div>
    </form>
  </div>
</main>
