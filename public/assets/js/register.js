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
    IMask(document.getElementById('cpf'), { mask: '000.000.000-00' });
    IMask(document.getElementById('cnpj'), { mask: '00.000.000/0000-00' });
    IMask(document.getElementById('telefoneProf'), { mask: '(00) 00000-0000' });
    IMask(document.getElementById('telefoneEmp'), { mask: '(00) 00000-0000' });
    IMask(document.getElementById('cepProf'), { mask: '00000-000' });
    IMask(document.getElementById('cepEmp'), { mask: '00000-000' });
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
    let t = cnpj.length - 2, n = cnpj.substring(0, t), d = cnpj.substring(t), s = 0, p = t - 7;
    for (let i = t; i >= 1; i--) { s += n.charAt(t - i) * p--; if (p < 2) p = 9; }
    let r = s % 11 < 2 ? 0 : 11 - (s % 11); if (r != d.charAt(0)) return false;
    t++; n = cnpj.substring(0, t); s = 0; p = t - 7;
    for (let i = t; i >= 1; i--) { s += n.charAt(t - i) * p--; if (p < 2) p = 9; }
    r = s % 11 < 2 ? 0 : 11 - (s % 11);
    return r == d.charAt(1);
  }

  // 🔍 Autocomplete de CEP (ViaCEP)
  const camposCep = [
    { cep: 'cepProf', prefix: 'Prof' },
    { cep: 'cepEmp', prefix: 'Emp' }
  ];

  camposCep.forEach(({ cep, prefix }) => {
    const cepInput = document.getElementById(cep);
    if (!cepInput) return;

    const endereco = document.querySelector(`[name="endereco"]`);
    const bairro = document.querySelector(`[name="bairro"]`);
    const cidade = document.querySelector(`[name="cidade"]`);
    const estado = document.querySelector(`[name="estado"]`);

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
