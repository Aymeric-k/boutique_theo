<?php
session_start();

// Assurez-vous que cette ligne est en haut de tous vos fichiers qui utilisent des sessions
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['variantId']) && isset($input['newQuantity'])) {
    $variantId = $input['variantId'];
    $newQuantity = $input['newQuantity'];

    $found = false;
    $newTotal = 0;

    // Parcours de $_SESSION['panier'] pour trouver le bon variantId
    foreach ($_SESSION['panier'] as &$item) {
        if ($item['variantId'] === $variantId) {
            $item['quantite'] = $newQuantity;
            $found = true;
        }
        // Calcul du nouveau total
        $newTotal += $item['prix'] * $item['quantite'];
    }

    if ($found) {
        echo json_encode(['status' => 'success', 'newTotal' => $newTotal]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Variant ID not found in cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}