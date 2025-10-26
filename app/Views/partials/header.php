<?php include_once(__DIR__ . '/head.php'); ?>

<header class="bg-[#0a1837] w-full py-5 shadow-lg relative z-50">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
    <!-- Logo -->
    <a href="/">
      <img src="/assets/Logo.png" alt="Estagiando" class="w-56 md:w-56">
    </a>

    <!-- Botão hamburguer (visível apenas no mobile) -->
    <button id="menu-btn" class="md:hidden text-white focus:outline-none">
      <!-- Ícone hambúrguer -->
      <svg id="menu-icon" class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M4 6h16M4 12h16M4 18h16" />
      </svg>

      <!-- Ícone X (fechar) -->
      <svg id="close-icon" class="w-8 h-8 hidden" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Menu desktop -->
    <nav id="desktop-menu" class="hidden md:flex space-x-6 items-center">
      <a href="/vagas" class="font-bold text-lg text-white hover:text-[#97dd3a]">Vagas</a>
      <a href="/empresas" class="font-bold text-lg text-white hover:text-[#97dd3a]">Empresas</a>

      <a href="/login" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Entrar</a>
      <a href="/cadastro" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Cadastre-se</a>
      <a href="/empresas/publicar" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Publicar vaga</a>
    </nav>
  </div>

  <!-- Menu mobile -->
  <nav id="mobile-menu"
    class="absolute left-0 top-full w-full bg-[#0a1837] border-t border-[#1b2754] hidden opacity-0 transform scale-y-0 origin-top transition-all duration-300 ease-out">
    <div class="flex flex-col items-center space-y-4 py-5">
      <a href="/vagas" class="font-semibold text-white hover:text-[#97dd3a] menu-link">Vagas</a>
      <a href="/empresas" class="font-semibold text-white hover:text-[#97dd3a] menu-link">Empresas</a>
      <a href="/login" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all menu-link">Entrar</a>
      <a href="/cadastro" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all menu-link">Cadastre-se</a>
      <a href="/empresas/publicar" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all menu-link">Publicar vaga</a>
    </div>
  </nav>
</header>
