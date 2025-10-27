<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-4xl mx-auto px-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
      👋 Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
    </h1>

    <div class="bg-white rounded-xl shadow p-6">
      <p class="text-gray-600">Bem-vindo à sua área do profissional.</p>
      <p class="mt-2 text-sm text-gray-500">
        Aqui você poderá acompanhar suas candidaturas, atualizar seus dados e visualizar novas vagas.
      </p>
    </div>
  </div>
</main>
