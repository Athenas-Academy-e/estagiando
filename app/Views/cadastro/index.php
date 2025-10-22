<section class="min-h-screen bg-gray-50 py-14">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-gray-700 mb-2">Cadastre-se agora mesmo</h2>
    <p class="text-gray-500 mb-8">e tenha acesso ao maior serviço de estagio.</p>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6 animate-fade-in"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-3 px-5 mb-6 animate-fade-in"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Escolha -->
    <div id="selecao" class="flex justify-center gap-6 mb-10">
      <button data-tipo="profissional" class="tipo-btn bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold hover:bg-[#003366] hover:text-white transition">Profissional</button>
      <button data-tipo="empresa" class="tipo-btn bg-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold hover:bg-[#003366] hover:text-white transition">Empresa</button>
    </div>

    <!-- Formulário Profissional -->
    <form id="form-profissional" class="hidden bg-white shadow-lg rounded-2xl p-8 text-left" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="tipo" value="profissional">
      <div class="steps">
        <!-- ETAPA 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Dados Pessoais</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" name="nome" placeholder="Nome completo" class="input" required>
            <input type="text" id="cpf" name="cpf" placeholder="CPF" class="input" required>
            <input type="email" name="email" placeholder="Email" class="input" required>
            <input type="text" id="telefoneProf" name="telefone" placeholder="Telefone" class="input">
            <select name="sexo" class="input">
              <option value="">Sexo</option>
              <option>Masculino</option>
              <option>Feminino</option>
            </select>
            <input type="date" name="nascimento" class="input">
          </div>
          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" id="cepProf" name="cep" placeholder="CEP" class="input" required>
            <input type="text" name="endereco" placeholder="Endereço" class="input" required>
            <input type="text" name="numero" placeholder="Número" class="input" required>
            <input type="text" name="bairro" placeholder="Bairro" class="input" required>
            <input type="text" name="cidade" placeholder="Cidade" class="input" required>
            <input type="text" name="estado" placeholder="Estado (UF)" class="input" required>
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso e Foto</h3>
          <div class="grid md:grid-cols-2 gap-4 mb-6">
            <input type="password" name="senha" placeholder="Senha" class="input" required>
            <input type="password" name="senha_confirm" placeholder="Confirmar Senha" class="input" required>
          </div>
          <div class="text-center">
            <h4 class="font-semibold text-gray-700 mb-3">Foto de Perfil</h4>
            <input type="file" name="foto" id="foto" accept="image/*" class="block mx-auto mb-2">
            <img id="fotoPreview" class="hidden mx-auto rounded-full w-32 h-32 object-cover border-4 border-blue-200 mb-3">
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal">Finalizar</button>
          </div>
        </div>
      </div>
    </form>

    <!-- Formulário Empresa -->
    <form id="form-empresa" class="hidden bg-white shadow-lg rounded-2xl p-8 text-left" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="tipo" value="empresa">
      <div class="steps">
        <!-- ETAPA 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold mb-6 text-center">Dados da Empresa</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" name="razao_social" placeholder="Razão Social" class="input" required>
            <input type="text" name="nome_fantasia" placeholder="Nome Fantasia" class="input" required>
            <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ" class="input" required>
            <input type="email" name="email" placeholder="E-mail" class="input" required>
            <input type="text" id="telefoneEmp" name="telefone1" placeholder="Telefone" class="input">
            <select name="categoria" class="input" required>
              <option value="">Selecione uma categoria</option>
              <?php foreach ($categorias as $c): ?>
                <option value="<?= htmlspecialchars($c['id']) ?>">
                  <?= htmlspecialchars($c['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="text" id="site" name="site" placeholder="Site" class="input">
          <div id="cnpjStatus" class="text-xs text-black my-4"></div>
          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">
            <input type="text" id="cepEmp" name="cep" placeholder="CEP" class="input" required>
            <input type="text" name="endereco" placeholder="Endereço" class="input" required>
            <input type="text" name="numero" placeholder="Número" class="input" required>
            <input type="text" name="bairro" placeholder="Bairro" class="input" required>
            <input type="text" name="cidade" placeholder="Cidade" class="input" required>
            <input type="text" name="estado" placeholder="Estado (UF)" class="input" required>
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div>

        <!-- ETAPA 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso e Logo</h3>
          <div class="grid md:grid-cols-2 gap-4 mb-6">
            <input type="password" name="senha" placeholder="Senha" class="input" required>
            <input type="password" name="senha_confirm" placeholder="Confirmar Senha" class="input" required>
          </div>
          <div class="text-center">
            <h4 class="font-semibold text-gray-700 mb-3">Logo da Empresa</h4>
            <input type="file" name="logo" id="logo" accept="image/*" class="block mx-auto mb-2">
            <img id="logoPreview" class="hidden mx-auto rounded-lg w-32 h-32 object-cover border-4 border-blue-200 mb-3">
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal">Finalizar</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>

<style>
  .input {
    @apply border border-black rounded-full px-4 py-2 text-sm text-black focus:ring-2 focus:ring-blue-600 focus:outline-none w-full placeholder:text-red-500;
  }

  .btn-principal {
    @apply bg-yellow-600 hover:bg-yellow-700 text-white px-10 py-3 rounded-full font-semibold transition;
  }

  .btn-secundario {
    @apply bg-gray-300 hover:bg-gray-400 text-gray-700 px-10 py-3 rounded-full font-semibold transition;
  }

  .animate-fade-in {
    animation: fadeIn .6s ease forwards;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<script src="https://unpkg.com/imask"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
        const tipoBtns = document.querySelectorAll('.tipo-btn');
        const forms = {
          profissional: document.getElementById('form-profissional'),
          empresa: document.getElementById('form-empresa')
        };

        // Alternância entre tipo de cadastro
        tipoBtns.forEach(btn => {
          btn.addEventListener('click', () => {
            tipoBtns.forEach(b => b.classList.remove('bg-[#003366]', 'text-white'));
            btn.classList.add('bg-[#003366]', 'text-white');

            Object.values(forms).forEach(f => f.classList.add('hidden'));
            forms[btn.dataset.tipo].classList.remove('hidden');
          });
        });

        // Controle de etapas (next / prev)
        document.querySelectorAll('form').forEach(form => {
          const steps = form.querySelectorAll('.step');
          let current = 0;
          const showStep = i => steps.forEach((s, idx) => s.classList.toggle('hidden', idx !== i));

          form.querySelectorAll('.next').forEach(btn => btn.addEventListener('click', () => {
            if (current < steps.length - 1) current++;
            showStep(current);
          }));

          form.querySelectorAll('.prev').forEach(btn => btn.addEventListener('click', () => {
            if (current > 0) current--;
            showStep(current);
          }));
        });

        // Pré-visualização da imagem
        const preview = (input, previewEl) => {
          input?.addEventListener('change', () => {
            const file = input.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = e => {
                previewEl.src = e.target.result;
                previewEl.classList.remove('hidden');
              };
              reader.readAsDataURL(file);
            }
          });
        };
        preview(document.getElementById('foto'), document.getElementById('fotoPreview'));
        preview(document.getElementById('logo'), document.getElementById('logoPreview'));

        // Máscaras
        if (window.IMask) {
          IMask(document.getElementById('cpf'), {
            mask: '000.000.000-00'
          });
          IMask(document.getElementById('cnpj'), {
            mask: '00.000.000/0000-00'
          });
          IMask(document.getElementById('telefoneProf'), {
            mask: '(00) 00000-0000'
          });
          IMask(document.getElementById('telefoneEmp'), {
            mask: '(00) 00000-0000'
          });
          IMask(document.getElementById('cepProf'), {
            mask: '00000-000'
          });
          IMask(document.getElementById('cepEmp'), {
            mask: '00000-000'
          });
        }

        // Validação de CNPJ
        const cnpjInput = document.getElementById('cnpj');
        const cnpjStatus = document.getElementById('cnpjStatus');
        cnpjInput?.addEventListener('input', () => {
          const cnpj = cnpjInput.value.replace(/\D/g, '');
          if (cnpj.length === 14) {
            if (validarCNPJ(cnpj)) {
              cnpjStatus.textContent = '✅ CNPJ válido';
              cnpjStatus.className = 'text-xs mb-4 text-green-600 font-medium';
            } else {
              cnpjStatus.textContent = '❌ CNPJ inválido';
              cnpjStatus.className = 'text-xs mb-4 text-red-600 font-medium';
            }
          } else {
            cnpjStatus.textContent = 'Digite o CNPJ completo para validar';
            cnpjStatus.className = 'text-xs mb-4 text-gray-500';
          }
        });

        function validarCNPJ(cnpj) {
          if (!cnpj || cnpj.length !== 14 || /^(\d)\1+$/.test(cnpj)) return false;
          let t = cnpj.length - 2,
            n = cnpj.substring(0, t),
            d = cnpj.substring(t),
            s = 0,
            p = t - 7;
          for (let i = t; i >= 1; i--) {
            s += n.charAt(t - i) * p--;
            if (p < 2) p = 9;
          }
          let r = s % 11 < 2 ? 0 : 11 - (s % 11);
          if (r != d.charAt(0)) return false;
          t++;
          n = cnpj.substring(0, t);
          s = 0;
          p = t - 7;
          for (let i = t; i >= 1; i--) {
            s += n.charAt(t - i) * p--;
            if (p < 2) p = 9;
          }
          r = s % 11 < 2 ? 0 : 11 - (s % 11);
          return r == d.charAt(1);
        }

// 🔍 Autocomplete de CEP (ViaCEP)
        const camposCep = [{
            cep: 'cepProf',
            formId: 'form-profissional'
          },
          {
            cep: 'cepEmp',
            formId: 'form-empresa'
          }
        ];

        camposCep.forEach(({
          cep,
          formId
        }) => {
          const cepInput = document.getElementById(cep);
          const form = document.getElementById(formId);
          if (!cepInput || !form) return;

          const endereco = form.querySelector('[name="endereco"]');
          const bairro = form.querySelector('[name="bairro"]');
          const cidade = form.querySelector('[name="cidade"]');
          const estado = form.querySelector('[name="estado"]');

          const status = document.createElement('p');
          status.className = 'text-xs text-gray-500 mt-1';
          cepInput.parentNode.appendChild(status);

          cepInput.addEventListener('input', async () => {
            const cleanCep = cepInput.value.replace(/\D/g, '');
            if (cleanCep.length === 8) {
              status.textContent = '⏳ Buscando endereço...';
              try {
                const response = await fetch(`https://viacep.com.br/ws/${cleanCep}/json/`);
                const data = await response.json();
                if (data.erro) throw new Error('CEP não encontrado');

                // ✅ Preenche apenas os campos do formulário correto
                endereco.value = data.logradouro || '';
                bairro.value = data.bairro || '';
                cidade.value = data.localidade || '';
                estado.value = data.uf || '';

                status.textContent = '✅ Endereço encontrado!';
                status.className = 'text-xs text-green-600 mt-1';
              } catch {
                status.textContent = '❌ CEP não encontrado.';
                status.className = 'text-xs text-red-600 mt-1';
              }
            }
          });
        });
  });
</script>