<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-6xl mx-auto px-6">
    <!-- Saudação e Logo -->
    <div class="flex justify-between items-center mb-8">
      <div class="flex items-center gap-4">

        <?php if (!empty($_SESSION['empresa_logo'])): ?>
          <img src="<?= htmlspecialchars($_SESSION['empresa_logo']) ?>"
            alt="<?= htmlspecialchars($_SESSION['empresa_nome']) ?>"
            class="w-14 h-14 object-cover rounded-full shadow-md border border-gray-200">
        <?php else: ?>
          <div class="w-14 h-14 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">
            ?
          </div>
        <?php endif; ?>

        <h1 class="font-bold text-gray-800 sm:text-base md:text-xl lg:text-2xl">
          Bem-vindo(a), <?= htmlspecialchars($_SESSION['empresa_nome']) ?>
        </h1>
      </div>

      <?php if (isset($_SESSION['empresa_id'])): ?>
        <div class="flex items-center gap-3">
          <a href="/empresas/editarperfil"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md shadow hover:bg-gray-300 transition-all text-sm">
            Alterar Dados
          </a>
          
          <a href="/empresas/alterarlogo"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md shadow hover:bg-gray-300 transition-all text-sm">
            Alterar Logo
          </a>

          <a href="/empresas/publicar"
            class="hidden md:inline-flex lg:inline-flex bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">
            Publicar Vaga
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Conteúdo -->
    <?php if (empty($vagas)): ?>
      <div class="bg-white p-6 rounded-lg shadow text-center text-gray-600">
        Nenhuma vaga cadastrada ainda.
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 auto-rows-fr transition-all duration-300">
        <?php foreach ($vagas as $vaga): ?>
          <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 flex flex-col justify-between h-full min-h-[200px]">
            <!-- Título e Localização -->
            <div>
              <h2 class="text-lg font-semibold text-blue-700 mb-1"><?= htmlspecialchars($vaga['title']) ?></h2>
              <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($vaga['location']) ?></p>
              <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars(date('d/m/Y', strtotime($vaga['posted_at']))) ?></p>
            </div>

            <!-- Candidatos -->
            <p class="text-sm mb-3 text-gray-700">
              <strong><?= $vaga['total_candidatos'] ?></strong> candidato(s)
            </p>

            <!-- Ações -->
            <!-- <div class="flex justify-between items-center mt-auto pt-3 border-t border-gray-100">
              <a href="/empresas/candidatos?vaga=<?= $vaga['id'] ?>"
                class="text-sm text-blue-600 hover:underline">
                Ver candidatos →
              </a> -->

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
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <script>
      console.log(<?= json_encode($_SESSION) ?>);
    </script>
  </div>
</main>