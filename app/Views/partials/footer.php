 <footer class="bg-[#0a1837] text-white py-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
   <div class="max-w-7xl mx-auto px-6 text-center">
     <p class="text-sm opacity-80">
       © <?= date('Y') ?> Estagiando — Todos os direitos reservados.
     </p>
   </div>
 </footer>
 <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

 <script>
   AOS.init({
     duration: 800,
     once: true,
     offset: 80
   });

   window.addEventListener("load", () => {
     AOS.refresh();
   });
 </script>
 </body>

 </html>