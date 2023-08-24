<?php
session_start();

// Assurez-vous que cette ligne est en haut de tous vos fichiers qui utilisent des sessions
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['variantId']) && isset($input['newQuantity'])) {
    $variantId = $input['variantId'];
    $newQuantity = $input['newQuantity'];

    if (isset($_SESSION['panier'][$variantId])) {
        $_SESSION['panier'][$variantId]['quantite'] = $newQuantity;
        $newTotal = 0;
        foreach ($_SESSION['panier'] as $item) {
            $newTotal += $item['prix'] * $item['quantite'];
        }
        echo json_encode(['status' => 'success', 'newTotal' => $newTotal]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Variant ID not found in cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
