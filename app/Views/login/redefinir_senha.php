<main class="bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Redefinir senha</h1>

    <?php if (!empty($erro)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm text-center">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="tipo" value="<?= htmlspecialchars($_GET['tipo'] ?? 'profissional') ?>">

      <div class="mb-6">
        <label class="block text-sm text-gray-700 mb-1">Nova senha</label>
        <input type="password" name="senha" required minlength="6"
               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
               placeholder="Digite sua nova senha">
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
        Redefinir senha
      </button>
    </form>

    <div class="text-center mt-6 text-sm">
      <p class="text-gray-500">
        Lembrou sua senha?
        <a href="/login" class="text-blue-600 hover:underline">Voltar para o login</a>
      </p>
    </div>
  </div>
</main>
