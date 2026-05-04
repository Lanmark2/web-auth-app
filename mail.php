<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/PHPMailer/src/Exception.php";
require_once __DIR__ . "/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/PHPMailer/src/SMTP.php";

function sendVerifyMail ($toEmail, $code): bool
{
    $mail = new PHPMailer(true);

    $mail->CharSet = 'UTF-8';

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.adm.tools';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@shapran.site';
        $mail->Password = 'y2020ky2020K';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('noreply@shapran.site', 'Shapran.site');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Подтверждение почты';
        $mail->Body = "
        <h2>Подтверждение почты</h2>
        <p>Ваш код подтверждения:</p>
        <h1>$code</h1>";
        $mail->send();

        return true;

    } catch (Exception $error) {
        echo "SMTP Error: " . $error->getMessage() . "\n";
        return false;
    }
}
