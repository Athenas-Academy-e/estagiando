<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-3xl mx-auto bg-white p-8 shadow-lg rounded-2xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Alterar Foto</h2>

    <?php if (!empty($success)): ?>
      <div class="p-3 bg-green-100 text-green-700 rounded mb-4"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="p-3 bg-red-100 text-red-700 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['profissional_logo'])): ?>
      <p class="text-gray-600 mb-2">Foto atual:</p>
      <img src="<?= htmlspecialchars($_SESSION['profissional_logo']) ?>" 
           class="w-32 h-32 object-cover rounded-full border shadow mb-6">
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <label class="block text-gray-700 font-medium">
        Escolha uma nova foto:
        <input type="file" name="foto" accept="image/*" required
          class="mt-2 block w-full border border-gray-300 rounded-lg p-2">
      </label>

      <button type="submit"
        class="bg-[#97dd3a] text-white px-4 py-2 rounded-lg shadow hover:bg-[#9fec3b] transition">
        Atualizar Foto
      </button>
      <a href="/profissional/dashboard" class="px-3 py-2 border rounded">Cancelar</a>
    </form>
  </div>
</main>
