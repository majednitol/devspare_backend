<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
 function sendVerificationEmail($email, $verification_code)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'majadrahman7@gmail.com'; 
            $mail->Password   = 'bgfdnoloupujaeyo'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('majadrahman7@gmail.com', 'Devspare');
            $mail->addAddress($email, 'Recipient Name'); 
            $mail->isHTML(true);
            $mail->Subject = 'Activate Your Account';
            $mail->Body    = "
                <h1>Welcome to Devspare!</h1>
                <p>Thank you for registering. To activate your account, please use the verification code below:</p>
                <h2 style='color: #3498db;'>$verification_code</h2>
                <p>This code will expire in 10 minutes.</p>
                <p>If you did not request this, please ignore this email.</p>
                <p>Best regards,<br>Devspare Team</p>
            ";
            $mail->AltBody = "Thank you for registering. Use the verification code: $verification_code to activate your account. This code will expire in 10 minutes.";

            $mail->send();
            echo "Activation email sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }