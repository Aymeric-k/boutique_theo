<?php

// Définir le chemin du fichier de logs
$logFile = __DIR__ . '/../logs.log';

// Fonction pour enregistrer les logs
function log_message($message)
{
    global $logFile;
    error_log($message . "\n", 3, $logFile);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    log_message("Formulaire soumis avec la méthode POST.");
    
    // Captcha token
    $captcha_token = isset($_POST['captchaToken']) ? $_POST['captchaToken'] : null;
    log_message("Captcha Token: " . $captcha_token);

    // Validation du CAPTCHA
    $recaptcha_secret = '6LdYvOMpAAAAAEGN1XDDrgAg0E0HTheLc1t0BvCH';
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $captcha_token);
    $recaptcha_data = json_decode($recaptcha_response);
    log_message("ReCAPTCHA response: " . print_r($recaptcha_data, true));

    if ($recaptcha_data->success == true && $recaptcha_data->score >= 0.5) {
        log_message("Captcha validé avec succès.");

        // Récupération des données du formulaire
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $subject = htmlspecialchars($_POST['subject']);
        $description = htmlspecialchars($_POST['description']);

        log_message("Données du formulaire: Name: $name, Email: $email, Subject: $subject, Description: $description");

        // Envoi de l'e-mail
        $to = "theo.kreichershop@gmail.com"; // Adresse e-mail de l'administrateur
        $subject = "Formulaire de contact envoyé, sujet : - $subject";
        $message = "Nom: $name\n";
        $message .= "E-mail: $email\n";
        $message .= "Sujet: $subject\n";
        $message .= "Message:\n$description\n";

        // Envoi de l'e-mail
        if (mail($to, $subject, $message)) {
            log_message("E-mail envoyé avec succès.");
            echo "Votre message a été envoyé avec succès.";
        } else {
            log_message("Erreur lors de l'envoi de l'e-mail.");
            echo "Une erreur s'est produite lors de l'envoi de votre message.";
        }
    } else {
        log_message("Captcha non valide ou score insuffisant.");
        echo "Veuillez valider le CAPTCHA.";
    }
} else {
    log_message("Méthode non POST utilisée.");
}