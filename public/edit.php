<?php
require_once __DIR__.'/../inc/functions.php';
include __DIR__.'/../templates/header.php';

$id = $_GET['id'] ?? null;
$job = getJobById($id);

if (!$job) {
    die("Vaga não encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job['title'] = $_POST['title'] ?? '';
    $job['company'] = $_POST['company'] ?? '';
    $job['location'] = $_POST['location'] ?? '';
    $job['type'] = $_POST['type'] ?? '';
    $job['salary'] = $_POST['salary'] ?? '';
    $job['description'] = $_POST['description'] ?? '';

    if (!$job['title'] || !$job['company']) {
        $error = "Informe título e empresa.";
    } else {
        saveJob($job);
        header('Location: index.php');
        exit;
    }
}
?>

<div class="md:col-span-3 bg-white p-6 rounded-lg shadow-sm">
<h2 class="text-xl font-semibold mb-4">Editar vaga</h2>

<?php if(!empty($error)): ?>
<div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?=$error?></div>
<?php endif; ?>

<form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <input type="text" name="title" value="<?=htmlspecialchars($job['title'])?>" placeholder="Título" class="p-2 border rounded">
    <input type="text" name="company" value="<?=htmlspecialchars($job['company'])?>" placeholder="Empresa" class="p-2 border rounded">
    <input type="text" name="location" value="<?=htmlspecialchars($job['location'])?>" placeholder="Localidade" class="p-2 border rounded">
    <select name="type" class="p-2 border rounded">
        <?php foreach(['Full-time','Part-time','Contract','Internship','Remote'] as $t): ?>
            <option value="<?=$t?>" <?=$job['type']==$t?'selected':''?>><?=$t?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="salary" value="<?=htmlspecialchars($job['salary'])?>" placeholder="Salário" class="p-2 border rounded md:col-span-2">
    <textarea name="description" placeholder="Descrição" class="p-2 border rounded md:col-span-2" rows="4"><?=htmlspecialchars($job['description'])?></textarea>
    <div class="md:col-span-2 flex justify-end gap-2">
        <a href="index.php" class="px-3 py-2 border rounded">Cancelar</a>
        <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Salvar alterações</button>
    </div>
</form>
</div>

<?php include __DIR__.'/../templates/footer.php'; ?>
