<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 🔹 Título e SEO dinâmicos -->
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle)  : 'Estagiando — Conectando talentos e oportunidades' ?></title>
  <meta name="description" content="<?= isset($pageDescription)
                                      ? htmlspecialchars($pageDescription)
                                      : 'Encontre estágios, vagas e oportunidades em Juiz de Fora e região. O Estagiando conecta estudantes e empresas de forma rápida e gratuita.' ?>">

  <meta name="keywords" content="<?= isset($pageKeywords)
                                    ? htmlspecialchars($pageKeywords)
                                    : 'estágio, vagas, Juiz de Fora, empregos, estudantes, empresas, oportunidades, petropolis' ?>">

  <meta name="author" content="Estagiando">
  <meta name="robots" content="index, follow">
  <meta name="language" content="pt-BR">

  <!-- 🔹 Open Graph / Redes Sociais -->
  <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Estagiando' : 'Estagiando — Conectando talentos e oportunidades' ?>">
  <meta property="og:description" content="<?= isset($pageDescription)
                                              ? htmlspecialchars($pageDescription)
                                              : 'Conecte-se às melhores oportunidades de estágio e emprego da região.' ?>">
  <meta property="og:image" content="<?= BASE_URL ?>assets/og-image.jpg">
  <meta property="og:url" content="<?= BASE_URL . ($_SERVER['REQUEST_URI'] ?? '') ?>">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="pt_BR">

  <!-- 🔹 Favicon -->
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/favicon/favicon-96x96.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/favicon/apple-touch-icon.png">

  <!-- 🔹 CSS compilado -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/output.css">

  <!-- Schema markup (Google) -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Estagiando",
      "url": "<?= BASE_URL ?>",
      "logo": "<?= BASE_URL ?>assets/favicon/favicon-96x96.png",
      "sameAs": [
        "https://www.instagram.com/estagiando",
      ]
    }
  </script>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>