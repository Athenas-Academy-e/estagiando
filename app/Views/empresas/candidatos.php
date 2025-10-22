<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-5xl mx-auto px-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">
        Candidatos — <?= htmlspecialchars($vaga['title']) ?>
      </h1>
      <a href="/empresa/dashboard" class="text-blue-600 hover:underline">← Voltar</a>
    </div>

    <?php if (empty($candidatos)): ?>
      <div class="bg-white p-6 rounded-xl shadow text-center text-gray-600">
        Nenhuma candidatura recebida ainda.
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach ($candidatos as $c): ?>
          <div class="bg-white p-6 rounded-xl shadow flex justify-between items-center">
            <div>
              <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($c['nome']) ?></h3>
              <p class="text-sm text-gray-600"><?= htmlspecialchars($c['email']) ?> — <?= htmlspecialchars($c['telefone']) ?></p>
              <?php if (!empty($c['mensagem'])): ?>
                <p class="mt-2 text-gray-700 text-sm"><?= nl2br(htmlspecialchars($c['mensagem'])) ?></p>
              <?php endif; ?>
            </div>
            <div class="text-right">
              <?php if (!empty($c['curriculo'])): ?>
                <a href="<?= $c['curriculo'] ?>" target="_blank"
                   class="text-blue-600 text-sm hover:underline">📄 Ver currículo</a><br>
              <?php endif; ?>
              <span class="text-xs text-gray-500">
                Enviado em <?= date('d/m/Y H:i', strtotime($c['data_envio'])) ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
