<?php
// Définir le chemin du fichier de logs
$logFile = __DIR__ . '/logs.log';

// Lire le corps de la requête
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Vérifier si le message d'erreur est présent
if (isset($data['message'])) {
    // Enregistrer l'erreur dans le fichier de logs
    error_log("Erreur JavaScript: " . $data['message'] . "\n", 3, $logFile);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No error message provided']);
}