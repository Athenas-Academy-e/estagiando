<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-6xl mx-auto px-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-bold text-gray-800">
        Painel da Empresa - <?= htmlspecialchars($_SESSION['empresa_nome']) ?>
      </h1>
      <a href="/empresas/logout" class="text-red-600 hover:underline">Sair</a>
    </div>

    <?php if (empty($vagas)): ?>
      <div class="bg-white p-6 rounded-lg shadow text-center text-gray-600">
        Nenhuma vaga cadastrada ainda.
      </div>
    <?php else: ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($vagas as $vaga): ?>
          <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-blue-700 mb-1"><?= htmlspecialchars($vaga['title']) ?></h2>
            <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($vaga['location']) ?></p>
            <p class="text-sm mb-3">
              <strong><?= $vaga['total_candidatos'] ?></strong> candidato(s)
            </p>
            <a href="/empresas/candidatos?vaga=<?= $vaga['id'] ?>"
               class="text-sm text-blue-600 hover:underline">Ver candidatos →</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
