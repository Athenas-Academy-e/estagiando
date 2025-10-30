<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-5xl mx-auto px-6">

    <!-- Cabeçalho com foto e nome -->
    <div class="flex justify-between items-center mb-8">
      <div class="flex items-center gap-4">

        <?php if (!empty($_SESSION['profissional_logo'])): ?>
          <img src="<?= htmlspecialchars($_SESSION['profissional_logo']) ?>"
            alt="<?= htmlspecialchars($_SESSION['profissional_nome']) ?>"
            class="w-14 h-14 object-cover rounded-full shadow-md border border-gray-200">
        <?php else: ?>
          <div class="w-14 h-14 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold">
            ?
          </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-gray-800">
          Bem-vindo(a), <?= htmlspecialchars($_SESSION['profissional_nome']) ?>
        </h1>
      </div>

      <?php if (isset($_SESSION['profissional_id'])): ?>
        <div class="flex items-center gap-3">
          <a href="/profissional/editarperfil"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md shadow hover:bg-gray-300 transition-all text-sm">
            Alterar Dados
          </a>

          <a href="/profissional/alterarlogo"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md shadow hover:bg-gray-300 transition-all text-sm">
            Alterar Foto
          </a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Mensagem de sucesso -->
    <?php if (!empty($mensagem)): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6" role="alert">
        <?= htmlspecialchars($mensagem) ?>
      </div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="mb-6 flex border-b border-gray-200">
      <button id="tabVagas" class="tab-btn px-4 py-2 text-green-600 border-b-2 border-green-600 font-semibold focus:outline-none">
        💼 Vagas (<?= $totalCandidaturas ?>)
      </button>
      <button id="tabCurriculo" class="tab-btn px-4 py-2 text-gray-500 hover:text-green-600 focus:outline-none">
        🧾 Currículo
      </button>
    </div>

    <!-- Conteúdo: VAGAS -->
    <div id="conteudoVagas" class="tab-content">
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 Vagas onde você se candidatou</h2>

        <?php if (empty($candidaturas)): ?>
          <p class="text-gray-500 text-sm">Você ainda não se candidatou a nenhuma vaga.</p>
        <?php else: ?>
          <div class="grid gap-4">
            <?php foreach ($candidaturas as $c): ?>
              <div class="border rounded-lg flex justify-between items-center hover:shadow transition bg-gray-50 p-2">
                <div>
                  <h3 class="font-semibold text-gray-800 text-lg"><?= htmlspecialchars($c['vaga_titulo']) ?></h3>
                  <p class="text-sm text-gray-500">
                    <?= htmlspecialchars($c['empresa_nome']) ?> — <?= htmlspecialchars($c['location']) ?>
                  </p>
                  <p class="text-xs text-gray-400 mt-1">
                    Candidatado em <?= date('d/m/Y', strtotime($c['data_envio'])) ?>
                  </p>
                </div>
                <a href="/vagas/detalhe?id=<?= $c['vaga_id'] ?>"
                  class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                  Ver vaga →
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Conteúdo: CURRÍCULO -->
    <div id="conteudoCurriculo" class="tab-content hidden">


      <div class="bg-white rounded-xl shadow p-6 mb-10">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
          <h2 class="text-lg font-semibold text-gray-800">📝 Criar / Editar Currículo</h2>
          <!-- <div class="flex gap-2">
            <?php // if (!empty($curriculo)): 
            ?>
              <a href="/pdf/curriculo" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                📄 Baixar PDF
              </a>
              <a href="/pdf/view" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow">
                👁️ Visualizar PDF
              </a>
            <?php // endif; 
            ?>
          </div> -->
        </div>

        <form method="POST" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Nome completo</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($curriculo['nome'] ?? $_SESSION['profissional_nome']) ?>"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Resumo profissional</label>
            <textarea name="resumo" rows="3"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400"><?= htmlspecialchars($curriculo['resumo'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Experiência</label>
            <textarea name="experiencia" rows="3"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400"><?= htmlspecialchars($curriculo['experiencia'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Formação</label>
            <textarea name="formacao" rows="3"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400"><?= htmlspecialchars($curriculo['formacao'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Cursos Complementares</label>
            <textarea name="cursos" rows="3"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400"><?= htmlspecialchars($curriculo['cursos'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Habilidades</label>
            <textarea name="habilidades" rows="3"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400"><?= htmlspecialchars($curriculo['habilidades'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Informações Adicionais</label>
            <textarea name="adicionais" rows="3"
              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-400 focus:border-green-400"><?= htmlspecialchars($curriculo['adicionais'] ?? '') ?></textarea>
          </div>

          <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow">
            💾 Salvar Currículo
          </button>
        </form>
      </div>
    </div>
  </div>
</main>

<!-- Script para alternar as abas -->
<script>
  const tabVagas = document.getElementById('tabVagas');
  const tabCurriculo = document.getElementById('tabCurriculo');
  const conteudoVagas = document.getElementById('conteudoVagas');
  const conteudoCurriculo = document.getElementById('conteudoCurriculo');

  function ativarTab(tab) {
    if (tab === 'vagas') {
      tabVagas.classList.add('text-green-600', 'border-b-2', 'border-green-600', 'font-semibold');
      tabCurriculo.classList.remove('text-green-600', 'border-b-2', 'border-green-600', 'font-semibold');
      conteudoVagas.classList.remove('hidden');
      conteudoCurriculo.classList.add('hidden');
    } else {
      tabCurriculo.classList.add('text-green-600', 'border-b-2', 'border-green-600', 'font-semibold');
      tabVagas.classList.remove('text-green-600', 'border-b-2', 'border-green-600', 'font-semibold');
      conteudoCurriculo.classList.remove('hidden');
      conteudoVagas.classList.add('hidden');
    }
  }

  tabVagas.addEventListener('click', () => ativarTab('vagas'));
  tabCurriculo.addEventListener('click', () => ativarTab('curriculo'));
  ativarTab('vagas');

  const msgSucesso = document.querySelector('[role="alert"]');
  if (msgSucesso) {
    setTimeout(() => {
      msgSucesso.classList.add('opacity-0', 'transition-opacity', 'duration-700');
      setTimeout(() => msgSucesso.remove(), 700); // remove do DOM após o fade
    }, 3000);
  }
</script>