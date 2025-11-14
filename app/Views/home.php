<!-- Carrossel Publicidades -->
<?php if (!empty($publicidades)): ?>
  <div class="relative w-full max-w-7xl mx-auto mt-4 rounded-xl overflow-hidden shadow-lg">

    <!-- Contêiner das imagens -->
    <div id="carouselPublicidade"
      class="flex transition-transform duration-700 ease-in-out touch-pan-x sm:scale-75 md:scale-50 lg:scale-100"
      style="width: <?= count($publicidades) * 100 ?>%;">

      <?php foreach ($publicidades as $p): ?>
        <a href="/redirect/<?= htmlspecialchars($p['site']) ?>"
          target="_blank"
          class="w-full flex-shrink-0 block">
          <img src="<?= htmlspecialchars($p['path']) ?>"
            alt="<?= htmlspecialchars($p['nome']) ?>"
            class="w-full  object-contain sm:object-cover">
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Indicadores -->
    <div class="absolute gap-2 bottom-3 left-1/2 transform -translate-x-1/2 z-10 lg:flex hidden">
      <?php foreach ($publicidades as $i => $p): ?>
        <button
          class="indicator w-3 h-3 rounded-full bg-white opacity-40 hover:opacity-100 transition"
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
        Mais de <span id="vagaCount" class="font-semibold text-blue-600 text-2xl"><?= ($totalVagas > 30) ? number_format($totalVagas, 0, ',', '.') : '30' ?></span> oportunidades esperando por você!
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

    <!-- Carrossel das Áreas -->
    <div class="md:w-full relative overflow-hidden" style="max-width: 720px; height: 300px;">
      <div id="carouselAreas"
        class="flex transition-transform duration-700 ease-in-out gap-2"
        style="width: calc(240px * <?= !empty($areas) ? count($areas) : 1 ?>);">

        <?php if (!empty($areas)): ?>
          <?php foreach ($areas as $area): ?>
            <a href="/vagas?categoria=<?= $area['id'] ?>"
              class="relative w-[240px] h-[300px] flex-shrink-0 overflow-hidden group rotate-[1deg] shadow-lg">

              <img src="<?= strtolower($area['imagempath']) ?>"
                alt="<?= htmlspecialchars($area['nome']) ?>"
                class="w-full h-full object-cover transition-all duration-500 grayscale group-hover:grayscale-0">

              <div
                class="absolute bottom-4 left-4 bg-[#ffffcc] px-4 py-2 shadow-md transform -rotate-2 group-hover:rotate-0 transition duration-500 rounded-md grayscale group-hover:grayscale-0">
                <span class="block text-sm font-bold text-[#ff6600] capitalize leading-none ">Vagas abertas</span>
                <span class="font-bold text-sm text-[#191ba5] capitalize"><?= htmlspecialchars($area['nome']) ?></span>
              </div>

            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- 🔹 Imagem padrão quando não há áreas -->
          <div class="relative w-[240px] h-[300px] flex-shrink-0 overflow-hidden rotate-[1deg] shadow-lg">
            <img src="/assets/default_areas.jpg" alt="Área padrão"
              class="w-full h-full object-cover grayscale">
            <!-- <div
              class="absolute bottom-4 left-4 bg-[#ffffcc] px-4 py-2 shadow-md transform -rotate-2 rounded-md">
              <span class="block text-sm font-bold text-[#ff6600] leading-none">Sem vagas disponíveis</span>
              <span class="font-bold text-sm text-[#191ba5]">Em breve novas oportunidades</span>
            </div> -->
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<!-- Cards inferiores -->
<section class="bg-gray-50 py-16">
  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-6">

    <div class="bg-[#004a99] text-white rounded-2xl p-8 text-center shadow-lg hover:scale-[1.02] transition-transform">
      <h2 class="text-2xl font-bold mb-2 text-white">Profissional</h2>
      <p class="text-sm mb-6">Cadastre seu currículo e concorra a mais de <span id="vagasCount" class="text-[#aef85f] font-bold"><?= ($totalVagas > 30) ? number_format($totalVagas, 0, ',', '.') : '30' ?></span> vagas</p>
      <a href="/cadastro" class="inline-block border text-white border-white rounded-full px-6 py-2 hover:bg-white hover:text-[#004a99]">Cadastrar Currículo</a>
    </div>

    <div class="bg-[#1d73d3] text-white rounded-2xl p-8 text-center shadow-lg hover:scale-[1.02] transition-transform">
      <h2 class="text-2xl font-bold mb-2 text-white">Empresa</h2>
      <p class="text-sm mb-6">Anuncie suas vagas e tenha acesso a <span id="empresasCount" class="text-[#aef85f] font-bold"><?= ($totalProfissionais > 200) ? number_format($totalProfissionais, 0, ',', '.') : '200' ?></span> currículos</p>
      <a href="/empresas/publicar" class="inline-block border text-white border-white rounded-full px-6 py-2 hover:bg-white hover:text-[#1d73d3]">Anunciar Vaga</a>
    </div>

  </div>
</section>

<!-- Seção Empresas Parceiras -->
<section class="bg-[#0a1837] text-white py-16 mt-10 items-center flex">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-10 text-white">Empresas Parceiras</h2>

    <?php if (!empty($empresasParceiras)): ?>
      <?php $temMuitas = count($empresasParceiras) > 6; ?>

      <div class="relative overflow-hidden">
        <!-- Trilho/carrossel quando há muitas -->
        <div
          id="empresaTrack"
          class="<?= $temMuitas ? 'flex flex-nowrap gap-8 will-change-transform' : 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8' ?>"
          data-carousel="<?= $temMuitas ? 'true' : 'false' ?>"
          style="<?= $temMuitas ? 'width:max-content; transform: translateX(0);' : '' ?>">
          <?php
          // Função render card para evitar repetição
          $renderCard = function ($e) {
            $temSite = !empty($e['site']);
            $siteUrl = $temSite ? (preg_match('#^https?://#i', $e['site']) ? $e['site'] : 'https://' . $e['site']) : null;

            ob_start(); ?>
            <?php if ($temSite): ?>
              <a href="<?= htmlspecialchars($siteUrl) ?>" target="_blank" class="block mt-4 text-right text-[#9fec3b] hover:text-[#97dd3a] text-sm transition">
              <?php else: ?>
                <div class="block mt-4 text-right text-[#9fec3b] text-sm cursor-default">
                <?php endif; ?>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition duration-300 w-[320px] flex-shrink-0 hover:translate-x-1">
                  <div class="flex items-center gap-4">
                    <img
                      src="<?= htmlspecialchars($e['logo'] ?? '/assets/default-company.png') ?>"
                      alt="<?= htmlspecialchars($e['nome_fantasia'] ?? 'Empresa') ?>"
                      class="w-16 h-16 object-cover rounded-full border border-gray-200">
                    <div class="text-left">
                      <h3 class="font-bold text-black text-lg">
                        <?= htmlspecialchars($e['nome_fantasia'] ?? 'Empresa') ?>
                      </h3>
                      <p class="text-black/80 font-semibold text-sm">
                        <?= htmlspecialchars($e['categoria_nome'] ?? 'Sem categoria') ?>
                      </p>
                      <p class="text-black/70 font-medium text-xs">
                        <?= htmlspecialchars($e['cidade'] ?? '') ?>
                      </p>
                    </div>
                  </div>
                </div>

                <?php if ($temSite): ?>
              </a>
            <?php else: ?>
        </div>
      <?php endif; ?>
    <?php
            return ob_get_clean();
          };
    ?>

    <?php foreach ($empresasParceiras as $e): ?>
      <?= $renderCard($e) ?>
    <?php endforeach; ?>

    <?php if ($temMuitas): ?>
      <!-- Duplicação para loop contínuo -->
      <?php foreach ($empresasParceiras as $e): ?>
        <?= $renderCard($e) ?>
      <?php endforeach; ?>
    <?php endif; ?>
      </div>
  </div>
<?php else: ?>
  <p class="text-center text-gray-300">Nenhuma empresa parceira cadastrada no momento.</p>
<?php endif; ?>
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

    animateCount("vagaCount", <?= ($totalVagas > 30) ? $totalVagas : '30' ?>);
    animateCount("vagasCount", <?= ($totalVagas > 30) ? $totalVagas : '30' ?>);
    animateCount("empresasCount", <?= ($totalProfissionais > 200) ? $totalProfissionais : '200' ?>);

    /* === Carrossel das Áreas === */
    const carAreas = document.getElementById("carouselAreas");
    if (carAreas) {
      let index = 0;
      const total = carAreas.children.length;
      const visibleCards = 1;
      const cardWidth = 250; // 220px + 1 gap

      const nextSlide3 = () => {
        index = (index + visibleCards) % total;
        carAreas.style.transform = `translateX(-${index * cardWidth}px)`;
      };

      setInterval(nextSlide3, 5000);
    }

    /* === Carrossel Publicidades Responsivo + Touch === */
    const carPublicidade = document.getElementById("carouselPublicidade");

    if (carPublicidade) {
      let slideIndex = 0;
      const totalSlides = carPublicidade.children.length;
      let isDragging = false;
      let startX = 0;
      let scrollLeft = 0;

      const goToSlide = (index) => {
        slideIndex = index;
        const width = carPublicidade.clientWidth;
        carPublicidade.style.transform = `translateX(-${slideIndex * width}px)`;
        updateIndicators();
      };

      const nextSlide = () => {
        slideIndex = (slideIndex + 1) % totalSlides;
        goToSlide(slideIndex);
      };

      const updateIndicators = () => {
        const indicators = document.querySelectorAll(".indicator");
        indicators.forEach((dot, i) => {
          dot.style.opacity = i === slideIndex ? "1" : "0.4";
          dot.style.transform = i === slideIndex ? "scale(1.2)" : "scale(1)";
        });
      };

      // 🔹 Auto play
      let autoPlay = setInterval(nextSlide, 5000);

      // 🔹 Pausa ao passar o mouse
      carPublicidade.addEventListener("mouseenter", () => clearInterval(autoPlay));
      carPublicidade.addEventListener("mouseleave", () => (autoPlay = setInterval(nextSlide, 5000)));

      // 🔹 Responsivo no resize
      window.addEventListener("resize", () => goToSlide(slideIndex));

      // 🔹 Touch swipe (celular e tablet)
      carPublicidade.addEventListener("touchstart", (e) => {
        clearInterval(autoPlay);
        isDragging = true;
        startX = e.touches[0].pageX;
      });

      carPublicidade.addEventListener("touchmove", (e) => {
        if (!isDragging) return;
        const x = e.touches[0].pageX;
        const walk = x - startX;
        carPublicidade.style.transform = `translateX(${walk - slideIndex * carPublicidade.clientWidth}px)`;
      });

      carPublicidade.addEventListener("touchend", (e) => {
        isDragging = false;
        const x = e.changedTouches[0].pageX;
        const diff = startX - x;
        if (Math.abs(diff) > 50) {
          // muda de slide
          if (diff > 0) slideIndex = (slideIndex + 1) % totalSlides;
          else slideIndex = (slideIndex - 1 + totalSlides) % totalSlides;
        }
        goToSlide(slideIndex);
        autoPlay = setInterval(nextSlide, 5000);
      });

      // inicializa
      goToSlide(0);
    }
  });

  /* === Carrossel de Empresas Parceiras === */
  (function() {
    const track = document.getElementById('empresaTrack');
    if (!track) return;

    const isCarousel = track.getAttribute('data-carousel') === 'true';
    if (!isCarousel) return; // com ≤6, fica grid estático

    // Configurações
    let speed = 0.35; // px por frame (~60fps)
    let pos = 0;
    let running = true;
    let trackWidthHalf = 0;

    // Recalcula largura do primeiro bloco (metade do trilho, antes dos clones)
    const calcHalfWidth = () => {
      // Metade = soma dos primeiros N filhos (antes da duplicação),
      // aqui simplificamos: como duplicamos exatamente os mesmos itens,
      // metade = metade do scrollWidth
      trackWidthHalf = track.scrollWidth / 2;
    };

    const step = () => {
      if (!running) return requestAnimationFrame(step);

      pos -= speed;
      // Quando percorre metade do trilho, reseta para 0
      if (Math.abs(pos) >= trackWidthHalf) pos = 0;
      track.style.transform = `translateX(${pos}px)`;

      requestAnimationFrame(step);
    };

    // Pausa no hover
    track.addEventListener('mouseenter', () => {
      running = false;
    });
    track.addEventListener('mouseleave', () => {
      if (!running) {
        running = true;
        requestAnimationFrame(step);
      }
    });

    // Responsivo
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        // Reset e recalcula
        pos = 0;
        track.style.transform = 'translateX(0)';
        calcHalfWidth();
      }, 120);
    });

    // Suporte a toque (arrastar para acelerar/direcionar)
    let isTouching = false,
      startX = 0,
      lastX = 0;
    track.addEventListener('touchstart', (e) => {
      isTouching = true;
      running = false;
      startX = lastX = e.touches[0].clientX;
    }, {
      passive: true
    });

    track.addEventListener('touchmove', (e) => {
      if (!isTouching) return;
      const x = e.touches[0].clientX;
      const delta = x - lastX;
      lastX = x;
      pos += delta; // arrasta na direção do dedo
      track.style.transform = `translateX(${pos}px)`;
    }, {
      passive: true
    });

    track.addEventListener('touchend', () => {
      isTouching = false;
      // retoma animação
      running = true;
      requestAnimationFrame(step);
    });

    // Inicializa
    calcHalfWidth();
    requestAnimationFrame(step);
  })();
</script>