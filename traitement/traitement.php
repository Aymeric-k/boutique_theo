<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/advergame/rugby.js/connect/connect.php';

header("Content-Type: application/json");

$email = $_POST["email"];
$newScore = $_POST["score"];
$pseudo = $_POST["pseudo"];
$response = [];

$stmt = $db->prepare("SELECT user_Score FROM users WHERE user_Mail = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    // L'e-mail existe déjà
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $existingScore = $row["user_Score"];

    $response["status"] = "exists";
    $response["score"] = $existingScore;


    if ($newScore > $existingScore) {

        $stmt = $db->prepare("UPDATE users SET user_Score = ? WHERE user_Mail = ?");
        $stmt->execute([$newScore, $email]);
        $response["isHigher"] = true;
    } else {
        $response["isHigher"] = false;
    }
} else {
    $stmt = $db->prepare("INSERT INTO users (user_Mail, user_Pseudo, user_Score) VALUES (:mail, :pseudo, :score)");
    $stmt->execute([
        ":mail" => $email,
        ":pseudo" => $pseudo,
        ":score" => $newScore
    ]);
    // L'e-mail n'existe pas dans la base de données
    $response["status"] = "not_exists";
}

echo json_encode($response);

// header("location:../index.php");