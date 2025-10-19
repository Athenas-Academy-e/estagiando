<?php
require_once './inc/functions.php';

// Conta total de vagas abertas e total de empresas
$totalVagas = count(getJobs('', '', '', 'newest'));
$totalEmpresas = count(getEmpresas(''));
?>

<section class="bg-gray-50 py-16">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-6">

    <!-- Card Profissional -->
    <div class="bg-[#004a99] text-white rounded-2xl p-8 text-center shadow-lg hover:scale-[1.02] transition-transform duration-300" data-animate>
      <h2 class="text-2xl font-bold mb-2">Profissional</h2>
      <p class="text-sm mb-6">
        Cadastre o seu currículo e concorra a<br>
        <span class="font-semibold text-white/90">
          mais de <span id="vagasCount" class="text-[#aef85f] font-bold">0</span> vagas abertas
        </span>
      </p>
      <a href="cadastro.php"
         class="inline-block border border-white rounded-full px-6 py-2 text-white hover:bg-white hover:text-[#004a99] transition-colors duration-300 font-medium">
        Cadastrar Currículo
      </a>
    </div>

    <!-- Card Empresa -->
    <div class="bg-[#1d73d3] text-white rounded-2xl p-8 text-center shadow-lg hover:scale-[1.02] transition-transform duration-300" data-animate>
      <h2 class="text-2xl font-bold mb-2">Empresa</h2>
      <p class="text-sm mb-6">
        Anuncie suas vagas e conte com<br>
        <span class="font-semibold text-white/90">
          mais de <span id="empresasCount" class="text-[#aef85f] font-bold">0</span> currículos cadastrados
        </span>
      </p>
      <a href="/public/add.php"
         class="inline-block border border-white rounded-full px-6 py-2 text-white hover:bg-white hover:text-[#1d73d3] transition-colors duration-300 font-medium">
        Anunciar Vaga
      </a>
    </div>

  </div>
</section>

<script>
// --- Contador animado ---
document.addEventListener("DOMContentLoaded", () => {
  const animateCount = (id, total, duration = 1000) => {
    const el = document.getElementById(id);
    let current = 0;
    const stepTime = 10;
    const increment = total / (duration / stepTime);
    const interval = setInterval(() => {
      current += increment;
      if (current >= total) {
        current = total;
        clearInterval(interval);
      }
      el.textContent = Math.floor(current).toLocaleString('pt-BR');
    }, stepTime);
  };

  // Recebe os valores do PHP
  const totalVagas = <?= $totalVagas ?>;
  const totalEmpresas = <?= $totalEmpresas ?>;

  // Inicia contagem progressiva
  setTimeout(() => {
    animateCount('vagasCount', totalVagas);
    animateCount('empresasCount', totalEmpresas);
  }, 400);
});
</script>
