<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo ao Estagiando</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333;">
    <h2 style="color:#0a1837;">🎉 Bem-vindo ao Estagiando!</h2>

    <p>Olá, <strong><?= htmlspecialchars($nome) ?></strong>!</p>

    <p>
        Seu cadastro como <strong><?= htmlspecialchars($tipo) ?></strong> foi realizado com sucesso.
    </p>

    <p>
        Agora você já pode acessar a plataforma:
    </p>

    <p>
        <a href="<?= htmlspecialchars($link) ?>"
           style="background:#0a1837; color:#fff; padding:10px 15px; text-decoration:none; border-radius:4px;">
           Acessar minha conta
        </a>
    </p>

    <br>
    <p style="font-size:13px; color:#777;">
        Equipe Estagiando 🚀
    </p>
</body>
</html>
