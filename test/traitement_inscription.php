<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = bin2hex(random_bytes(32));

    $to = $_POST['email'];
    $subject = 'Confirmation d\'inscription';
    $message = "Cliquez sur le lien suivant pour confirmer votre inscription : \n\n";
    $message .= "https://tonsite.com/confirmation.php?token=$token";

    require 'vendor/autoload.php';

    $transport = (new Swift_SmtpTransport('smtp.example.com', 587))
        ->setUsername('your_username')
        ->setPassword('your_password');

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message($subject))
        ->setFrom(['noreply@tonsite.com' => 'Ton Site'])
        ->setTo([$to])
        ->setBody($message);

    $result = $mailer->send($message);

    if ($result) {
        echo 'Un e-mail de confirmation a été envoyé à votre adresse.';
    } else {
        echo 'Erreur lors de l\'envoi de l\'e-mail de confirmation.';
    }
}
?>
