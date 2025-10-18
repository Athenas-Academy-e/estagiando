<?php
require_once __DIR__ . '/../inc/functions.php';
include __DIR__ . '/../templates/header.php';

// Carrega listas de selects
$empresas    = getEmpresas();
$municipios  = getMunicipios();
$jobmethods  = getJobmethod();
$categorias  = getCategorias();

// Obtém o ID da vaga
$id  = $_GET['id'] ?? null;
$job = $id ? getJobById($id) : null;

if (!$job) {
    die("Vaga não encontrada.");
}

// Tenta identificar o município atual a partir de 'location'
$selectedMunicipioId = '';
if (!empty($job['location'])) {
    [$nomeLoc, $ufLoc] = array_map('trim', explode(',', $job['location'] . ','));
    foreach ($municipios as $m) {
        if (
            mb_strtolower($m['nome']) === mb_strtolower($nomeLoc ?? '') &&
            mb_strtolower($m['estado']) === mb_strtolower($ufLoc ?? '')
        ) {
            $selectedMunicipioId = (string)$m['id'];
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empresa_id   = $_POST['empresa_id']   ?? '';
    $municipio_id = $_POST['municipio_id'] ?? '';
    $categoria_id = $_POST['categoria_id'] ?? '';
    $title        = $_POST['title']        ?? '';
    $type         = $_POST['type']         ?? '';
    $salary       = $_POST['salary']       ?? '';
    $description  = $_POST['description']  ?? '';

    // Monta campo 'location' a partir do município
    $location = '';
    if ($municipio_id) {
        $m = getMunicipioById($municipio_id);
        if ($m) {
            $location = $m['nome'] . ', ' . $m['estado'];
        }
    }

    // Limpa o salário para salvar sem "R$"
    $salary = preg_replace('/[^\d,]/', '', $salary);
    $salary = str_replace(',', '.', $salary);

    if (!$title || !$empresa_id) {
        $error = "Informe o título e selecione uma empresa.";
    } else {
        $data = [
            'id'           => $id,
            'title'        => $title,
            'company_id'   => (int)$empresa_id,
            'categoria_id' => $categoria_id ? (int)$categoria_id : null,
            'location'     => $location,
            'type'         => $type,
            'salary'       => $salary,
            'description'  => $description
        ];

        updateJob($data);
        header('Location: index.php');
        exit;
    }
}
?>

<div class="md:col-span-3 bg-white p-6 rounded-lg shadow-sm">
    <h2 class="text-xl font-semibold mb-4">Editar vaga</h2>

    <?php if (!empty($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-3">

        <!-- Título -->
        <input 
            type="text" 
            name="title" 
            value="<?= htmlspecialchars($job['title'] ?? '') ?>" 
            placeholder="Título" 
            class="p-2 border rounded" 
            required
        >

        <!-- Empresa -->
        <select name="empresa_id" class="p-2 border rounded" required>
            <option value="" disabled>Selecione uma empresa</option>
            <?php foreach ($empresas as $emp): if (($emp['status'] ?? 'ativo') !== 'ativo') continue; ?>
                <option 
                    value="<?= (int)$emp['id'] ?>" 
                    <?= ((int)$job['company_id'] === (int)$emp['id']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($emp['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Município -->
        <select name="municipio_id" class="p-2 border rounded" required>
            <option value="" disabled>Selecione uma localidade</option>
            <?php foreach ($municipios as $mun): ?>
                <option 
                    value="<?= (int)$mun['id'] ?>" 
                    <?= ((string)$selectedMunicipioId === (string)$mun['id']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($mun['nome']) . ' - ' . htmlspecialchars($mun['estado']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Tipo -->
        <select name="type" class="p-2 border rounded" required>
            <option value="" disabled>Selecione o tipo</option>
            <?php foreach ($jobmethods as $jm): if (($jm['status'] ?? 'ativo') !== 'ativo') continue; ?>
                <option 
                    value="<?= htmlspecialchars($jm['nome']) ?>" 
                    <?= ($job['type'] === $jm['nome']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($jm['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Categoria -->
        <select name="categoria_id" class="p-2 border rounded" required>
            <option value="" disabled>Selecione uma categoria</option>
            <?php foreach ($categorias as $cat): if (($cat['status'] ?? 'ativo') !== 'ativo') continue; ?>
                <option 
                    value="<?= (int)$cat['id'] ?>" 
                    <?= ((int)($job['categoria_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($cat['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Salário -->
        <input 
            type="text" 
            name="salary" 
            value="<?= htmlspecialchars($job['salary'] ?? '') ?>" 
            placeholder="Salário" 
            class="p-2 border rounded md:col-span-2"
        >

        <!-- Descrição -->
        <textarea 
            name="description" 
            placeholder="Descrição" 
            class="p-2 border rounded md:col-span-2" 
            rows="4"
        ><?= htmlspecialchars($job['description'] ?? '') ?></textarea>

        <!-- Botões -->
        <div class="md:col-span-2 flex justify-end gap-2">
            <a href="index.php" class="px-3 py-2 border rounded">Cancelar</a>
            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Salvar alterações</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const salaryInput = document.querySelector('input[name="salary"]');
    if (!salaryInput) return;

    // Exibe valor formatado ao carregar (ex: 1000.00 → R$ 1.000,00)
    const bootVal = salaryInput.value.trim();
    if (bootVal && !bootVal.startsWith('R$')) {
        const num = parseFloat(bootVal.replace(',', '.'));
        if (!isNaN(num)) {
            salaryInput.value = formatBRL(num);
        }
    }

    // Máscara ao digitar
    salaryInput.addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, "");
        if (!v) { e.target.value = ""; return; }
        v = (v / 100).toFixed(2) + "";
        v = v.replace(".", ",");
        v = v.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        e.target.value = "R$ " + v;
    });

    // Remove o "R$" ao enviar
    salaryInput.form.addEventListener('submit', function() {
        const raw = salaryInput.value;
        const cleaned = raw.replace(/[^\d,]/g, "").replace(",", ".");
        salaryInput.value = cleaned;
    });

    function formatBRL(n) {
        let s = (n.toFixed(2) + '').replace('.', ',');
        s = s.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return 'R$ ' + s;
    }
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
