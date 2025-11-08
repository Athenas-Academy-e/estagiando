<?php
if (!$vaga) {
  echo "<main class='max-w-3xl mx-auto py-20 text-center text-gray-600'>
          <h1 class='text-2xl font-bold mb-4'>Vaga não encontrada 😕</h1>
          <a href='/vagas' class='text-blue-600 hover:underline'>Voltar às vagas</a>
        </main>";
  return;
}
?>

<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-4xl mx-auto px-6 bg-white shadow-md rounded-2xl p-8">

    <!-- Título e empresa -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($vaga['title']) ?></h1>
      <p class="text-lg text-gray-600"><?= htmlspecialchars($vaga['empresa_nome'] ?? 'Empresa não informada') ?></p>
    </div>

    <!-- Local e tipo -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
      <span class="inline-block bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full">
        <?= htmlspecialchars($vaga['type'] ?? 'Não especificado') ?>
      </span>
      <?php if (!empty($vaga['location'])): ?>
        <span class="inline-block bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1 rounded-full">
          <?= htmlspecialchars($vaga['location']) ?>
        </span>
      <?php endif; ?>
      <?php if (!empty($vaga['categoria_nome'])): ?>
        <span class="inline-block bg-green-100 text-green-700 text-sm font-medium px-3 py-1 rounded-full">
          <?= htmlspecialchars($vaga['categoria_nome']) ?>
        </span>
      <?php endif; ?>
    </div>

    <!-- Descrição -->
    <div class="prose max-w-none text-gray-700 leading-relaxed mb-8">
      <?= nl2br(htmlspecialchars($vaga['description'] ?? 'Descrição não disponível.')) ?>
    </div>

    <!-- Salário, data e ações -->
    <div class="flex flex-wrap justify-between items-center border-t pt-6 text-gray-600">
      <div>
        <?php if (!empty($vaga['salary'])): ?>
          <?php
          // Converte o valor numérico em formato BRL
          $salario = is_numeric($vaga['salary'])
            ? 'R$ ' . number_format($vaga['salary'], 2, ',', '.')
            : htmlspecialchars($vaga['salary']);
          ?>
          <p class="mb-1"><strong>Salário:</strong> <?= $salario ?></p>
        <?php endif; ?>
        <p><strong>Publicado em:</strong> <?= date('d/m/Y', strtotime($vaga['posted_at'])) ?></p>
      </div>

      <div class="mt-4 md:mt-0">
        <?php $applyClass = isset($_SESSION['empresa_id']) ? 'hidden' : 'block'; ?>
        <a href="/apply?id=<?= $vaga['id'] ?>"
          class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-[#97dd3a] hover:text-white transition <?= $applyClass ?>">
          Candidatar-se
        </a>
        <a href="/vagas"
          class="ml-3 inline-block bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
          Voltar
        </a>
      </div>
    </div>
  </div>
</main>