<?php
require_once __DIR__ . '/../../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function enviarRecuperacao($email, $token)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuração SMTP
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;

            // Remetente e destinatário
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress($email);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - Estagiando';

            $link = "https://estagiando.com/redefinir-senha?token=" . urlencode($token);
            $mail->Body = "
                <html>
                <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                    <title>Recuperação de Senha - Estagiando</title>
                </head>
                <body style='font-family: Arial, sans-serif; color:#333;'>
                    <h2 style='color:#0a1837;'>Recuperação de Senha</h2>
                    <p>Olá! Você solicitou a redefinição da sua senha no <strong>Estagiando</strong>.</p>
                    <p>Clique no link abaixo para criar uma nova senha:</p>
                    <p><a href='{$link}' style='color:#0a1837; font-weight:bold;'>{$link}</a></p>
                    <p>⚠️ Este link é válido por 1 hora.</p>
                    <br>
                    <p style='font-size:13px; color:#777;'>Se você não fez esta solicitação, basta ignorar este e-mail.</p>
                </body>
                </html>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
