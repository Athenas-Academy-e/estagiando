<?php // Lista de vagas ?>

<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-7xl mx-auto px-6">

    <div class="mb-10 text-center">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Vagas de Estágio</h1>
      <p class="text-gray-600">Busque oportunidades disponíveis e encontre o estágio ideal para você.</p>
    </div>

    <!-- 🔍 Filtros -->
    <form method="GET" action="/vagas" class="bg-white shadow-md rounded-xl p-6 mb-8">
      <div class="grid md:grid-cols-4 gap-4">
        <div>
          <label class="text-sm text-gray-600">Palavra-chave</label>
          <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            placeholder="Ex: marketing, TI, engenharia..."
            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
          <label class="text-sm text-gray-600">Localidade</label>
          <input type="text" name="loc" value="<?= htmlspecialchars($_GET['loc'] ?? '') ?>"
            placeholder="Ex: Juiz de Fora, MG"
            class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
          <label class="text-sm text-gray-600">Tipo</label>
          <select name="type" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
            <option value="">Todos</option>
            <?php foreach ($metodosTrabalho as $metodo): ?>
              <option value="<?= htmlspecialchars($metodo['nome']) ?>" <?= ($_GET['type'] ?? '') === $metodo['nome'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($metodo['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="text-sm text-gray-600">Ordenar por</label>
          <select name="sort" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
            <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Mais recentes</option>
            <option value="oldest" <?= ($_GET['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>Mais antigas</option>
          </select>
        </div>
      </div>

      <div class="mt-6 flex justify-between">
        <button id="clearFilters" type="button"
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
          Limpar filtros
        </button>
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition">
          Buscar vagas
        </button>
      </div>
    </form>

    <?php if (empty($vagas)): ?>
      <div class="bg-white shadow-md rounded-xl p-8 text-center">
        <p class="text-gray-600 text-lg">Nenhuma vaga encontrada com os filtros aplicados.</p>
      </div>
    <?php else: ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($vagas as $vaga): ?>
          <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100">
            <div class="p-6 flex flex-col h-full justify-between">
              <div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">
                  <?= htmlspecialchars($vaga['title'] ?? 'Título não informado') ?>
                </h2>
                <p class="text-sm text-gray-500 mb-2">
                  <span class="font-medium"><?= htmlspecialchars($vaga['empresa_nome'] ?? $vaga['empresa_razao'] ?? 'Empresa não informada') ?></span>
                </p>
                <p class="text-gray-600 text-sm mb-4">
                  <?= htmlspecialchars($vaga['location'] ?? 'Local não informado') ?>
                </p>
                <p class="text-gray-700 text-sm line-clamp-3 mb-4">
                  <?= nl2br(htmlspecialchars($vaga['description'] ?? '')) ?>
                </p>
              </div>
              <div class="flex items-center justify-between mt-auto">
                <span class="text-xs bg-blue-100 text-blue-700 font-medium px-3 py-1 rounded-full">
                  <?= htmlspecialchars($vaga['type'] ?? 'Não especificado') ?>
                </span>
                <a href="/vagas/detalhe?id=<?= $vaga['id'] ?>"
                  class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                  Ver detalhes →
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</main>

<script>
document.getElementById('clearFilters')?.addEventListener('click', (e) => {
  e.preventDefault();
  const url = new URL(window.location.href);
  url.search = '';
  window.history.replaceState({}, '', url);
  window.location.reload();
});
</script>
