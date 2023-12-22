<?php
require_once 'vendor/autoload.php';

// Paramètres SMTP
$transport = (new Swift_SmtpTransport('smtp.example.com', 587, 'tls'))
    ->setUsername('votre_adresse_email@example.com')
    ->setPassword('votre_mot_de_passe');

// Création de l'instance Swift Mailer
$mailer = new Swift_Mailer($transport);
