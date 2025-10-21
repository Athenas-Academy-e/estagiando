<?php
session_start();
require_once __DIR__ . '/../inc/functions.php';

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = loginEmpresa($_POST['email'] ?? '', $_POST['senha'] ?? '');
    if ($result['success']) {
        header('Location: public/');
        exit;
    }
}
?>
<?php require(__DIR__.'/head.php')?>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Login da Empresa</h2>

    <?php if ($result && !$result['success']): ?>
      <div class="bg-red-100 text-red-700 border px-4 py-3 rounded mb-4 text-center">
        <?= htmlspecialchars($result['message']) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <label class="block mb-2 font-medium">E-mail</label>
      <input type="email" name="email" required class="w-full p-2 border rounded mb-4" placeholder="exemplo@email.com">

      <label class="block mb-2 font-medium">Senha</label>
      <input type="password" name="senha" required class="w-full p-2 border rounded mb-6" placeholder="********">

      <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded hover:bg-blue-800 transition">
        Entrar
      </button>

      <p class="text-center text-sm mt-4">
        Ainda não tem conta? <a href="/public/empresa/register/" class="text-blue-600 hover:underline">Cadastre-se</a>
      </p>
    </form>
  </div>
</body>
</html>
