<?php
require_once __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../templates/header.php';

// Carrega listas
$municipios  = getMunicipios();
$categorias  = getCategorias();
$jobmethods  = getJobmethod();
$empresas    = getEmpresas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $municipio_id = $_POST['municipio_id'] ?? '';
    $empresa_id   = $_POST['empresa_id'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? '';
    $title        = $_POST['title'] ?? '';
    $type         = $_POST['type'] ?? '';
    $salary       = $_POST['salary'] ?? '';
    $description  = $_POST['description'] ?? '';

    // Monta o campo location (Nome, UF)
    $locationText = '';
    if ($municipio_id) {
        $m = getMunicipioById($municipio_id);
        if ($m) {
            $locationText = $m['nome'] . ', ' . $m['estado'];
        }
    }

    // Limpa o salário (remove R$, pontos e vírgula)
    $salary = preg_replace('/[^\d,]/', '', $salary);
    $salary = str_replace(',', '.', $salary);

    if (!$title || !$empresa_id) {
        $error = "Informe título e selecione uma empresa.";
    } else {
        $data = [
            'title'        => $title,
            'company_id'   => (int)$empresa_id,
            'categoria_id' => $categoria_id ? (int)$categoria_id : null,
            'location'     => $locationText,
            'type'         => $type,
            'salary'       => $salary,
            'description'  => $description
        ];

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

        <select name="empresa_id" class="p-2 border rounded" required>
            <option value="" disabled selected>Selecione uma empresa</option>
            <?php foreach ($empresas as $emp): if (($emp['status'] ?? 'ativo') !== 'ativo') continue; ?>
                <option value="<?= (int)$emp['id'] ?>">
                    <?= htmlspecialchars($emp['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="municipio_id" class="p-2 border rounded" required>
            <option value="" disabled selected>Selecione uma localidade</option>
            <?php foreach ($municipios as $mun): ?>
                <option value="<?= (int)$mun['id'] ?>">
                    <?= htmlspecialchars($mun['nome']) . ' - ' . htmlspecialchars($mun['estado']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="type" class="p-2 border rounded" required>
            <option value="" disabled selected>Selecione o tipo</option>
            <?php foreach ($jobmethods as $jm): if (($jm['status'] ?? 'ativo') !== 'ativo') continue; ?>
                <option value="<?= htmlspecialchars($jm['nome']) ?>">
                    <?= htmlspecialchars($jm['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Novo campo: Categoria -->
        <select name="categoria_id" class="p-2 border rounded" required>
            <option value="" disabled selected>Selecione uma categoria</option>
            <?php foreach ($categorias as $categoria): if (($categoria['status'] ?? 'ativo') !== 'ativo') continue; ?>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const salaryInput = document.querySelector('input[name="salary"]');
    if (!salaryInput) return;

    // Máscara R$
    salaryInput.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, "");
        if (!v) { e.target.value = ""; return; }
        v = (v / 100).toFixed(2) + "";
        v = v.replace(".", ",");
        v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        e.target.value = "R$ " + v;
    });

    // Antes de enviar, limpa o campo
    salaryInput.form.addEventListener('submit', function() {
        const raw = salaryInput.value;
        const cleaned = raw.replace(/[^\d,]/g, "").replace(",", ".");
        salaryInput.value = cleaned;
    });
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
