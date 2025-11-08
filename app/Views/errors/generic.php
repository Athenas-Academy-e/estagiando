<main class="relative min-h-screen flex flex-col items-center justify-center text-white text-center overflow-hidden bg-[#0a1837]">

  <!-- 🔹 Camada 1: Onda azul escura -->
  <div class="absolute inset-0 wave wave-1"></div>

  <!-- 🔹 Camada 2: Onda azul média -->
  <div class="absolute inset-0 wave wave-2"></div>

  <!-- 🔹 Camada 3: Onda azul-clara com brilho -->
  <div class="absolute inset-0 wave wave-3"></div>

  <!-- 🔹 Conteúdo -->
  <div class="relative z-10 max-w-xl px-6">
    <img src="/assets/Logo.png" alt="Estagiando" class="mx-auto mb-8 w-32 opacity-90">

    <p class="text-[#97dd3a] text-sm font-semibold uppercase"><?= htmlspecialchars($errorCode) ?></p>
    <h1 class="mt-3 text-5xl md:text-6xl font-extrabold tracking-tight"><?= htmlspecialchars($errorTitle) ?></h1>
    <p class="mt-4 text-lg text-gray-200"><?= htmlspecialchars($errorMessage) ?></p>

    <a href="/"
      class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-[#0a1837] bg-[#97dd3a] hover:bg-[#aafc4d] px-6 py-3 rounded-full transition duration-200">
      ← Voltar à página inicial
    </a>
  </div>

  <!-- 🔹 Estilo das ondas animadas -->
  <style>
    /* Fundo gradiente */
    main {
      background: radial-gradient(circle at center, #0a1837 50%, #07132b 100%);
    }

    /* Ondas baseadas em curvas SVG */
    .wave {
      background-repeat: no-repeat;
      background-size: cover;
      opacity: 0.5;
      animation: moveWave 30s linear infinite;
      filter: blur(40px);
    }

    /* Azul escuro */
    .wave-1 {
      background: radial-gradient(circle at 40% 60%, #0f2559 0%, transparent 70%);
      animation-duration: 60s;
    }

    /* Azul médio */
    .wave-2 {
      background: radial-gradient(circle at 60% 40%, #1e3a8a 0%, transparent 70%);
      animation-duration: 45s;
      animation-direction: reverse;
    }

    /* Azul claro esverdeado (brilho Estagiando) */
    .wave-3 {
      background: radial-gradient(circle at 50% 50%, #97dd3a 0%, transparent 60%);
      mix-blend-mode: screen;
      opacity: 0.6;
      animation-duration: 90s;
    }

    @keyframes moveWave {
      from { transform: rotate(0deg) scale(1.2); }
      to { transform: rotate(360deg) scale(1.2); }
    }
  </style>
</main>
