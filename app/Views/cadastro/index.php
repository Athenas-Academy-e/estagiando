<section class="min-h-screen bg-gray-50 py-14">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-gray-700 mb-2">Cadastre-se agora mesmo</h2>
    <p class="text-gray-500 mb-8">e tenha acesso ao maior serviço de estágio.</p>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 rounded-lg py-3 px-5 mb-6 animate-fade-in"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-700 border border-red-400 rounded-lg py-3 px-5 mb-6 animate-fade-in"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Switch Tipo -->
    <div id="selecao" class="flex justify-center gap-6 mb-10">
      <button data-tipo="profissional"
        class="tipo-btn px-8 py-3 rounded-full font-semibold transition-all duration-300 bg-gray-200 text-gray-700 hover:bg-[#003366] hover:text-white shadow-sm hover:shadow-md active:scale-95">
        Profissional
      </button>

      <button data-tipo="empresa"
        class="tipo-btn px-8 py-3 rounded-full font-semibold transition-all duration-300 bg-gray-200 text-gray-700 hover:bg-[#003366] hover:text-white shadow-sm hover:shadow-md active:scale-95">
        Empresa
      </button>
    </div>

    <!-- ★ FORMULÁRIO PROFISSIONAL ★ -->
    <form id="form-profissional"
      class="hidden bg-white shadow-xl rounded-2xl p-8 text-left transition-all duration-300"
      method="POST" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="tipo" value="profissional">
      <input type="hidden" name="status" value="S">

      <div class="steps">
        <!-- Etapa 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Dados Pessoais</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <input type="text" name="nome" placeholder="Nome completo"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" id="cpf" name="cpf" placeholder="CPF"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="email" name="email" placeholder="Email"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" id="telefoneProf" name="telefone" placeholder="Telefone"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <select name="sexo"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full cursor-pointer bg-white appearance-none pr-10"
              style="background-image:url('data:image/svg+xml,%3Csvg width=\'18\' height=\'18\' stroke=\'%23000\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 0.75rem center;">
              <option value="">Sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
            </select>

            <input type="date" name="nascimento"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>
          </div>

          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 1 Profissional -->

        <!-- Etapa 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <input type="text" id="cepProf" name="cep" placeholder="CEP"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="endereco" placeholder="Endereço"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="numero" placeholder="Número"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="complemento" placeholder="Complemento"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400">

            <input type="text" name="bairro" placeholder="Bairro"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="cidade" placeholder="Cidade"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="estado" placeholder="Estado (UF)"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>
            <p class="cepStatus text-xs text-gray-500 mt-1"></p>
          </div>

          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 2 Profissional -->

        <!-- Etapa 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso & Foto</h3>

          <div class="flex flex-col md:flex-row gap-10 items-start justify-between">
            <!-- SENHA ESQ -->
            <div class="flex-1">
              <label class="block font-semibold mb-1 text-gray-600">Senha</label>
              <div class="relative mb-4">
                <input type="password" name="senha" placeholder="Senha"
                  class="border border-gray-700 rounded-full px-4 pr-12 h-12 text-sm text-gray-900
                  focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400 senha" required>
                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer select-none">👁</span>
              </div>

              <label class="block font-semibold mb-1 text-gray-600">Confirmar Senha</label>
              <div class="relative mb-4">
                <input type="password" name="senha_confirm" placeholder="Confirmar Senha"
                  class="border border-gray-700 rounded-full px-4 pr-12 h-12 text-sm text-gray-900
                  focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400 senha-confirm" required>
                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer select-none">👁</span>
              </div>

              <div class="flex gap-2 justify-start mt-3">
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
              </div>

              <ul class="requisitos text-gray-900 text-sm mt-3 space-y-1 text-left font-semibold">
                <li data-rule="len">❌ Mínimo 8 caracteres</li>
                <li data-rule="upper">❌ Letra maiúscula</li>
                <li data-rule="num">❌ Número</li>
                <li data-rule="spe">❌ Símbolo (!@#$...)</li>
              </ul>

              <p class="confirmMsg text-sm mt-3 font-semibold"></p>
            </div><!-- /Senha -->

            <!-- FOTO DIR -->
            <div class="flex flex-col items-center gap-3 md:w-64">
              <h4 class="font-semibold text-gray-700">Foto de Perfil</h4>
              <input type="file" name="foto" id="foto" accept="image/*"
                class="block mx-2 mb-2">
              <h4 class="font-semibold text-gray-700 text-sm">Tamanho recomendado: <strong>400 × 400 px</strong></h4>
              <img id="fotoPreview" class="hidden mx-auto rounded-full w-20 h-20 object-cover border-4 border-blue-200 shadow-md">
            </div>
          </div><!-- /flex layout -->

          <div class="flex justify-between mt-8">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal finalizar opacity-50 cursor-not-allowed" disabled>Finalizar</button>
          </div>
        </div><!-- /Etapa 3 Profissional -->

      </div><!-- ✅ fechamento .steps -->
    </form><!-- ✅ fechamento form profissional -->

    <!-- ★ FORMULÁRIO EMPRESA ★ -->
    <form id="form-empresa"
      class="hidden bg-white shadow-xl rounded-2xl p-8 text-left transition-all duration-300"
      method="POST" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="tipo" value="empresa">

      <div class="steps">
        <!-- Etapa 1 -->
        <div class="step active">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Dados da Empresa</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <input type="text" name="razao_social" placeholder="Razão Social"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="nome_fantasia" placeholder="Nome Fantasia"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400">


            <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="email" name="email" placeholder="E-mail"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" id="telefoneEmp" name="telefone1" placeholder="Telefone"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <select name="categoria"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full cursor-pointer bg-white appearance-none pr-10"
              style="background-image:url('data:image/svg+xml,%3Csvg width=\'18\' height=\'18\' stroke=\'%230000\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 0.75rem center;" required>
              <option value="">Selecione uma categoria</option>
              <?php foreach ($categorias as $c): ?>
                <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['nome']) ?></option>
              <?php endforeach; ?>
            </select>

            <input type="url" name="site" placeholder="Site"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400">
            <p id="cnpjStatus" class="text-xs text-gray-500 mt-1"></p>
          </div>
          <div class="text-center mt-4">
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 1 Empresa -->

        <!-- Etapa 2 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Endereço</h3>
          <div class="grid md:grid-cols-3 gap-4 mb-6">

            <input type="text" id="cepEmp" name="cep" placeholder="CEP"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>
            <input type="text" name="endereco" placeholder="Endereço"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="numero" placeholder="Número"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="complemento" placeholder="Complemento"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400">

            <input type="text" name="bairro" placeholder="Bairro"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="cidade" placeholder="Cidade"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>

            <input type="text" name="estado" placeholder="Estado (UF)"
              class="border border-gray-700 rounded-full px-4 h-12 text-sm text-gray-900 focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400" required>
            <p class="cepStatus text-xs text-gray-500 mt-1"></p>
          </div>
          <div class="flex justify-between mt-4">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="button" class="btn-principal next">Próximo</button>
          </div>
        </div><!-- /Etapa 2 Empresa -->

        <!-- Etapa 3 -->
        <div class="step hidden">
          <h3 class="text-xl font-semibold text-gray-700 mb-6 text-center">Acesso & Logo</h3>

          <div class="flex flex-col md:flex-row gap-10 items-start justify-between">
            <!-- SENHA -->
            <div class="flex-1">
              <label class="block font-semibold mb-1 text-gray-600">Senha</label>
              <div class="relative mb-4">
                <input type="password" name="senha" placeholder="Senha"
                  class="border border-gray-700 rounded-full px-4 pr-12 h-12 text-sm text-gray-900
                  focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400 senha" required>
                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer select-none">👁</span>
              </div>

              <label class="block font-semibold mb-1 text-gray-600">Confirmar Senha</label>
              <div class="relative mb-4">
                <input type="password" name="senha_confirm" placeholder="Confirmar Senha"
                  class="border border-gray-700 rounded-full px-4 pr-12 h-12 text-sm text-gray-900
                  focus:ring-2 focus:ring-blue-600 w-full placeholder-gray-400 senha-confirm" required>
                <span class="toggle-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 cursor-pointer select-none">👁</span>
              </div>

              <div class="flex gap-2 justify-start mt-3">
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
                <div class="strength-bar w-16 h-2 bg-gray-300 rounded"></div>
              </div>

              <ul class="requisitos text-gray-900 text-sm mt-3 space-y-1 text-left font-semibold">
                <li data-rule="len">❌ Mínimo 8 caracteres</li>
                <li data-rule="upper">❌ Letra maiúscula</li>
                <li data-rule="num">❌ Número</li>
                <li data-rule="spe">❌ Símbolo (!@#$...)</li>
              </ul>

              <p class="confirmMsg text-sm mt-3 font-semibold"></p>
            </div><!-- /Senha -->

            <!-- LOGO -->
            <div class="flex flex-col items-center gap-3 md:w-72">
              <h4 class="font-semibold text-gray-700">Logo da Empresa</h4>
              <input type="file" name="logo" id="logo" accept="image/*"
                class="block mx-2 mb-2">
              <h4 class="font-semibold text-gray-700 text-sm">Tamanho recomendado: <strong>500 × 500 px</strong></h4>
              <img id="logoPreview" class="hidden mx-auto rounded-lg w-20 h-20 object-cover border-4 border-blue-200 shadow-md">
            </div>
          </div><!-- /flex -->

          <div class="flex justify-between mt-8">
            <button type="button" class="btn-secundario prev">Voltar</button>
            <button type="submit" class="btn-principal finalizar opacity-50 cursor-not-allowed" disabled>Finalizar</button>
          </div>
        </div><!-- /Etapa 3 Empresa -->

      </div><!-- ✅ fechamento .steps -->
    </form><!-- ✅ fechamento form empresa -->

  </div>
</section>

<!-- CSS simples para botões (sem @apply) -->
<style>
  .btn-principal {
    background: #ca8a04;
    /* yellow-600 */
    color: #fff;
    border-radius: 9999px;
    padding: 0.75rem 2.5rem;
    font-weight: 600;
    transition: box-shadow .2s, transform .05s, background .2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
  }

  .btn-principal:hover {
    background: #a16207;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
  }

  .btn-principal:active {
    transform: scale(.98);
  }

  .btn-secundario {
    background: #e5e7eb;
    /* gray-200 */
    color: #374151;
    /* gray-700 */
    border-radius: 9999px;
    padding: 0.75rem 2.5rem;
    font-weight: 600;
    transition: box-shadow .2s, transform .05s, background .2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .08);
  }

  .btn-secundario:hover {
    background: #d1d5db;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
  }

  .btn-secundario:active {
    transform: scale(.98);
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
    /* ===== Selecão automática do formulário ===== */
    const savedForm = localStorage.getItem('formType') || 'profissional';
    const tipoBtns = document.querySelectorAll('.tipo-btn');
    const forms = {
      profissional: document.getElementById('form-profissional'),
      empresa: document.getElementById('form-empresa')
    };

    function ativarFormulario(tipo) {
      tipoBtns.forEach(b => {
        b.classList.toggle('bg-[#003366]', b.dataset.tipo === tipo);
        b.classList.toggle('text-white', b.dataset.tipo === tipo);
        b.classList.toggle('bg-gray-200', b.dataset.tipo !== tipo);
        b.classList.toggle('text-gray-700', b.dataset.tipo !== tipo);
      });
      Object.values(forms).forEach(f => f.classList.add('hidden'));
      forms[tipo].classList.remove('hidden');
      inicializarEtapas(forms[tipo]);
      localStorage.setItem('formType', tipo);
    }
    ativarFormulario(savedForm);

    tipoBtns.forEach(btn => {
      btn.addEventListener('click', () => ativarFormulario(btn.dataset.tipo));
    });

    /* ===== Controle de etapas ===== */
    function inicializarEtapas(form) {
      const steps = [...form.querySelectorAll('.step')];
      let current = 0;

      function show(i) {
        steps.forEach((s, idx) => s.classList.toggle('hidden', idx !== i));
      }
      form.querySelectorAll('.next').forEach(btn => btn.onclick = () => {
        if (current < steps.length - 1) {
          current++;
          show(current);
        }
      });
      form.querySelectorAll('.prev').forEach(btn => btn.onclick = () => {
        if (current > 0) {
          current--;
          show(current);
        }
      });
      show(current);
    }
    Object.values(forms).forEach(inicializarEtapas);

    /* ===== Pré-visualização imagens ===== */
    function previewFile(input, img) {
      input?.addEventListener('change', () => {
        const f = input.files?.[0];
        if (!f) return;
        const r = new FileReader();
        r.onload = e => {
          img.src = e.target.result;
          img.classList.remove('hidden');
        };
        r.readAsDataURL(f);
      });
    }
    previewFile(document.getElementById('foto'), document.getElementById('fotoPreview'));
    previewFile(document.getElementById('logo'), document.getElementById('logoPreview'));

    /* ===== Olho da senha ===== */
    document.querySelectorAll('.toggle-eye').forEach(eye => {
      eye.addEventListener('click', () => {
        const input = eye.previousElementSibling;
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        eye.textContent = isPass ? '🙈' : '👁';
      });
    });

    /* ===== Força + Match Senha ===== */
    function verificarSenhas(form) {
      const p = form.querySelector('.senha');
      const c = form.querySelector('.senha-confirm');
      const bars = form.querySelectorAll('.strength-bar');
      const reqs = form.querySelector('.requisitos');
      const msg = form.querySelector('.confirmMsg');
      const btn = form.querySelector('.finalizar');
      if (!p || !c) return;

      function val() {
        let s = p.value,
          e = {
            len: s.length >= 8,
            upper: /[A-Z]/.test(s),
            num: /\d/.test(s),
            spe: /[^A-Za-z0-9]/.test(s)
          };
        Object.entries(e).forEach(([k, v]) => {
          const li = reqs.querySelector(`[data-rule="${k}"]`);
          const text = li.textContent.replace(/^✅ |^❌ /, '');
          li.textContent = `${v?'✅':'❌'} ${text}`;
          li.classList.toggle('text-green-600', v);
          li.classList.toggle('text-red-600', !v);
        });
        let lvl = Object.values(e).filter(Boolean).length;
        bars.forEach((b, i) => b.className = `strength-bar w-16 h-2 rounded ${i<lvl?(lvl<3?'bg-yellow-500':'bg-green-600'):'bg-gray-300'}`);
        const match = s && s === c.value;
        msg.textContent = match ? "✅ Senhas coincidem" : "❌ Senhas não coincidem";
        msg.classList.toggle('text-green-600', match);
        msg.classList.toggle('text-red-600', !match);
        const ok = match && lvl >= 3;
        btn.disabled = !ok;
        btn.classList.toggle('opacity-50', !ok);
        btn.classList.toggle('cursor-not-allowed', !ok);
      }
      p.addEventListener('input', val);
      c.addEventListener('input', val);
      val();
    }
    verificarSenhas(forms.profissional);
    verificarSenhas(forms.empresa);

    /* ===== Validação CNPJ ===== */
    function validarCNPJ(c) {
      c = c.replace(/\D/g, '');
      if (c.length !== 14 || /^(\d)\1+$/.test(c)) return false;
      let t = 12,
        n = c.slice(0, t),
        d = c.slice(t),
        s = 0,
        p = t - 7;
      for (let i = t; i >= 1; i--) {
        s += n[t - i] * p--;
        if (p < 2) p = 9;
      }
      let r = s % 11 < 2 ? 0 : 11 - (s % 11);
      if (r != d[0]) return false;
      t = 13;
      n = c.slice(0, t);
      s = 0;
      p = t - 7;
      for (let i = t; i >= 1; i--) {
        s += n[t - i] * p--;
        if (p < 2) p = 9;
      }
      r = s % 11 < 2 ? 0 : 11 - (s % 11);
      return r == d[1];
    }
    const inCNPJ = document.getElementById('cnpj'),
      msgCNPJ = document.getElementById('cnpjStatus');
    inCNPJ?.addEventListener('input', () => {
      const v = inCNPJ.value.replace(/\D/g, '');
      if (v.length < 14) {
        msgCNPJ.textContent = 'Digite o CNPJ completo';
        msgCNPJ.className = 'text-xs text-gray-500 mt-1';
        return;
      }
      const ok = validarCNPJ(v);
      msgCNPJ.textContent = ok ? '✅ Válido' : '❌ Inválido';
      msgCNPJ.className = `text-xs mt-1 ${ok?'text-green-600':'text-red-600'}`;
    });

    /* ===== ViaCEP (profissional + empresa) ===== */
    const ceps = [{
      id: 'cepProf',
      form: forms.profissional
    }, {
      id: 'cepEmp',
      form: forms.empresa
    }];
    ceps.forEach(({
      id,
      form
    }) => {
      const input = document.getElementById(id);
      if (!input || !form) return;
      const statusEl = input.parentElement.querySelector('.cepStatus'); // pega o <p> irmão logo abaixo
      const endereco = form.querySelector('[name="endereco"]');
      const bairro = form.querySelector('[name="bairro"]');
      const cidade = form.querySelector('[name="cidade"]');
      const estado = form.querySelector('[name="estado"]');

      input.addEventListener('input', async () => {
        const clean = input.value.replace(/\D/g, '');
        if (clean.length !== 8) {
          if (statusEl) {
            statusEl.textContent = '';
            statusEl.className = 'cepStatus text-xs text-gray-500 mt-1';
          }
          return;
        }
        if (statusEl) {
          statusEl.textContent = 'Buscando endereço...';
          statusEl.className = 'cepStatus text-xs text-gray-500 mt-1';
        }
        try {
          const resp = await fetch(`https://viacep.com.br/ws/${clean}/json/`);
          const data = await resp.json();
          if (data.erro) throw new Error('CEP não encontrado');
          endereco.value = data.logradouro || '';
          bairro.value = data.bairro || '';
          cidade.value = data.localidade || '';
          estado.value = data.uf || '';
          if (statusEl) {
            statusEl.textContent = '✅ Endereço encontrado';
            statusEl.className = 'cepStatus text-xs text-green-600 mt-1';
          }
        } catch (e) {
          if (statusEl) {
            statusEl.textContent = '❌ CEP não encontrado';
            statusEl.className = 'cepStatus text-xs text-red-600 mt-1';
          }
        }
      });
    });

    /* ===== Máscaras ===== */
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
  });
</script>