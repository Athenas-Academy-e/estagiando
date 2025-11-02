<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Curriculo.php';

class PdfController
{
    /**
     * 📄 Gera o PDF do currículo de um profissional
     * - Se chamado sem parâmetro: usa o profissional logado.
     * - Se chamado com $id: usado por admin/empresa para ver currículo de outro candidato.
     * 
     * @param int|null $id ID do profissional (opcional)
     * @param bool $visualizar Exibir no navegador (true) ou baixar (false)
     */
    public function curriculo($id = null, $visualizar = false)
    {
        // 🧩 Se foi passado um ID, é admin/empresa acessando o currículo de outro candidato
        if ($id !== null) {
            Auth::check('admin'); // ou 'empresa' se quiser liberar para empresas também
            $profissionalId = (int)$id;
        } else {
            // 🔒 Profissional gerando o próprio currículo
            Auth::check('profissional');
            $profissionalId = $_SESSION['profissional_id'];
        }

        $curriculoModel = new Curriculo();
        $curriculo = $curriculoModel->buscar($profissionalId);
        $profissional = $curriculoModel->buscarprofissional($profissionalId);

        if (!$curriculo) {
            echo "<main class='max-w-3xl mx-auto text-center py-20 text-gray-600'>
                    <h2 class='text-xl font-bold mb-2'>Nenhum currículo encontrado 😕</h2>
                    <p>O profissional ainda não criou seu currículo.</p>
                  </main>";
            return;
        }

        // Caminho da logo
        $logo = __DIR__ . '/../../public_html/assets/Logo_dark.png';
        if (!file_exists($logo)) {
            $logo = null;
        }

        // Estrutura de dados para o PDF
        $dados = [
            'nome' => $curriculo['nome'] ?? '',
            'cargo' => '',
            'foto' => !empty($profissional['foto']) ? $profissional['foto'] : null,
            'resumo' => $curriculo['resumo'] ?? '',
            'experiencias' => [
                [
                    'cargo' => '',
                    'empresa' => '',
                    'periodo' => '',
                    'descricao' => $curriculo['experiencia'] ?? ''
                ]
            ],
            'formacao' => [
                [
                    'curso' => $curriculo['formacao'] ?? '',
                    'instituicao' => '',
                    'periodo' => ''
                ],
                [
                    'curso' => $curriculo['cursos'] ?? '',
                    'instituicao' => '',
                    'periodo' => ''
                ]
            ],
            'competencias' => array_filter(array_map('trim', explode(',', $curriculo['habilidades'] ?? ''))),
        ];

        if (!empty($curriculo['adicionais'])) {
            $dados['competencias'][] = 'Outros: ' . $curriculo['adicionais'];
        }

        // Caminho temporário e geração do PDF
        $arquivo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'curriculo_' . $profissionalId . '.pdf';
        require_once __DIR__ . '/../Core/pdf_generator.php';
        gerarPDF($arquivo, $dados, $logo);

        if (file_exists($arquivo)) {
            header('Content-Type: application/pdf; charset=utf-8');
            header(
                'Content-Disposition: ' .
                    ($visualizar ? 'inline' : 'attachment') . '; filename="curriculo_' .
                    preg_replace('/[^a-z0-9]/i', '_', $dados['nome']) . '.pdf"'
            );
            header('Content-Length: ' . filesize($arquivo));
            readfile($arquivo);
            unlink($arquivo);
        } else {
            http_response_code(500);
            echo "<main class='max-w-3xl mx-auto text-center py-20 text-red-600'>
                    <h2 class='text-xl font-bold mb-2'>Erro ao gerar PDF 😢</h2>
                    <p>Tente novamente em alguns instantes.</p>
                  </main>";
        }

        exit;
    }

    /** 📥 Download direto (profissional logado) */
    public function download()
    {
        $this->curriculo(null, false);
    }

    /** 👀 Visualizar no navegador (profissional logado) */
    public function view()
    {
        $this->curriculo(null, true);
    }
}
