<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-lg mx-auto bg-white p-8 rounded-2xl shadow">

    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-[#0a1837]">Editar Administrador</h1>
      <a href="/admin/gerenciar"
        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow transition">← Voltar</a>
    </div>

    <?php if (!empty($success)): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php elseif (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-3 px-5 mb-6">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <input type="hidden" name="id" value="<?= (int)$_SESSION['admin_id'] ?>">
      <div>
        <label class="block text-gray-700 font-medium mb-1">Nome</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($admin['nome']) ?>" required
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">E-mail</label>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Nova Senha <span class="text-gray-400 text-sm">(opcional)</span></label>
        <input type="password" name="senha" placeholder="Deixe em branco para manter"
          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
      </div>

      <button type="submit"
        class="w-full bg-[#97dd3a] text-white py-2 rounded-lg font-semibold hover:bg-[#aafc4f] transition">
        Salvar Alterações
      </button>
    </form>

  </div>
</main>