<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

// Définir le chemin du fichier de logs
$logFile = __DIR__ . '/logs.log';

// Fonction pour enregistrer les logs
function log_message($message)
{
    global $logFile;
    error_log($message . "\n", 3, $logFile);
}

try {
    log_message("Début du traitement de la création/mise à jour d'un code promo");

    $codePromoId = isset($_POST['codePromoId']) ? $_POST['codePromoId'] : 0;
    log_message("Code Promo ID : " . $codePromoId);

    // Vérification si le code promo existe déjà
    $sql = "SELECT * FROM code_promo WHERE codePromo LIKE :codePromo";
    $request = $db->prepare($sql);
    $request->execute(['codePromo' => '%' . $_POST['codePromo'] . '%']);
    $codePromoExist = $request->fetch();

    log_message("Vérification si le code promo existe : " . ($codePromoExist ? 'Existe déjà' : 'Nouveau code promo'));

    $freeShipping = isset($_POST['freeShipping']) ? 1 : 0;
    log_message("Free Shipping sélectionné : " . $freeShipping);

    // Conversion de la date de début
    $dateDebut = $_POST['codePromoDateDebut'] ?? '';
    if ($dateDebut) {
        $dateDebut .= (strlen($dateDebut) === 10) ? ' 00:00:00' : '';
        $dateTime = new DateTime($dateDebut);
        $dateTime->setTimezone(new DateTimeZone('UTC'));
        $utcTimeDateDebut = $dateTime->format('Y-m-d H:i:s');
        log_message("Date de début UTC : " . $utcTimeDateDebut);
    } else {
        log_message("Date de début non valide");
        echo 'Date de début non valide';
        exit();
    }

    // Conversion de la date de fin
    $dateFin = $_POST['codePromoDateFin'] ?? '';
    if ($dateFin) {
        $dateFin .= (strlen($dateFin) === 10) ? ' 23:59:59' : '';
        $dateTime = new DateTime($dateFin);
        $dateTime->setTimezone(new DateTimeZone('UTC'));
        $utcTimeDateFin = $dateTime->format('Y-m-d H:i:s');
        log_message("Date de fin UTC : " . $utcTimeDateFin);
    } else {
        log_message("Date de fin non valide");
        echo 'Date de fin non valide';
        exit();
    }

    // Préparation de la requête SQL
    $bind = [
        'codePromo' => $_POST['codePromo'],
        'codePromoDescription' => $_POST['codePromoDescription'],
        'codePromoValeur' => $_POST['codePromoValeur'],
        'codePromoDateDebut' => $utcTimeDateDebut,
        'codePromoDateFin' => $utcTimeDateFin,
        'freeShipping' => $freeShipping
    ];
    log_message("Bind des données : " . print_r($bind, true));

    if ($codePromoId == 0) {
        if (!$codePromoExist) {
            $sql = "INSERT INTO code_promo 
                    (codePromo, codePromoDescription, codePromoValeur, codePromoDateDebut, codePromoDateFin, freeShipping) 
                    VALUES (:codePromo, :codePromoDescription, :codePromoValeur, :codePromoDateDebut, :codePromoDateFin, :freeShipping)";
            log_message("Requête préparée pour insertion");
        } else {
            log_message("Erreur : Code promo existe déjà.");
            echo 'Code promo du même nom existe déjà.';
            exit();
        }
    } else {
        $sql = "UPDATE code_promo 
                SET codePromo = :codePromo, 
                    codePromoDescription = :codePromoDescription, 
                    codePromoValeur = :codePromoValeur, 
                    codePromoDateDebut = :codePromoDateDebut, 
                    codePromoDateFin = :codePromoDateFin, 
                    freeShipping = :freeShipping 
                WHERE codePromoId = :codePromoId";
        $bind['codePromoId'] = $codePromoId;
        log_message("Requête préparée pour mise à jour");
    }

    // Exécution de la requête
    $request = $db->prepare($sql);
    $request->execute($bind);
    log_message("Requête SQL exécutée avec succès");

    // Redirection après succès
    header('Location: ./code_promos.php');
    log_message("Redirection vers code_promos.php");
    exit();
} catch (Exception $e) {
    log_message("Erreur générale : " . $e->getMessage());
    echo 'Une erreur est survenue : ' . $e->getMessage();
}
?>
