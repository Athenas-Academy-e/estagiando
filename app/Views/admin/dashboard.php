<main class="bg-gray-50 min-h-screen py-10">
  <div class="max-w-7xl mx-auto px-6">

    <!-- Cabeçalho -->
    <h1 class="text-3xl font-bold text-[#0a1837] mb-4">Painel do Administrador</h1>
    <p class="text-gray-600 mb-8">👋 Olá, <span class="font-semibold"><?= htmlspecialchars($_SESSION['admin_nome']) ?></span>!<br>
      Aqui está um resumo geral do sistema Estagiando.</p>

    <!-- Cards resumo -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white shadow-md rounded-xl py-6 text-center">
        <p class="text-gray-500">Empresas</p>
        <p class="text-3xl font-bold text-[#0a1837]"><?= htmlspecialchars($totalEmpresas ?? 0) ?></p>
      </div>
      <div class="bg-white shadow-md rounded-xl py-6 text-center">
        <p class="text-gray-500">Profissionais</p>
        <p class="text-3xl font-bold text-[#0a1837]"><?= htmlspecialchars($totalProfissionais ?? 0) ?></p>
      </div>
      <div class="bg-white shadow-md rounded-xl py-6 text-center">
        <p class="text-gray-500">Vagas</p>
        <p class="text-3xl font-bold text-[#0a1837]"><?= htmlspecialchars($totalVagas ?? 0) ?></p>
      </div>
    </div>

    <!-- Tabs (botões) -->
    <div class="flex flex-wrap gap-4 justify-center mb-8">
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="empresas">Empresas</button>
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="profissionais">Profissionais</button>
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="vagas">Vagas</button>
      <button class="tab-btn bg-[#0a1837] text-white font-semibold px-6 py-3 rounded-lg shadow transition" data-type="admins">Administradores</button>
    </div>

    <!-- Tabela dinâmica -->
    <div id="dataContainer" class="bg-white p-6 rounded-2xl shadow-lg overflow-x-auto text-sm text-gray-700"></div>
  </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const dataContainer = document.getElementById("dataContainer");
  const tabs = document.querySelectorAll(".tab-btn");
  let activeType = "admins";

  const setActive = (btn) => {
    tabs.forEach(b => {
      b.classList.remove("bg-[#0a1837]", "text-white");
      b.classList.add("bg-[#97dd3a]", "text-[#0a1837]");
    });
    btn.classList.remove("bg-[#97dd3a]", "text-[#0a1837]");
    btn.classList.add("bg-[#0a1837]", "text-white");
  };

  const loadData = (type) => {
    if (type === "admins") {
      dataContainer.innerHTML = `
        <div class="text-center py-10">
          <p class="text-gray-600 mb-4">A gestão de administradores é feita em uma página dedicada.</p>
          <a href="/admin/gerenciar" class="bg-[#0a1837] text-white px-5 py-2 rounded-lg shadow hover:bg-[#13245c]">Ir para Gerenciar Administradores</a>
        </div>`;
      return;
    }

    fetch(`/admin/fetchData?type=${type}`)
      .then(res => res.json())
      .then(data => {
        if (!Array.isArray(data) || data.length === 0) {
          dataContainer.innerHTML = `<p class="text-center text-gray-500 py-6">Nenhum registro encontrado.</p>`;
          return;
        }

        let headers = Object.keys(data[0]);
        let html = `
          <table class="w-full border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
              <tr>${headers.map(h => `<th class="p-3 border-b font-semibold capitalize">${h}</th>`).join('')}
                  <th class="p-3 border-b font-semibold">Ações</th>
              </tr>
            </thead>
            <tbody>
              ${data.map(row => `
                <tr class="border-b hover:bg-gray-50 transition">
                  ${headers.map(h => `<td class="p-3">${row[h] ?? ''}</td>`).join('')}
                  <td class="p-3 flex gap-2">
                    <button class="toggle-btn bg-yellow-500 text-white px-3 py-1 rounded" data-id="${row.id}" data-type="${type}">${row.status === 'S' ? 'Desativar' : 'Ativar'}</button>
                    <button class="delete-btn bg-red-600 text-white px-3 py-1 rounded" data-id="${row.id}" data-type="${type}">Excluir</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        `;
        dataContainer.innerHTML = html;
      })
      .catch(() => {
        dataContainer.innerHTML = `<p class="text-center text-red-500 py-6">Erro ao carregar dados.</p>`;
      });
  };

  // Troca de abas
  tabs.forEach(btn => {
    btn.addEventListener("click", () => {
      setActive(btn);
      activeType = btn.dataset.type;
      loadData(activeType);
    });
  });

  // Toggle status
  dataContainer.addEventListener("click", e => {
    if (e.target.classList.contains("toggle-btn")) {
      const id = e.target.dataset.id;
      const type = e.target.dataset.type;
      fetch("/admin/toggleStatus", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `id=${id}&type=${type}`
      }).then(() => loadData(activeType));
    }
  });

  // Excluir
  dataContainer.addEventListener("click", e => {
    if (e.target.classList.contains("delete-btn")) {
      if (confirm("Deseja realmente excluir este registro?")) {
        const id = e.target.dataset.id;
        const type = e.target.dataset.type;
        fetch("/admin/delete", {
          method: "POST",
          headers: {"Content-Type": "application/x-www-form-urlencoded"},
          body: `id=${id}&type=${type}`
        }).then(() => loadData(activeType));
      }
    }
  });

  // Inicial
  loadData(activeType);
});
</script>
