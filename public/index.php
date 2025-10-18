<?php
require_once __DIR__.'/../inc/functions.php';
include __DIR__.'/../templates/header.php';

// Carrega municípios para o select
$municipios = getMunicipios();

// Captura filtros
$query        = $_GET['q']   ?? '';
$municipio_id = $_GET['mun'] ?? '';  // <-- agora usamos 'mun' (id do município)
$type         = $_GET['type'] ?? '';
$sort         = $_GET['sort'] ?? 'newest';

// Converte municipio_id → "Nome, UF" para o filtro de jobs
$location = '';
if ($municipio_id) {
    $m = getMunicipioById($municipio_id);
    if ($m) {
        $location = $m['nome'] . ', ' . $m['estado'];
    }
}

// Busca vagas aplicando os filtros (note que passamos $location)
$jobs = getJobs($query, $location, $type, $sort);
?>
<main class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
<aside class="bg-white p-4 rounded-lg shadow-sm">
  <form method="get" class="space-y-3">
    <div>
      <label class="block text-sm font-medium text-gray-700">Buscar</label>
      <input
        type="text"
        name="q"
        value="<?= htmlspecialchars($query) ?>"
        class="mt-1 w-full border-gray-200 rounded-md shadow-sm p-2"
        placeholder="título, empresa, habilidade"
      >
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Localidade</label>
      <select name="mun" class="mt-1 w-full border-gray-200 rounded-md shadow-sm p-2">
        <option value="" disabled <?= $municipio_id ? '' : 'selected' ?>>Selecione uma localidade</option>
        <?php foreach ($municipios as $mun): ?>
          <option
            value="<?= (int)$mun['id'] ?>"
            <?= ($municipio_id == $mun['id']) ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($mun['nome']) . ' - ' . htmlspecialchars($mun['estado']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Tipo</label>
      <select name="type" class="mt-1 w-full border-gray-200 rounded-md shadow-sm p-2">
        <option value="" disabled <?= $type ? '' : 'selected' ?>>Selecione o tipo</option>
        <?php foreach(['Full-time','Part-time','Contract','Internship','Remote'] as $t): ?>
          <option value="<?= $t ?>" <?= $type === $t ? 'selected' : '' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Ordenar</label>
      <select name="sort" class="mt-1 w-full border-gray-200 rounded-md shadow-sm p-2">
        <option value="" disabled <?= $sort ? '' : 'selected' ?>>Escolha a ordenação</option>
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Mais recentes</option>
        <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Mais antigas</option>
      </select>
    </div>

    <button class="bg-gray-800 text-white w-full py-2 rounded-md hover:bg-gray-900 mt-2">Filtrar</button>
  </form>
</aside>


<section class="md:col-span-3 bg-white p-4 rounded-lg shadow-sm">
<h2 class="text-lg font-semibold mb-4">Resultados (<?=count($jobs)?>)</h2>

<?php if(empty($jobs)): ?>
<p class="text-center text-gray-500 py-6">Nenhuma vaga encontrada.</p>
<?php else: ?>
<div class="grid gap-4">
<?php foreach($jobs as $job): ?>
<article class="p-4 border rounded-md flex flex-col md:flex-row md:justify-between">
  <div>
    <h3 class="text-lg font-semibold"><?=htmlspecialchars($job['title'])?></h3>
    <p class="text-sm text-gray-600"><?=htmlspecialchars($job['company'])?> • <?=htmlspecialchars($job['location'])?> • <span class="font-medium"><?=htmlspecialchars($job['type'])?></span></p>
    <p class="mt-2 text-gray-700 text-sm"><?=htmlspecialchars($job['description'])?></p>
  </div>
  <div class="mt-4 md:mt-0 md:text-right flex flex-col gap-2">
    <div class="text-sm text-gray-500"><?=htmlspecialchars($job['postedAt'])?></div>
    <div class="font-semibold"><?=htmlspecialchars($job['salary'])?></div>
    <div class="flex gap-2 justify-end">
      <button onclick="openModal(<?=$job['id']?>)" class="bg-green-600 text-white px-3 py-1 rounded-md text-sm">Candidatar</button>
      <a href="edit.php?id=<?=$job['id']?>" class="border px-3 py-1 rounded-md text-sm">Editar</a>
      <a href="delete.php?id=<?=$job['id']?>" class="border px-3 py-1 rounded-md text-sm text-red-600" onclick="return confirm('Remover vaga?')">Remover</a>
    </div>
  </div>
</article>
<?php endforeach; ?>
</div>
<?php endif; ?>

</section>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-4 relative">
    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500">✕</button>
    <form id="applyForm" action="apply.php" method="post" enctype="multipart/form-data">
      <h3 class="text-xl font-semibold mb-2" id="jobTitle"></h3>
      <input type="hidden" name="job_id" id="jobId">
      <input type="text" name="name" placeholder="Seu nome" required class="p-2 border rounded w-full mb-2">
      <input type="email" name="email" placeholder="E-mail" required class="p-2 border rounded w-full mb-2">
      <input type="file" name="cv" class="p-2 w-full mb-2">
      <div class="flex gap-2 justify-end">
        <button type="button" onclick="closeModal()" class="px-3 py-2 border rounded">Fechar</button>
        <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded">Enviar candidatura</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id) {
  const jobs = <?=json_encode($jobs)?>;
  const job = jobs.find(j => j.id==id);
  document.getElementById('jobTitle').innerText = 'Candidatar: ' + job.title;
  document.getElementById('jobId').value = job.id;
  document.getElementById('modal').classList.remove('hidden');
}
function closeModal() {
  document.getElementById('modal').classList.add('hidden');
}
</script>

<?php include __DIR__.'/../templates/footer.php'; ?>
