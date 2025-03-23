<?php

session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';


$categorieId = isset($_POST['categorieId']) ? $_POST['categorieId'] : 0;
$sql = "SELECT * 
FROM categorie
WHERE categorieLibelle LIKE :categorieLibelle";
$request = $db->prepare($sql);
$request->execute(['categorieLibelle' => '%' . $_POST['categorieLibelle'] . '%']);
$categoryExist = $request->fetch();


if ($categorieId == 0) {
    if (!$categoryExist) {
        $sql = "INSERT INTO categorie (categorieLibelle) VALUES (:categorieLibelle)";
    } else {
        echo 'Catégorie du même nom existe dèja';
        echo '<br>';
        echo '<a href="./categories.php">Retour</a>';
        exit();
    }
} else {
    $sql = "UPDATE categorie SET categorieLibelle =:categorieLibelle WHERE categorieId = :categorieId";
    $bind['categorieId'] = $categorieId;
}
$bind['categorieLibelle'] = $_POST['categorieLibelle'];
$request = $db->prepare($sql);
$request->execute($bind);

header('Location: ./categories.php');
