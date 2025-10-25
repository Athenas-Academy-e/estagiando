<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-6xl mx-auto px-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-bold text-gray-800">
        Painel da Empresa - <?= htmlspecialchars($_SESSION['empresa_nome']) ?>
      </h1>
      <a href="/logout" class="text-red-600 hover:underline font-medium">Sair</a>
    </div>

    <!-- Conteúdo -->
    <?php if (empty($vagas)): ?>
      <div class="bg-white p-6 rounded-lg shadow text-center text-gray-600">
        Nenhuma vaga cadastrada ainda.
      </div>
    <?php else: ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($vagas as $vaga): ?>
          <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 p-6 flex flex-col justify-between">
            
            <!-- Título e Localização -->
            <div>
              <h2 class="text-lg font-semibold text-blue-700 mb-1"><?= htmlspecialchars($vaga['title']) ?></h2>
              <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($vaga['location']) ?></p>
            </div>

            <!-- Candidatos -->
            <p class="text-sm mb-3 text-gray-700">
              <strong><?= $vaga['total_candidatos'] ?></strong> candidato(s)
            </p>

            <!-- Ações -->
            <div class="flex justify-between items-center mt-auto pt-3 border-t border-gray-100">
              <a href="/empresas/candidatos?vaga=<?= $vaga['id'] ?>"
                 class="text-sm text-blue-600 hover:underline">
                 Ver candidatos →
              </a>

              <div class="flex gap-2">
                <!-- Botão Editar -->
                <a href="/empresas/editar?id=<?= $vaga['id'] ?>"
                   class="inline-flex items-center gap-1 bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                   ✏️
                </a>

                <!-- Botão Excluir -->
                <form action="/empresas/excluir?id=<?= $vaga['id'] ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta vaga?');">
                  <button type="submit"
                          class="inline-flex items-center gap-1 bg-red-600 text-white text-sm px-3 py-1.5 rounded-lg hover:bg-red-700 transition">
                    🗑️
                  </button>
                </form>
              </div>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    console.log(<?= json_encode($vagas) ?>);
  </script>
</main>
