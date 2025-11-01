<main class="bg-gray-50 min-h-screen py-12">
  <div class="max-w-5xl mx-auto px-6">

    <div class="flex justify-between items-center mb-10">
      <div class="flex items-center gap-4">
        <a href="/admin/dashboard" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
          ← Voltar
        </a>
        <h1 class="text-3xl font-bold text-[#0a1837]">Gerenciar Administradores</h1>
      </div>

      <a href="/admin/criar" class="bg-[#97dd3a] text-white px-4 py-2 rounded-lg shadow hover:bg-[#aafc4f] transition">
        + Novo
      </a>
    </div>

    <!-- ✅ Mensagem simples de sucesso ou erro -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6 shadow">
        <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
      </div>
    <?php elseif (!empty($_SESSION['flash_error'])): ?>
      <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-3 px-5 mb-6 shadow">
        <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
      </div>
    <?php endif; ?>

    <!-- 📋 Tabela de administradores -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-mail</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($admins as $a): ?>
            <tr>
              <td class="px-6 py-4 text-gray-800"><?= htmlspecialchars($a['nome']) ?></td>
              <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($a['email']) ?></td>
              <td class="px-6 py-4">
                <?php if ($a['status'] === 'S'): ?>
                  <span class="text-green-600 font-semibold">Ativo</span>
                <?php else: ?>
                  <span class="text-red-600 font-semibold">Inativo</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-right space-x-3">
                <!-- Editar -->
                <form action="/admin/editar" method="POST" class="inline-block">
                  <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                  <button type="submit" class="text-blue-600 hover:underline">Editar</button>
                </form>

                <!-- Desativar -->
                <?php if ($a['status'] === 'S'): ?>
                  <form action="/admin/desativar" method="POST" class="inline-block"
                        onsubmit="return confirm('Deseja realmente desativar este administrador?');">
                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                    <button type="submit" class="text-red-600 hover:underline">Desativar</button>
                  </form>
                <?php else: ?>
                  <!-- Reativar -->
                  <form action="/admin/ativar" method="POST" class="inline-block"
                        onsubmit="return confirm('Deseja reativar este administrador?');">
                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                    <button type="submit" class="text-green-600 hover:underline">Ativar</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</main>
