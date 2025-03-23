<?php
$id = $_POST['id'];
$value = $_POST['value'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';
// Vérifiez d'abord l'ID reçu
if (isset($id) &&  $value == 'photo') {
    // Supprimez l'élément de la base de données
    $sql = "DELETE FROM photo WHERE photoId = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
} 
if (isset($id) &&  $value == 'variant') {
    // Supprimez l'élément de la base de données
    
    $sql = "DELETE FROM variant_produit WHERE variantId = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
} 