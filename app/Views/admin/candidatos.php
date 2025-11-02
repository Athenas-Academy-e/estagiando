<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-5xl mx-auto px-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">
        Candidatos — <?= htmlspecialchars($vaga['title']) ?>
      </h1>
      <a href="/admin/dashboard" class="text-blue-600 hover:underline">← Voltar</a>
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
              <h2 class="text-base text-gray-800">Nome Completo:</h2>
              <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($c['nome']) ?></h3>
              <?php if (!empty($c['mensagem'])): ?>
                <p class="mt-2 text-gray-700 text-sm"><?= nl2br(htmlspecialchars($c['mensagem'])) ?></p>
              <?php endif; ?>
            </div>
            <div class="text-right">
                <a href="/pdf/curriculo/<?= $c['profissional_id'] ?>" target="_blank"
                  class="text-blue-600 text-sm hover:underline">📄 Ver currículo</a><br>
              <span class="text-xs text-gray-500 block mt-2">
                <?php
                $dataEnvio = new DateTime($c['data_envio'], new DateTimeZone('UTC'));
                $dataEnvio->setTimezone(new DateTimeZone('America/Sao_Paulo'));
                echo 'Enviado em ' . $dataEnvio->format('d/m/Y');
                ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>