<?php
require_once './inc/functions.php';

// Conta total de vagas abertas
$totalVagas = count(getJobs('', '', '', 'newest'));
?>

<section class="bg-gray-50 py-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-10">

    <!-- Texto e busca -->
    <div class="md:w-1/2 text-center md:text-left">
      <h1 class="text-4xl md:text-5xl font-extrabold text-[#0a1837] mb-4 leading-tight opacity-0 translate-y-4 transition-all duration-700" data-animate>
        Encontre a vaga <br class="hidden md:block"> certa para você!
      </h1>

      <p class="text-gray-600 text-lg mb-8 opacity-0 translate-y-4 transition-all duration-700 delay-200" data-animate>
        Hoje temos <span id="vagaCount" class="font-semibold text-blue-600 text-2xl">0</span> abertas.
      </p>

      <!-- Formulário de pesquisa -->
      <form action="public/" method="get"
        class="flex items-center gap-2 max-w-md mx-auto md:mx-0 opacity-0 translate-y-4 transition-all duration-700 delay-400"
        data-animate>
        <div class="flex-grow relative">
          <input
            type="text"
            name="q"
            placeholder="Pesquisar vaga"
            class="w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#0a1837]">
          <input type="hidden" name="sort" value="newest">
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.6 3.6a7.5 7.5 0 0013.05 13.05z" />
          </svg>
        </div>
        <button
          type="submit"
          class="bg-[#97dd3a] hover:bg-[#a0f72f] text-white font-medium px-5 py-3 rounded-md shadow transition">
          Encontrar vagas
        </button>
      </form>
    </div>

    <!-- Colagem de imagens -->
    <div class="md:w-1/2 grid grid-cols-4 gap-2">
      <?php
      $imgs = [
        'trabalhador1.jpg',
        'trabalhador2.jpg',
        'trabalhador3.jpg',
        'trabalhador4.jpg',
        'trabalhador5.jpg',
        'trabalhador6.jpg',
        'trabalhador7.jpg',
        'trabalhador8.jpg',
        'trabalhador9.jpg',
        'trabalhador10.jpg',
        'trabalhador11.jpg',
        'trabalhador12.jpg',
        'trabalhador13.jpg',
        'trabalhador14.jpg',
        'trabalhador15.jpg',
        'trabalhador16.jpg'
      ];
      foreach ($imgs as $img): ?>
        <div class="overflow-hidden rounded-lg transform scale-95 hover:scale-100 transition-all duration-300">
          <img
            src="assets/img/home/<?= $img ?>"
            alt="Trabalhador"
            class="w-full h-24 object-cover grayscale hover:grayscale-0 transition-all duration-700 ease-in-out" />
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- Script: contagem e animação -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // --- Contagem progressiva ---
    const total = <?= $totalVagas ?>;
    const countEl = document.getElementById('vagaCount');
    let current = 0;
    const duration = 1000;
    const stepTime = 10;
    const increment = total / (duration / stepTime);

    const interval = setInterval(() => {
      current += increment;
      if (current >= total) {
        current = total;
        clearInterval(interval);
      }
      countEl.textContent = Math.floor(current).toLocaleString('pt-BR');
    }, stepTime);

    // --- Animação de entrada suave ---
    const elements = document.querySelectorAll('[data-animate]');
    elements.forEach((el, i) => {
      setTimeout(() => {
        el.classList.remove('opacity-0', 'translate-y-4');
        el.classList.add('opacity-100', 'translate-y-0');
      }, i * 200);
    });

    // --- Efeito aleatório de grayscale nas imagens ---
    const imgs = document.querySelectorAll('.grid img');

    imgs.forEach(img => {
      // Mantém o efeito hover padrão
      img.addEventListener('mouseenter', () => {
        img.classList.remove('grayscale');
      });
      img.addEventListener('mouseleave', () => {
        img.classList.add('grayscale');
      });
    });

    // Função que ativa/desativa o grayscale aleatoriamente
    function randomGrayEffect() {
      imgs.forEach(img => {
        const shouldColor = Math.random() < 0.1; // 30% de chance de ficar colorida
        img.classList.toggle('grayscale', !shouldColor);
      });
    }
    // Executa o efeito aleatório a cada 2 segundos
    setInterval(randomGrayEffect, 2000);
  });
</script>