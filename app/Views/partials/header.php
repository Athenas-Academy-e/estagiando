<?php include_once(__DIR__ . '/head.php'); ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<body class="bg-gray-50 font-sans min-h-screen">
  <header class="bg-[#0a1837] w-full py-5 shadow-lg relative z-50">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">

      <!-- Logo -->
      <a href="/">
        <img src="/assets/Logo.png" alt="Estagiando" class="w-56 md:w-56">
      </a>

      <!-- Botão hamburguer (visível apenas no mobile) -->
      <button id="menu-btn" class="md:hidden text-white focus:outline-none">
        <svg id="menu-icon" class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>

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

        <?php if (!isset($_SESSION['empresa_id']) && !isset($_SESSION['profissional_id'])): ?>
          <a href="/login" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Entrar</a>
          <a href="/cadastro" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Cadastre-se</a>
        <?php else: ?>
          <a href="<?php echo isset($_SESSION['empresa_id']) ? '/empresas/dashboard' : '/profissional/dashboard' ?>" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Meu Perfil</a>

          <?php if (isset($_SESSION['empresa_id'])): ?>
            <a href="/empresas/publicar" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Publicar Vaga</a>
          <?php endif; ?>

          <a href="/logout" class="bg-[#97dd3a] text-white px-4 py-2 rounded-md shadow hover:bg-[#9fec3b] transition-all">Sair</a>
        <?php endif; ?>
      </nav>
    </div>

    <!-- Menu mobile -->
    <nav id="mobile-menu"
      class="absolute left-0 top-full w-full bg-[#0a1837] border-t border-[#1b2754] hidden opacity-0 transform scale-y-0 origin-top transition-all duration-300 ease-out">
      <div class="flex flex-col items-center space-y-4 py-5">
        <a href="/vagas" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Vagas</a>
        <a href="/empresas" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Empresas</a>

        <?php if (!isset($_SESSION['empresa_id']) && !isset($_SESSION['profissional_id'])): ?>
          <a href="/login" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Entrar</a>
          <a href="/cadastro" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Cadastre-se</a>
        <?php else: ?>
          <a href="<?php echo isset($_SESSION['empresa_id']) ? '/empresas/dashboard' : '/profissional/dashboard' ?>" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Meu Perfil</a>

          <?php if (isset($_SESSION['empresa_id'])): ?>
            <a href="/empresas/publicar" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Publicar Vaga</a>
          <?php endif; ?>

          <a href="/logout" class="font-semibold text-white hover:bg-[#97dd3a] hover:text-white menu-link w-full text-center p-2">Sair</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <script>
    (function() {
      const menuBtn = document.getElementById('menu-btn');
      const menuIcon = document.getElementById('menu-icon');
      const closeIcon = document.getElementById('close-icon');
      const mobileMenu = document.getElementById('mobile-menu');
      const menuLinks = document.querySelectorAll('.menu-link');
      let open = false;

      const toggleMenu = (forceClose = false) => {
        open = forceClose ? false : !open;
        if (open) {
          mobileMenu.classList.remove('hidden');
          setTimeout(() => {
            mobileMenu.classList.remove('opacity-0', 'scale-y-0');
            mobileMenu.classList.add('opacity-100', 'scale-y-100');
          }, 10);
          menuIcon.classList.add('hidden');
          closeIcon.classList.remove('hidden');
        } else {
          mobileMenu.classList.add('opacity-0', 'scale-y-0');
          mobileMenu.classList.remove('opacity-100', 'scale-y-100');
          setTimeout(() => mobileMenu.classList.add('hidden'), 300);
          menuIcon.classList.remove('hidden');
          closeIcon.classList.add('hidden');
        }
      };

      menuBtn?.addEventListener('click', () => toggleMenu());
      menuLinks.forEach(link => link.addEventListener('click', () => toggleMenu(true)));
    })();
  </script>