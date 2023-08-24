<?php
session_start();

$response = ["success" => false];

// Vide le panier en session
$_SESSION['panier'] = [];

$response["success"] = true;

header('Content-Type: application/json');
echo json_encode($response);
?>