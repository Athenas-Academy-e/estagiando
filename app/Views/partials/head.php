<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Estagiando</title>

  <!-- Caminho correto para o CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/output.css">

  <!-- Favicons -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>assets/favicon/favicon.svg">
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/favicon/favicon-96x96.png">
  <link rel="shortcut icon" href="<?= BASE_URL ?>assets/favicon/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/favicon/apple-touch-icon.png">
  <link rel="manifest" href="<?= BASE_URL ?>assets/favicon/site.webmanifest">


  <!-- Caso queira ativar Tailwind por CDN -->
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
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
</head>