<main class="bg-gray-50 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Acesse sua conta</h1>

    <?php if (!empty($erro)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? '') ?>">
      <div class="mb-4">
        <label class="block text-sm text-gray-700 mb-1">E-mail</label>
        <input type="email" name="email" required
               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
      </div>

      <div class="mb-6">
        <label class="block text-sm text-gray-700 mb-1">Senha</label>
        <input type="password" name="senha" required
               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
        Entrar
      </button>
    </form>

    <div class="text-center mt-6 text-sm">
      <p class="text-gray-500">
        Ainda não tem uma conta?
        <a href="/cadastro" class="text-blue-600 hover:underline">Cadastre-se</a>
      </p>
    </div>
  </div>
</main>
