<?php

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    echo 'Votre compte a été confirmé avec succès!';
} else {
    echo 'Token de confirmation manquant.';
}
?>
