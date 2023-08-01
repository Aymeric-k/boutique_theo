<?php
try{

    $db = new PDO('mysql:host=localhost;dbname=boutique_theo','root','');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->exec('SET NAMES "UTF8"');
}catch(PDOException $e){
    echo 'Erreur : '. $e -> getMessage();
    die();
}
