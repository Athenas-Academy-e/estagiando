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

            $link = BASE_URL . "redefinir-senha?token=" . urlencode($token);
            $mail->Body = "
                <h2>Recuperação de Senha</h2>
                <p>Você solicitou redefinição de senha. Clique no link abaixo:</p>
                <p><a href='{$link}'>{$link}</a></p>
                <p>Este link expira em 1 hora.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
