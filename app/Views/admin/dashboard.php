<main class="bg-gray-50 min-h-screen py-10 overflow-hidden">
  <div class="max-w-7xl mx-auto px-6">

    <!-- Cabeçalho -->
    <h1 class="text-3xl font-bold text-[#0a1837] mb-4">Painel do Administrador</h1>
    <p class="text-gray-600 mb-8">👋 Olá, <span class="font-semibold"><?= htmlspecialchars($_SESSION['admin_nome']) ?></span>!<br>
      Aqui está um resumo geral do sistema Estagiando.</p>

    <!-- Cards resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
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
      <div class="bg-white shadow-md rounded-xl py-6 text-center">
        <p class="text-gray-500">Publicidades</p>
        <p class="text-3xl font-bold text-[#0a1837]"><?= htmlspecialchars($totalPublicidade ?? 0) ?></p>
      </div>
    </div>

    <!-- Tabs (botões) -->
    <div class="flex flex-wrap gap-4 justify-center mb-8">
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="empresas">Empresas</button>
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="profissionais">Profissionais</button>
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="vagas">Vagas</button>
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="categoria">Categorias</button>
      <button class="tab-btn bg-[#97dd3a] hover:bg-[#aafc4d] text-[#0a1837] font-semibold px-6 py-3 rounded-lg shadow transition" data-type="publicidade">Publicidade</button>
      <button class="tab-btn bg-[#0a1837] text-white font-semibold px-6 py-3 rounded-lg shadow transition" data-type="admins">Administradores</button>
    </div>

    <!-- Tabela dinâmica -->
  </div>
  <div id="dataContainer" class="bg-white p-6 rounded-2xl shadow-lg text-sm text-gray-700 mx-[4px] w-[calc(100vw-8px)] ">
  </div>
  <!-- 🔹 Modal de Edição -->
  <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-xl max-h-[90vh] flex flex-col relative">

      <!-- Cabeçalho -->
      <div class="flex justify-between items-start mb-2 p-6 border-b">
        <h2 id="modalTitle" class="text-xl font-semibold text-[#0a1837]">Editar Registro</h2>
        <button id="closeModal" class="text-gray-500 hover:text-red-600 text-2xl leading-none">&times;</button>
      </div>

      <!-- Conteúdo rolável -->
      <div class="overflow-y-auto px-6 pb-6 flex-1">
        <form id="editForm" class="space-y-4" enctype="multipart/form-data">
          <input type="hidden" name="id" id="edit-id">
          <input type="hidden" name="type" id="edit-type">

          <!-- Campos dinâmicos -->
          <div id="dynamicFields"></div>

          <!-- Rodapé fixo -->
          <div class="flex justify-end mt-6 gap-3  bg-white pt-4 border-t">
            <button type="button" id="cancelEdit" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">Cancelar</button>
            <button type="submit" class="bg-[#97dd3a] hover:bg-[#85c334] text-white px-4 py-2 rounded-lg">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const dataContainer = document.getElementById("dataContainer");
    const tabs = document.querySelectorAll(".tab-btn");
    const modal = document.getElementById('editModal');
    const closeModal = document.getElementById('closeModal');
    const cancelEdit = document.getElementById('cancelEdit');
    const form = document.getElementById('editForm');
    const dynamicFields = document.getElementById('dynamicFields');
    const modalTitle = document.getElementById('modalTitle');
    let activeType = "admins";

    // === Funções base ===
    const setActive = (btn) => {
      tabs.forEach(b => {
        b.classList.remove("bg-[#0a1837]", "text-white");
        b.classList.add("bg-[#97dd3a]", "text-[#0a1837]");
      });
      btn.classList.remove("bg-[#97dd3a]", "text-[#0a1837]");
      btn.classList.add("bg-[#0a1837]", "text-white");
    };

    const formatDate = (value) => {
      if (!value) return '';
      const date = new Date(value);
      if (isNaN(date)) return value;
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}/${month}/${year}`;
    };

    // === Carregar dados da aba ===
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
          let html = `<div class="overflow-x-auto">
          <table class="min-w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-100 text-center">
            <tr>
              ${headers.map(h => `<th class="p-3 border-b font-semibold capitalize">${h}</th>`).join('')}
              <th class="p-3 border-b font-semibold">Ações</th>
            </tr>
          </thead>
          <tbody>
            ${data.map(row => `
              <tr class="border-b hover:bg-gray-50 transition text-center">
                ${headers.map(h => {
                  let value = row[h] ?? '';
                  if (/data|date/i.test(h) && value) value = formatDate(value);
                  return `<td class="p-3">${value}</td>`;
                }).join('')}
                <td class="p-3 flex flex-wrap gap-2 justify-center">
                  <button class="edit-btn bg-blue-600 text-white px-3 py-1 rounded shadow" data-id="${row.id}" data-type="${type}">Editar</button>
                  ${
    type === 'vagas'
      ? (() => {
          const expiracao = row.data_expiracao ? new Date(row.data_expiracao) : null;
          const agora = new Date();
          const expirada = expiracao && expiracao < agora;

          if (expirada) {
            return `<button class="reactivar-btn bg-gray-600 text-white px-3 py-1 rounded shadow" 
                      data-id="${row.id}" data-type="${type}">
                      Reativar Vaga
                    </button>`;
          } else {
            return `<button class="toggle-btn bg-yellow-500 text-white px-3 py-1 rounded shadow" 
                      data-id="${row.id}" data-type="${type}">
                      ${row.status === 'S' ? 'Desativar' : 'Ativar'}
                    </button>`;
          }
        })()
      : ''
  }
                  <button class="delete-btn bg-red-600 text-white px-3 py-1 rounded shadow" data-id="${row.id}" data-type="${type}">Excluir</button>
                  ${type === 'vagas' ? `
                    <a href="/admin/candidatos?vaga=${row.id}" class="bg-green-600 text-white px-3 py-1 rounded shadow hover:bg-green-700 transition">Candidatos</a>` : ''}
                </td>
              </tr>`).join('')}
          </tbody>
        </table>
      </div>`;
          dataContainer.innerHTML = html;
        })
        .catch(() => {
          dataContainer.innerHTML = `<p class="text-center text-red-500 py-6">Erro ao carregar dados.</p>`;
        });
    };

    // === Tabs ===
    tabs.forEach(btn => {
      btn.addEventListener("click", () => {
        setActive(btn);
        activeType = btn.dataset.type;
        loadData(activeType);
      });
    });

    // === Ativar / Desativar ===
    dataContainer.addEventListener("click", async e => {
      if (e.target.classList.contains("toggle-btn")) {
        const id = e.target.dataset.id;
        const type = e.target.dataset.type;

        try {
          const res = await fetch("/admin/toggleStatus", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${id}&type=${type}`
          });

          const text = await res.text(); // 🧩 Ler como texto primeiro (debug seguro)

          const result = JSON.parse(text); // Só parseia se for JSON válido

          if (result.success) {
            showToast(`✅ ${result.message}`, 'green');
            loadData(activeType);
          } else {
            showToast(`❌ ${result.message || 'Erro ao alternar status'}`, 'red');
          }
        } catch (err) {
          console.error('Erro no toggle:', err);
          showToast('⚠️ Erro inesperado no servidor.', 'red');
        }
      }
    });
    // === Reativar Vaga Expirada ===
dataContainer.addEventListener("click", async e => {
  if (e.target.classList.contains("reactivar-btn")) {
    const id = e.target.dataset.id;
    const type = e.target.dataset.type;

    if (confirm("Deseja reativar esta vaga expirada?")) {
      try {
        const res = await fetch("/admin/reactivarVaga", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id=${id}&type=${type}`
        });
        const result = await res.json();
        if (result.success) {
          showToast("✅ Vaga reativada com sucesso!", "green");
          loadData(activeType);
        } else {
          showToast(`❌ ${result.message || 'Erro ao reativar vaga'}`, "red");
        }
      } catch (err) {
        console.error(err);
        showToast("⚠️ Erro inesperado ao reativar vaga.", "red");
      }
    }
  }
});

    // === Excluir ===
    dataContainer.addEventListener("click", e => {
      if (e.target.classList.contains("delete-btn")) {
        if (confirm("Deseja realmente excluir este registro?")) {
          const id = e.target.dataset.id;
          const type = e.target.dataset.type;
          fetch("/admin/delete", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${id}&type=${type}`
          }).then(() => loadData(activeType));
        }
      }
    });

    // === Editar (abrir modal e buscar dados) ===
    dataContainer.addEventListener("click", async e => {
      if (e.target.classList.contains("edit-btn")) {
        const id = e.target.dataset.id;
        const type = e.target.dataset.type;

        try {
          const res = await fetch(`/admin/getRegistroAjax?type=${type}&id=${id}`);
          const result = await res.json();

          if (!result.success) {
            alert("Erro ao carregar os dados.");
            return;
          }

          const data = result.data;
          modalTitle.textContent = `✏️ Editar ${type.charAt(0).toUpperCase() + type.slice(1)}`;
          modal.classList.remove('hidden');
          dynamicFields.innerHTML = '';

          // Cria campos dinamicamente (inteligente)
          for (const [key, value] of Object.entries(data)) {
            if (['id', 'senha', 'password'].includes(key)) continue;

            const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            let inputField = '';

            // 🖼️ Campo de imagem
            if (/foto|logo|imagem|path|caminho/i.test(key)) {
              inputField = `
      <input type="file" name="${key}" accept="image/*" id="${key}"
             class="w-full border rounded-lg px-3 py-2 file:mr-3 file:py-2 file:px-3 file:border-0 file:bg-[#97dd3a] file:text-white file:rounded-lg file:cursor-pointer focus:ring-2 focus:ring-[#97dd3a]">
      ${value ? `<p class="text-xs text-gray-500 mt-1">Atual: ${value}</p>` : ''}
    `;
            }

            // 📅 Campo de data
            else if (/data|nascimento|date|_em$/i.test(key)) {
              let val = value && /^\d{4}-\d{2}-\d{2}/.test(value) ? value.split('T')[0] : value ?? '';
              inputField = `<input type="date" name="${key}" value="${val}" id="${key}"
         class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">`;
            }

            // 🏷️ Campo de categoria
            else if (/categoria_nome/i.test(key)) {
              inputField = `
      <select name="categoria_id" id="select-categoria" 
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
        <option value="">Carregando categorias...</option>
      </select>
    `;
              fetch('/admin/fetchData?type=categoria')
                .then(r => r.json())
                .then(categorias => {
                  const select = document.getElementById('select-categoria');
                  select.innerHTML = categorias.map(cat => `
          <option value="${cat.id}" ${cat.nome === value ? 'selected' : ''}>${cat.nome}</option>
        `).join('');
                })
                .catch(() => {
                  document.getElementById('select-categoria').innerHTML = '<option value="">Erro ao carregar</option>';
                });
            }

            // 🧭 Campo de método de trabalho
            else if (/method_nome|metodo_nome/i.test(key)) {
              inputField = `
      <select name="method_id" id="select-metodo" 
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
        <option value="">Carregando métodos...</option>
      </select>
    `;
              fetch('/admin/fetchData?type=metodo')
                .then(r => r.json())
                .then(metodos => {
                  const select = document.getElementById('select-metodo');
                  select.innerHTML = metodos.map(m => `
          <option value="${m.id}" ${m.nome === value ? 'selected' : ''}>${m.nome}</option>
        `).join('');
                })
                .catch(() => {
                  document.getElementById('select-metodo').innerHTML = '<option value="">Erro ao carregar</option>';
                });
            }

            // 📍 Campo de município / localidade
            else if (/municipio|localidade/i.test(key)) {
              inputField = `
      <select name="municipio_id" id="select-municipio" 
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
        <option value="">Carregando municípios...</option>
      </select>
    `;
              fetch('/admin/fetchData?type=localidade')
                .then(r => r.json())
                .then(municipios => {
                  const select = document.getElementById('select-municipio');
                  select.innerHTML = municipios.map(m => `
          <option value="${m.id}" ${m.nome === value ? 'selected' : ''}>${m.nome}</option>
        `).join('');
                })
                .catch(() => {
                  document.getElementById('select-municipio').innerHTML = '<option value="">Erro ao carregar</option>';
                });
            }

            // 🏢 Campo de empresa (com nome_fantasia ou razão social)
            else if (/empresa_nome|empresa|company/i.test(key)) {
              inputField = `
      <select name="company_id" id="select-empresa" 
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
        <option value="">Carregando empresas...</option>
      </select>
    `;
              fetch('/admin/fetchData?type=empresas')
                .then(r => r.json())
                .then(empresas => {
                  const select = document.getElementById('select-empresa');
                  select.innerHTML = empresas.map(emp => {
                    const nomeExibido = emp.nome_fantasia && emp.nome_fantasia.trim() !== '' ?
                      emp.nome_fantasia :
                      emp.razao_social;
                    return `
            <option value="${emp.id}" ${nomeExibido === value ? 'selected' : ''}>
              ${nomeExibido}
            </option>`;
                  }).join('');
                })
                .catch(() => {
                  document.getElementById('select-empresa').innerHTML = '<option value="">Erro ao carregar</option>';
                });
            }

            // ✏️ Campo padrão
            else {
              inputField = `
      <input type="text" name="${key}" value="${value ?? ''}" id="${key}"
             class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#97dd3a]">
    `;
            }

            dynamicFields.innerHTML += `
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1" for="${key}">${label}</label>
      ${inputField}
    </div>
  `;
          }

          document.getElementById('edit-id').value = id;
          document.getElementById('edit-type').value = type;
        } catch (error) {
          alert("Erro ao buscar dados.");
        }
      }
    });

    // === Fechar modal ===
    [closeModal, cancelEdit].forEach(el => el.addEventListener('click', () => modal.classList.add('hidden')));

    // === Enviar edição ===
    form.addEventListener('submit', async e => {
      e.preventDefault();

      const formData = new FormData(form);

      const response = await fetch('/admin/editarAjax', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        showToast('✅ Registro atualizado com sucesso!', 'green');
        modal.classList.add('hidden');
        loadData(activeType);
      } else {
        showToast('❌ Erro: ' + result.message, 'red');
      }
    });

    // === Toast bonito ===
    function showToast(msg, color) {
      const toast = document.createElement('div');
      toast.textContent = msg;
      toast.className = `fixed top-5 right-5 bg-${color}-600 text-white px-5 py-3 rounded-lg shadow-lg z-[9999] animate-fadeIn`;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }

    // === Inicial ===
    loadData(activeType);
  });
</script>