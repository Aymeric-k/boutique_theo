<?php
session_start();
// Définir le chemin du fichier de logs
$logFile = __DIR__ . '/../logs.log';

// Fonction pour enregistrer les logs
function log_message($message)
{
    global $logFile;
    error_log($message . "\n", 3, $logFile);
}

$response = ["success" => false];

if (isset($_GET['variantId'])) {
    $variantId = $_GET['variantId'];
    $itemToRemove = null;

    foreach ($_SESSION['panier'] as $key => $item) {
        if ($item['variantId'] == $variantId) {
            $itemToRemove = $key;
            break;
        }
    }

    if ($itemToRemove !== null) {
        unset($_SESSION['panier'][$itemToRemove]);

        $newTotal = 0;
        foreach ($_SESSION['panier'] as $item) {
            $newTotal += $item['prix'] * $item['quantite'];
        }

        $response["success"] = true;
        $response["newTotal"] = $newTotal;
    } else {
        $response["error"] = "Clé d'article non trouvée dans le panier";
    }
} else {
    $response["error"] = "Clé d'article non fournie";
}
log_message("Requête GET variantId: " . $_GET['variantId']);
header('Content-Type: application/json');
echo json_encode($response);
?>