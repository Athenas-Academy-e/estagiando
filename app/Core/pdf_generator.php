<?php
/**
 * 📄 Gerador de Currículo Profissional — Estilo Moderno com Foto e Marca d’Água
 * ---------------------------------------------------------------------------
 * Usa TCPDF localizado em /libs/TCPDF
 * ---------------------------------------------------------------------------
 */

define('K_PATH_FONTS', __DIR__ . '/../../libs/TCPDF/fonts/');
require_once __DIR__ . '/../../libs/TCPDF/tcpdf.php';

function gerarPDF($arquivo, $dados, $logoPath = null)
{
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Estagiando');
    $pdf->SetAuthor('Estagiando');
    $pdf->SetTitle('Currículo De '.$dados['nome']);
    $pdf->SetMargins(20, 20, 20);
    $pdf->AddPage();

    // 🎨 Cores principais
    $azul = [10, 24, 55];
    $verde = [151, 221, 58];
    $cinza = [80, 80, 80];

    /**
     * ============================================================
     * 🌊 MARCA D'ÁGUA — LOGO CENTRAL TRANSLÚCIDA
     * ============================================================
     */
    if ($logoPath && file_exists($logoPath)) {
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();

        // Salva o estado atual do gráfico
        $pdf->StartTransform();
        $pdf->SetAlpha(0.08); // transparência
        // Centraliza a imagem
        $pdf->Image(
            $logoPath,
            ($pageWidth - 120) / 2, // X central
            ($pageHeight - 120) / 2, // Y central
            120, 120, '', '', '', false, 300, '', false, false, 0, false, false, false
        );
        $pdf->StopTransform();
    }

    /**
     * ============================================================
     * FOTO DO PROFISSIONAL (opcional)
     * ============================================================
     */

    if (!empty($dados['foto']) && file_exists($dados['foto'])) {
        $imageFile = '/../../public_html'.$dados['foto'];
        $pdf->Image($imageFile, 150, 20, 35, 35, '', '', '', true, 300, '', false, false, 0, false, false, false);
    }

    /**
     * ============================================================
     * CABEÇALHO — Nome e Cargo
     * ============================================================
     */
    $pdf->SetFont('helvetica', 'B', 22);
    $pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
    $pdf->Cell(0, 10, $dados['nome'], 0, 1, 'L');

    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetTextColor(50, 50, 50);
    $pdf->Cell(0, 8, $dados['cargo'], 0, 1, 'L');
    $pdf->Ln(3);

    // Linha verde
    $pdf->SetDrawColor($verde[0], $verde[1], $verde[2]);
    $pdf->SetLineWidth(1);
    $pdf->Line(20, 43, 190, 43);
    $pdf->Ln(8);

    /**
     * ============================================================
     * RESUMO PROFISSIONAL
     * ============================================================
     */
    if (!empty($dados['resumo'])) {
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
        $pdf->Cell(0, 8, 'Resumo Profissional', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->MultiCell(0, 7, $dados['resumo'], 0, 'J', false);
        $pdf->Ln(8);
    }

    /**
     * ============================================================
     * EXPERIÊNCIA PROFISSIONAL
     * ============================================================
     */
    if (!empty($dados['experiencias'])) {
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
        $pdf->Cell(0, 8, 'Experiência Profissional', 0, 1, 'L');
        $pdf->Ln(2);

        foreach ($dados['experiencias'] as $exp) {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 6, trim(($exp['cargo'] ?? '') . ' — ' . ($exp['empresa'] ?? '')), 0, 1, 'L');
            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->Cell(0, 5, ($exp['periodo'] ?? ''), 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 11);
            $pdf->MultiCell(0, 6, $exp['descricao'] ?? '', 0, 'J', false);
            $pdf->Ln(4);
        }
    }

    /**
     * ============================================================
     * EDUCAÇÃO / FORMAÇÃO
     * ============================================================
     */
    if (!empty($dados['formacao'])) {
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
        $pdf->Cell(0, 8, 'Educação', 0, 1, 'L');
        $pdf->Ln(2);

        foreach ($dados['formacao'] as $form) {
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 6, ($form['curso'] ?? ''), 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 11);
            $pdf->Cell(0, 6, ($form['instituicao'] ?? '') . ' — ' . ($form['periodo'] ?? ''), 0, 1, 'L');
            $pdf->Ln(2);
        }
    }

    /**
     * ============================================================
     * COMPETÊNCIAS
     * ============================================================
     */
    if (!empty($dados['competencias'])) {
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
        $pdf->Cell(0, 8, 'Competências', 0, 1, 'L');
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(40, 40, 40);
        foreach ($dados['competencias'] as $comp) {
            $pdf->MultiCell(0, 6, "• " . $comp, 0, 'L', false);
        }
    }

    /**
     * ============================================================
     * RODAPÉ
     * ============================================================
     */
    $pdf->SetY(-20);
    $pdf->SetFont('helvetica', 'I', 9);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->Cell(0, 10, 'Gerado automaticamente — Estagiando.com.br', 0, 0, 'C');

    $pdf->Output($arquivo, 'F');
}
