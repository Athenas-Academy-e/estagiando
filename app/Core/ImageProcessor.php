<?php

class ImageProcessor
{
    /**
     * Processa imagem, converte, redimensiona e salva em diretório específico.
     *
     * @param array  $file  Arquivo enviado ($_FILES['alguma_coisa'])
     * @param string $folder Caminho relativo dentro de /public/assets/
     * @param int    $maxWidth Largura máxima
     * @param int    $maxHeight Altura máxima
     * @param int    $quality Qualidade de 0 a 100
     * @return string|false Caminho final ou false
     */
    public static function processImage($file, $folder, $maxWidth = 800, $maxHeight = 800, $quality = 85)
    {
        if (!$file || $file['error'] !== 0) {
            return false;
        }

        /* ───────────────────────────────
           Detecta MIME
        ───────────────────────────────*/
        $tempPath = $file['tmp_name'];
        $info = getimagesize($tempPath);
        if (!$info) return false;

        $width  = $info[0];
        $height = $info[1];
        $mime   = $info['mime'];

        /* ───────────────────────────────
           Carrega imagem conforme MIME
        ───────────────────────────────*/
        switch ($mime) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($tempPath);
                $ext = '.jpg';
                break;

            case 'image/png':
                $source = imagecreatefrompng($tempPath);
                $ext = '.png';
                break;

            case 'image/webp':
                $source = imagecreatefromwebp($tempPath);
                $ext = '.webp';
                break;

            default:
                return false; // tipo inválido
        }

        /* ───────────────────────────────
           Mantém proporção
        ───────────────────────────────*/
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth  = (int) ($width * $ratio);
        $newHeight = (int) ($height * $ratio);

        /* ───────────────────────────────
           Cria nova imagem
        ───────────────────────────────*/
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Suporte a transparência
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }

        imagecopyresampled(
            $newImage, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );

        /* ───────────────────────────────
           Cria pasta caso não exista
        ───────────────────────────────*/
        $targetDir = __DIR__ . '/../../public_html/assets/' . $folder;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        /* ───────────────────────────────
           Define nome único
        ───────────────────────────────*/
        $filename = uniqid("img_") . $ext;
        $savePath = $targetDir . '/' . $filename;

        /* ───────────────────────────────
           Salva a imagem
        ───────────────────────────────*/
        switch ($ext) {
            case '.jpg':
                imagejpeg($newImage, $savePath, $quality);
                break;

            case '.png':
                imagepng($newImage, $savePath, 6);
                break;

            case '.webp':
                imagewebp($newImage, $savePath, $quality);
                break;
        }

        imagedestroy($source);
        imagedestroy($newImage);

        // Retorna caminho relativo (para banco)
        return '/assets/' . $folder . '/' . $filename;
    }
}
