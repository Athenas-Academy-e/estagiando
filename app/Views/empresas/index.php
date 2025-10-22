<section class="bg-gray-50 min-h-screen py-10">
  <div class="max-w-7xl mx-auto px-6">
    <h1 class="text-3xl font-bold text-[#0a1837] mb-8 text-center">Empresas</h1>

    <div class="flex flex-col md:flex-row gap-10">
      <!-- 🔹 Filtros laterais -->
      <aside class="md:w-1/4 bg-white p-6 rounded-xl shadow">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-700">Filtre sua busca</h2>

          <!-- 🔹 Botão Limpar Filtros  -->
          <button
            type="button"
            id="clearFilters"
            class="text-sm bg-[#97dd3a] hover:bg-[#a9f84f] text-white px-3 py-1.5 rounded-full shadow transition duration-200 flex items-center gap-1">
            Limpar
          </button>
        </div>

        <!-- Segmento -->
        <div class="mb-6">
          <h3 class="font-medium text-gray-600 mb-2">Segmento</h3>
          <form method="get" class="space-y-2">
            <?php foreach ($categorias as $cat): ?>
              <label class="flex items-center space-x-2 text-sm text-gray-700">
                <input
                  type="radio"
                  name="categoria"
                  value="<?= $cat['id'] ?>"
                  <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['id']) ? 'checked' : '' ?>
                  class="text-blue-600 border-gray-300 focus:ring-[#0a1837]"
                  onchange="this.form.submit()">
                <span><?= htmlspecialchars($cat['nome']) ?></span>
              </label>
            <?php endforeach; ?>
          </form>
        </div>

        <!-- Localização -->
        <div>
          <h3 class="font-medium text-gray-600 mb-2">Localização</h3>
          <form method="get" class="space-y-2 max-h-56 overflow-y-auto">
            <?php foreach ($locais as $l): ?>
              <label class="flex items-center space-x-2 text-sm text-gray-700">
                <input
                  type="radio"
                  name="local"
                  value="<?= htmlspecialchars($l['nome']) ?>"
                  <?= (isset($_GET['local']) && $_GET['local'] == $l['nome']) ? 'checked' : '' ?>
                  class="text-blue-600 border-gray-300 focus:ring-[#0a1837]"
                  onchange="this.form.submit()">
                <span><?= htmlspecialchars($l['nome']) ?> - <?= htmlspecialchars($l['estado']) ?></span>
              </label>
            <?php endforeach; ?>
          </form>
        </div>
      </aside>

      <!-- 🔹 Lista de empresas -->
      <main class="flex-1">
        <!-- Campo de busca -->
        <form method="get" class="flex items-center mb-6">
          <input
            type="text"
            name="q"
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            placeholder="Buscar empresa"
            class="flex-grow border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#0a1837]">
          <button
            type="submit"
            class="ml-3 bg-[#0a1837] text-white rounded-full px-4 py-2 hover:bg-[#122c5c] transition">
            🔍
          </button>
        </form>

        <p class="text-gray-600 text-sm mb-6">
          Encontramos <?= $totalEmpresas ?> empresa<?= $totalEmpresas == 1 ? '' : 's' ?>.
        </p>

        <!-- Cards de empresas -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php foreach ($empresas as $e): ?>
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
              <div class="flex flex-col items-center text-center">
                <img
                  src="<?= !empty($e['logo'])
                          ? '/public/assets/logos/' . htmlspecialchars($e['logo'])
                          : '/public/assets/img/default-company.png' ?>"
                  alt="Logo da empresa"
                  class="w-20 h-20 object-contain mb-3 rounded-full border border-gray-200 shadow-sm">

                <h3 class="font-bold text-lg text-[#0a1837] mb-1">
                  <?= htmlspecialchars($e['nome_fantasia'] ?? $e['razao_social'] ?? 'Empresa sem nome') ?>
                </h3>

                <p class="text-sm text-gray-500 mb-3">
                  <?= htmlspecialchars($e['cidade'] ?? $e['municipio_nome'] ?? 'Local não informado') ?>
                  <?= isset($e['estado']) ? ' - ' . htmlspecialchars($e['estado']) : '' ?>
                </p>

                <?php if (!empty($e['site'])): ?>
                  <a href="<?= htmlspecialchars($e['site']) ?>" target="_blank"
                    class="text-sm text-blue-600 hover:underline mb-3">
                    🌐 Visitar site
                  </a>
                <?php endif; ?>

                <a
                  href="/public/?empresa=<?= $e['id'] ?>"
                  class="inline-block text-[#97dd3a] font-semibold hover:underline mt-2">
                  <?= intval($e['total_vagas'] ?? 0) ?> vaga<?= intval($e['total_vagas'] ?? 0) == 1 ? '' : 's' ?> disponíveis
                </a>
              </div>
            </div>
          <?php endforeach; ?>
          <!-- 🔹 Paginação -->
          <?php if ($totalPages > 1): ?>
            <div class="flex justify-center mt-10 space-x-2">
              <?php if ($page > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">← Anterior</a>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                  class="px-4 py-2 rounded-full <?= $i === $page ? 'bg-[#0a1837] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?> transition">
                  <?= $i ?>
                </a>
              <?php endfor; ?>

              <?php if ($page < $totalPages): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition">Próxima →</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <?php if (empty($empresas)): ?>
            <p class="text-gray-500 text-center col-span-full py-10">
              Nenhuma empresa encontrada para os filtros selecionados.
            </p>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const clearBtn = document.getElementById('clearFilters');

      if (clearBtn) {
        clearBtn.addEventListener('click', () => {
          // 🔹 Limpa campo de busca
          const searchInput = document.querySelector('input[name="q"]');
          if (searchInput) searchInput.value = '';

          // 🔹 Desmarca todos os radios de categoria e local
          document.querySelectorAll('input[type="radio"][name="categoria"], input[type="radio"][name="local"]').forEach(r => {
            r.checked = false;
          });

          // 🔹 Limpa parâmetros da URL (sem recarregar)
          const newUrl = window.location.pathname;
          window.history.replaceState({}, '', newUrl);

          // 🔹 Atualiza listagem sem reload total (AJAX-like)
          // Opcional — recarrega apenas o conteúdo principal (empresas)
          const main = document.querySelector('main');
          if (main) {
            main.style.opacity = '0.3';
            fetch(newUrl)
              .then(res => res.text())
              .then(html => {
                // Extrai apenas o conteúdo da nova lista
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMain = doc.querySelector('main');
                if (newMain) main.innerHTML = newMain.innerHTML;
              })
              .finally(() => main.style.opacity = '1');
          }
        });
      }
    });
  </script>
</section>