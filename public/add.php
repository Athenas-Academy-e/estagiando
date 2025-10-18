<?php
require_once __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../templates/header.php';

// Carrega municípios para o select
$municipios = getMunicipios();
$categorias = getCategorias();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // recebemos o ID do município
    $municipio_id = $_POST['municipio_id'] ?? '';

    // monta o campo 'location' no formato "Nome, UF"
    $locationText = '';
    if ($municipio_id) {
        $m = getMunicipioById($municipio_id);
        if ($m) {
            $locationText = $m['nome'] . ', ' . $m['estado'];
        }
    }

    $data = [
        'title'       => $_POST['title'] ?? '',
        'company'     => $_POST['company'] ?? '',
        'location'    => $locationText, // ← usamos o texto gerado a partir do município
        'type'        => $_POST['type'] ?? '',
        'salary'      => $_POST['salary'] ?? '',
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

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <input type="text" name="title" placeholder="Título" class="p-2 border rounded" required>
        <input type="text" name="company" placeholder="Empresa" class="p-2 border rounded" required>

        <!-- Localidade via municípios -->
        <select name="municipio_id" class="p-2 border rounded" required>
            <option value="" disabled selected>Selecione uma localidade</option>
            <?php foreach ($municipios as $mun): ?>
                <option value="<?= (int)$mun['id'] ?>">
                    <?= htmlspecialchars($mun['nome']) . ' - ' . htmlspecialchars($mun['estado']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="type" class="p-2 border rounded">
            <option>Full-time</option>
            <option>Part-time</option>
            <option>Contract</option>
            <option>Internship</option>
            <option>Remote</option>
        </select>

        <select name="categoria_id" class="p-2 border rounded" required>
            <option value="" disabled selected>Selecione uma categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= (int)$categoria['id'] ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input type="text" name="salary" placeholder="Salário" class="p-2 border rounded md:col-span-2">
        <textarea name="description" placeholder="Descrição" class="p-2 border rounded md:col-span-2" rows="4"></textarea>

        <div class="md:col-span-2 flex justify-end gap-2">
            <a href="index.php" class="px-3 py-2 border rounded">Cancelar</a>
            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Salvar vaga</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>