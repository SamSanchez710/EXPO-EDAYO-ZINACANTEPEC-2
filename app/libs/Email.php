<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Email {
    public static function sendVerificationEmail($to, $token) {
        $mail = new PHPMailer(true);
        try {
            $fromEmail = 'edayozinacantepec2025@gmail.com';
            $fromName  = 'Expo Edayo';
            $smtpPassword = 'jofahgsmnzexwaoo'; // contraseña de aplicación Gmail

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $fromEmail;
            $mail->Password   = $smtpPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = 'Confirma tu cuenta - Expo Edayo';

            // Detectar si estamos en localhost o en hosting
            $host = $_SERVER['HTTP_HOST'];
            if (strpos($host, 'localhost') !== false) {
                // Ruta local
                $baseUrl = "http://localhost/EXPO-EDAYO-ZINACANTEPEC-2/public";
            } else {
                // Hosting
                $baseUrl = "http://ExpoAprendeEdayo.infinityfreeapp.com";
            }

            $link = $baseUrl . "/verificar.php?token=$token";

            $mail->Body = "
                <h2>Bienvenido a Expo Edayo</h2>
                <p>Gracias por registrarte. Haz clic en el botón para verificar tu correo y activar tu cuenta:</p>
                <a href='$link' style='display:inline-block; padding:10px 20px; background-color:#28a745; color:white; text-decoration:none; border-radius:5px;'>Verificar correo</a>
                <p>Si no te registraste, ignora este correo.</p>
            ";

            $mail->AltBody = "Visita este enlace para verificar tu cuenta: $link";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error PHPMailer: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>
