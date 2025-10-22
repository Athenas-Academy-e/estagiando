<?php
if (!isset($vaga) || !$vaga) {
  echo "<main class='max-w-3xl mx-auto py-20 text-center text-gray-600'>
          <h1 class='text-2xl font-bold mb-4'>Vaga não encontrada 😕</h1>
          <a href='/vagas' class='text-blue-600 hover:underline'>Voltar às vagas</a>
        </main>";
  return;
}
?>

<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">
      Candidatura para <?= htmlspecialchars($vaga['title']) ?>
    </h1>
    <p class="text-gray-600 mb-6">
      <?= htmlspecialchars($vaga['empresa_nome'] ?? '') ?> — 
      <?= htmlspecialchars($vaga['location'] ?? '') ?>
    </p>

    <?php if (isset($_GET['success'])): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        🎉 Candidatura enviada com sucesso!
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-5">

      <div>
        <label class="block text-sm font-medium text-gray-700">Nome completo</label>
        <input type="text" name="nome" required
               class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">E-mail</label>
        <input type="email" name="email" required
               class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Telefone</label>
        <input type="text" name="telefone"
               class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Mensagem (opcional)</label>
        <textarea name="mensagem" rows="4"
                  class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Fale brevemente sobre seu interesse na vaga..."></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Anexar currículo (PDF ou DOC)</label>
        <input type="file" name="curriculo" accept=".pdf,.doc,.docx"
               class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                      file:rounded-lg file:border-0 file:text-sm file:font-semibold
                      file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
      </div>

      <div class="flex justify-end">
        <button type="submit"
                class="bg-blue-600 text-white font-medium px-6 py-2 rounded-lg hover:bg-blue-700 transition">
          Enviar candidatura
        </button>
      </div>
    </form>

    <div class="mt-6">
      <a href="/vagas?id=<?= $vaga['id'] ?>" class="text-blue-600 hover:underline">
        ← Voltar à vaga
      </a>
    </div>
  </div>
</main>
