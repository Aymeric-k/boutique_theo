<?php

$stmt = $db->prepare("INSERT INTO users (user_Mail, user_Pseudo, user_Score) VALUES (:mail, :pseudo, :score");
$stmt->execute([
    ":mail" => $_POST["email"],
    ":pseudo" => $_POST["pseudo"],
    ":score" => $_POST["score"]
]);
