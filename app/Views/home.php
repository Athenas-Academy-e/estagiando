<!-- Carrossel Publicidades -->
<?php if (!empty($publicidades)): ?>
  <div class="relative w-full max-w-[1220px] mx-auto mt-2 rounded-xl overflow-hidden shadow-lg">
    <div id="carouselPublicidade"
      class="flex transition-transform duration-700 ease-in-out"
      style="width: calc(1220px * <?= count($publicidades) ?>);">

      <?php foreach ($publicidades as $p): ?>
        <a href="/redirect/<?= htmlspecialchars($p['site']) ?>" target="_blank" class="shrink-0">
          <img src="<?= htmlspecialchars($p['path']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>"
            class="w-[1220px] max-w-full h-auto object-contain">
        </a>
      <?php endforeach; ?>

    </div>

    <!-- Indicadores -->
    <div class="absolute flex gap-2 bottom-3 left-1/2 transform -translate-x-1/2">
      <?php foreach ($publicidades as $i => $p): ?>
        <button class="w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100"
          onclick="goToSlide(<?= $i ?>)"></button>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<!-- Seção principal -->
<section class="bg-gray-50 py-16">
  <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center md:items-start justify-between gap-10">
    <!-- Texto e busca -->
    <div class="md:w-1/2 text-center md:text-left">
      <h1 class="text-4xl md:text-5xl font-extrabold text-[#0a1837] mb-4 leading-tight">Encontre a vaga<br />certa para você!</h1>
      <p class="text-gray-600 text-lg mb-8">
        Hoje temos <span id="vagaCount" class="font-semibold text-blue-600 text-2xl"><?= $totalVagas ?></span> abertas.
      </p>

      <form action="/vagas" method="get" class="flex items-center gap-2 max-w-md mx-auto md:mx-0">
        <div class="flex-grow relative">
          <input type="text" name="q" placeholder="Pesquisar vaga"
            class="w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#0a1837]">
          <input type="hidden" name="sort" value="newest">
        </div>
        <button type="submit"
          class="bg-[#97dd3a] hover:bg-[#a0f72f] text-white font-medium px-5 py-3 rounded-md shadow transition hover:text-[#0a1837]">
          Encontrar vagas
        </button>
      </form>
    </div>

    <!-- Carrossel das Áreas (fixo e retangular, sem indicadores) -->
    <!-- Carrossel de 3 cards -->
    <div class="md:w-full relative overflow-hidden" style="max-width: 720px; height: 300px;">
      <div id="carouselAreas"
        class="flex transition-transform duration-700 ease-in-out gap-2"
        style="width: calc(240px * <?= count($areas) ?>);">

        <?php foreach ($areas as $area): ?>
          <a href="/vagas?categoria=<?= $area['id'] ?>"
            class="relative w-[240px] h-[300px] flex-shrink-0 overflow-hidden group rotate-[1deg] shadow-lg">

            <img src="<?= strtolower($area['imagempath']) ?>"
              alt="<?= htmlspecialchars($area['nome']) ?>"
              class="w-full h-full object-cover transition-all duration-500 grayscale group-hover:grayscale-0">

            <div class="absolute bottom-4 left-4 bg-[#ffffcc] px-4 py-2 shadow-md transform -rotate-2 group-hover:rotate-0 transition duration-500 rounded-md grayscale group-hover:grayscale-0">
              <span class="block text-sm font-bold text-[#ff6600] capitalize leading-none ">Vaga aberta</span>
              <span class="font-bold text-sm text-[#191ba5] capitalize"><?= htmlspecialchars($area['nome']) ?></span>
            </div>

          </a>
        <?php endforeach; ?>

      </div>
    </div>




  </div>
</section>

<!-- Cards inferiores -->
<section class="bg-gray-50 py-16">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-6">

    <div class="bg-[#004a99] text-white rounded-2xl p-8 text-center shadow-lg hover:scale-[1.02] transition-transform">
      <h2 class="text-2xl font-bold mb-2 text-white">Profissional</h2>
      <p class="text-sm mb-6">Cadastre seu currículo e concorra a mais de <span class="text-[#aef85f] font-bold"><?= $totalVagas ?></span> vagas</p>
      <a href="/cadastro" class="inline-block border text-white border-white rounded-full px-6 py-2 hover:bg-white hover:text-[#004a99]">Cadastrar Currículo</a>
    </div>

    <div class="bg-[#1d73d3] text-white rounded-2xl p-8 text-center shadow-lg hover:scale-[1.02] transition-transform">
      <h2 class="text-2xl font-bold mb-2 text-white">Empresa</h2>
      <p class="text-sm mb-6">Anuncie suas vagas e tenha acesso a <span class="text-[#aef85f] font-bold"><?= $totalEmpresas ?></span> currículos</p>
      <a href="/empresas/publicar" class="inline-block border text-white border-white rounded-full px-6 py-2 hover:bg-white hover:text-[#1d73d3]">Anunciar Vaga</a>
    </div>

  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {

    /* === Animações de entrada === */
    const animatedElements = document.querySelectorAll("[data-animate]");
    animatedElements.forEach((el, index) => {
      el.classList.add("opacity-0", "translate-y-4");
      setTimeout(() => {
        el.classList.remove("opacity-0", "translate-y-4");
        el.classList.add("opacity-100", "translate-y-0");
      }, 200 * index);
    });

    /* === Contadores animados === */
    const animateCount = (id, total, duration = 1500) => {
      const el = document.getElementById(id);
      if (!el) return;
      let current = 0;
      const step = total / (duration / 10);
      const timer = setInterval(() => {
        current += step;
        if (current >= total) {
          current = total;
          clearInterval(timer);
        }
        el.textContent = Math.floor(current).toLocaleString("pt-BR");
      }, 10);
    };

    animateCount("vagaCount", <?= $totalVagas ?>);
    animateCount("vagasCount", <?= $totalVagas ?>);
    animateCount("empresasCount", <?= $totalEmpresas ?>);

    /* === Carrossel das Áreas === */
    const carAreas = document.getElementById("carouselAreas");
    if (carAreas) {
      let index = 0;
      const total = carAreas.children.length;
      const visibleCards = 3;
      const cardWidth = 220; // 220px + 1 gap

      const nextSlide3 = () => {
        index = (index + visibleCards) % total;
        carAreas.style.transform = `translateX(-${index * cardWidth}px)`;
      };

      setInterval(nextSlide3, 5000);
    }

    /* === Carrossel Publicidades === */
    const carPublicidade = document.getElementById("carouselPublicidade");
    if (carPublicidade) {
      let slideIndex = 0;
      const totalSlides = carPublicidade.children.length;

      const nextSlide = () => {
        slideIndex = (slideIndex + 1) % totalSlides;
        const w = carPublicidade.children[0].clientWidth;
        carPublicidade.style.transform = `translateX(-${slideIndex * w}px)`;
      };

      setInterval(nextSlide, 5000);
    }

  });
</script>