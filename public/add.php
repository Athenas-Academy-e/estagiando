<?php
require_once __DIR__.'/../inc/functions.php';
include __DIR__.'/../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'company' => $_POST['company'] ?? '',
        'location' => $_POST['location'] ?? '',
        'type' => $_POST['type'] ?? '',
        'salary' => $_POST['salary'] ?? '',
        'description' => $_POST['description'] ?? ''
    ];

    if (!$data['title'] || !$data['company']) {
        $error = "Informe título e empresa.";
    } else {
        saveJob($data);
        header('Location: index.php');
        exit;
    }
}
?>

<div class="md:col-span-3 bg-white p-6 rounded-lg shadow-sm">
<h2 class="text-xl font-semibold mb-4">Publicar nova vaga</h2>

<?php if(!empty($error)): ?>
<div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?=$error?></div>
<?php endif; ?>

<form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <input type="text" name="title" placeholder="Título" class="p-2 border rounded">
    <input type="text" name="company" placeholder="Empresa" class="p-2 border rounded">
    <input type="text" name="location" placeholder="Localidade" class="p-2 border rounded">
    <select name="type" class="p-2 border rounded">
        <option>Full-time</option>
        <option>Part-time</option>
        <option>Contract</option>
        <option>Internship</option>
        <option>Remote</option>
    </select>
    <input type="text" name="salary" placeholder="Salário" class="p-2 border rounded md:col-span-2">
    <textarea name="description" placeholder="Descrição" class="p-2 border rounded md:col-span-2" rows="4"></textarea>
    <div class="md:col-span-2 flex justify-end gap-2">
        <a href="index.php" class="px-3 py-2 border rounded">Cancelar</a>
        <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Salvar vaga</button>
    </div>
</form>
</div>

<?php include __DIR__.'/../templates/footer.php'; ?>
