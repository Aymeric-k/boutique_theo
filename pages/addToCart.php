<?php

session_set_cookie_params(604800); 
session_start();
// Durée de vie d'une semaine
require_once '../bdd/connect.php';
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$response = ["success" => false];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql ='SELECT variantPrix, variantFormat, photoUrl, photoLegende, produitLibelle from variant_produit INNER JOIN photo on variant_produit.produitId =photo.produitId INNER JOIN produits ON variant_produit.produitId = produits.produitId WHERE variantId = :size';
    $request = $db->prepare($sql);
    $request->execute(['size' => $_POST['size']]);
    $productInfos = $request->fetch();
    echo'';
    $produitId = $_POST['produitId'];
    $quantite = $_POST['quantity'];
    $variantId = trim($_POST['size']);
    $clePanier = $variantId;
    if(isset($_SESSION['panier'][$clePanier])) {
        
        $_SESSION['panier'][$clePanier]['quantite'] += $quantite;
    } else {
        $_SESSION['panier'][$clePanier] = [
            'produitId' => $produitId,
            'quantite' => $quantite,
            'variantId' => $variantId,
            'prix' => $productInfos['variantPrix'],
            'photoUrl'=> $productInfos['photoUrl'],
            'photoLegende'=> $productInfos['photoLegende'],
            'format' => $productInfos['variantFormat'],
            'libelle' => $productInfos['produitLibelle']
        ];
    }

    $response["success"] = true;
    $response["panier"] = $_SESSION['panier'];

}

header('Content-Type: application/json');
echo json_encode($response);
?>