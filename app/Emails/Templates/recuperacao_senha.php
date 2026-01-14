<?php
$link = $link ?? '#';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333;">
    <h2 style="color:#0a1837;">Recuperação de Senha</h2>

    <p>Olá! Você solicitou a redefinição da sua senha no <strong>Estagiando</strong>.</p>

    <p>Clique no link abaixo para criar uma nova senha:</p>

    <p>
        <a href="<?= $link ?>" style="color:#0a1837; font-weight:bold;">
            <?= $link ?>
        </a>
    </p>

    <p>⚠️ Este link é válido por 1 hora.</p>

    <p style="font-size:13px; color:#777;">
        Se você não fez esta solicitação, basta ignorar este e-mail.
    </p>
</body>
</html>
