<?php
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Curriculo.php';

class PdfController
{
    /**
     * 📄 Gera o PDF do currículo do profissional logado.
     * @param bool $visualizar — Se true, abre no navegador; se false, baixa o arquivo.
     */
    public function curriculo($visualizar = false)
    {
        Auth::check('profissional');

        $curriculoModel = new Curriculo();
        $profissionalId = $_SESSION['profissional_id'];
        $curriculo = $curriculoModel->buscar($profissionalId);
        $profissional = $curriculoModel->buscarprofissional($profissionalId);

        if (!$curriculo) {
            echo "<main class='max-w-3xl mx-auto text-center py-20 text-gray-600'>
                    <h2 class='text-xl font-bold mb-2'>Nenhum currículo encontrado 😕</h2>
                    <p>Crie seu currículo na área do profissional antes de gerar o PDF.</p>
                  </main>";
            return;
        }

        // Caminho da logo
        $logo = __DIR__ . '/../../public_html/assets/Logo_dark.png';
        if (!file_exists($logo)) {
            $logo = null;
        }

        // Monta dados estruturados esperados pelo gerador
        $dados = [
            'nome' => $curriculo['nome'] ?? '',
            'cargo' => '',
            'foto' => !empty($profissional['foto'])
                ? $profissional['foto']
                : null,
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

        // Adicionais (caso exista)
        if (!empty($curriculo['adicionais'])) {
            $dados['competencias'][] = 'Outros: ' . $curriculo['adicionais'];
        }

        // Caminho temporário para gerar o PDF
        $arquivo = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'curriculo_' . $profissionalId . '.pdf';

        // Gerar PDF
        require_once __DIR__ . '/../Core/pdf_generator.php';
        gerarPDF($arquivo, $dados, $logo);

        // Retornar arquivo
        if (file_exists($arquivo)) {
            header('Content-Type: application/pdf; charset=utf-8');
            header(
                'Content-Disposition: ' .
                    ($visualizar ? 'inline' : 'attachment') .
                    '; filename="curriculo_' . preg_replace('/[^a-z0-9]/i', '_', $dados['nome']) . '.pdf"'
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

    /** 📥 Download direto */
    public function download()
    {
        $this->curriculo(false);
    }

    /** 👀 Visualizar no navegador */
    public function view()
    {
        $this->curriculo(true);
    }
}
