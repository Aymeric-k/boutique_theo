<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

// Fonction de log
function log_message($message) {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/logs.log'; // Chemin du fichier de logs
    error_log($message . "\n", 3, $logFile);
}

// Log le début du traitement
log_message("Début du traitement du fichier");
log_message("Nouveau produit ajouté avec les valeurs: " . json_encode($_POST));

// Validation des champs essentiels
if (!isset($_POST['produitLibelle']) || empty($_POST['produitLibelle'])) {
    log_message("Erreur: Le champ produitLibelle est vide.");
    die("Le nom du produit est requis.");
}

if (!isset($_POST['produitEnAvant'])) {
    log_message("Erreur: Le champ produitEnAvant est vide.");
    die("Le statut produitEnAvant est requis.");
}

// Récupération des valeurs POST
$produitLibelle = $_POST['produitLibelle'];
$produitDescription = $_POST['produitDescription'];
$produitEnAvant = $_POST['produitEnAvant'];
$produitEnAvantOrdre = isset($_POST['produitEnAvantOrdre']) ? $_POST['produitEnAvantOrdre'] : null;
$categorieId = $_POST['category'];

// Log pour vérifier que les valeurs existent
log_message("Produit libellé: $produitLibelle, Description: $produitDescription, En Avant: $produitEnAvant, Ordre: $produitEnAvantOrdre, Catégorie ID: $categorieId");

try {
    $produitId = $_POST['produitId']; // Si produitId = 0, c'est un nouvel ajout
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/assets/productPics/";

    // Log de l'upload des photos
log_message("Gestion des fichiers photos");

// Vérification si des fichiers sont présents dans $_FILES
if (isset($_FILES['photos'])) {
    log_message("Fichiers reçus : " . json_encode($_FILES['photos'])); // Log de l'ensemble des fichiers reçus
    
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
        // Log des informations spécifiques à chaque fichier
        log_message("Traitement du fichier index: $key");
        log_message("Nom temporaire du fichier: " . $_FILES['photos']['tmp_name'][$key]);
        log_message("Nom original du fichier: " . $_FILES['photos']['name'][$key]);
        log_message("Taille du fichier: " . $_FILES['photos']['size'][$key]);
        log_message("Erreur lors du téléchargement (s'il y en a une): " . $_FILES['photos']['error'][$key]);

        // Vérification s'il n'y a pas d'erreur avec le fichier
        if ($_FILES['photos']['error'][$key] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['photos']['name'][$key];
            $destinationPath = $uploadDir . $file_name;
            
            // Vérification si le fichier n'existe pas déjà
            if (!file_exists($destinationPath)) {
                // Tentative de déplacer le fichier dans le répertoire
                if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $destinationPath)) {
                    log_message("Fichier téléchargé avec succès: " . $file_name);
                } else {
                    log_message("Erreur lors du déplacement du fichier: " . $file_name);
                }
            } else {
                log_message("Le fichier existe déjà : " . $destinationPath);
            }
        } else {
            // Log des erreurs si le téléchargement du fichier a échoué
            log_message("Erreur lors du téléchargement du fichier index $key : " . $_FILES['photos']['error'][$key]);
        }
    }
} else {
    log_message("Aucun fichier reçu dans \$_FILES.");
}

    // Fonction pour ajuster les priorités des produits en avant
    function adjustPriorities($db, $newPriority, $currentProductId) {
        log_message("Ajustement des priorités des produits");
        $stmt = $db->prepare("SELECT produitId, produitEnAvantOrdre FROM produits WHERE produitEnAvant = 1 AND produitId != ? ORDER BY produitEnAvantOrdre");
        $stmt->execute([$currentProductId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $updatedProducts = [];
        foreach ($products as $product) {
            if ($product['produitEnAvantOrdre'] >= $newPriority) {
                $updatedProducts[] = $product['produitId'];
            }
        }

        foreach ($updatedProducts as $index => $id) {
            $newOrder = $newPriority + $index + 1;
            if ($newOrder > 8) {
                $stmt = $db->prepare("UPDATE produits SET produitEnAvantOrdre = NULL, produitEnAvant = 0 WHERE produitId = ?");
                $stmt->execute([$id]);
            } else {
                $stmt = $db->prepare("UPDATE produits SET produitEnAvantOrdre = ? WHERE produitId = ?");
                $stmt->execute([$newOrder, $id]);
            }
        }
    }

    // Log de la gestion du produit
    log_message("Gestion du produit ID: " . $produitId);

    // Cas mise à jour produit existant
    if ($produitId > 0) {
        $newPriority = $_POST['produitEnAvantOrdre'];
        if ($_POST['produitEnAvant'] == 1 && $newPriority >= 1 && $newPriority <= 8) {
            adjustPriorities($db, $newPriority, $produitId);
        }

        $sql = "UPDATE produits SET produitLibelle = :produitLibelle, produitDescription = :produitDescription, produitEnAvant = :produitEnAvant, produitEnAvantOrdre = :produitEnAvantOrdre, categorieId = :categorieId WHERE produitId = :produitId";
        $bind = [
            'produitLibelle' => $produitLibelle,
            'produitEnAvant' => $produitEnAvant,
            'produitDescription' => $produitDescription,
            'categorieId' => $categorieId,
            'produitEnAvantOrdre' => $produitEnAvantOrdre,
            'produitId' => $produitId
        ];
        log_message("Produit mis à jour avec les valeurs: " . json_encode($bind));
         $request = $db->prepare($sql);
    $request->execute($bind);

    } else {
        // Insertion nouveau produit
        $sql = "INSERT INTO produits(produitLibelle, produitDescription, produitEnAvant, produitEnAvantOrdre, categorieId) VALUES (:produitLibelle, :produitDescription, :produitEnAvant, :produitEnAvantOrdre, :categorieId)";
        $bind = [
            'produitLibelle' => $produitLibelle,
            'produitDescription' => $produitDescription,
            'produitEnAvant' => $produitEnAvant,
            'produitEnAvantOrdre' => $produitEnAvantOrdre,
            'categorieId' => $categorieId
        ];

        $request = $db->prepare($sql);
        $request->execute($bind);
        $produitId = $db->lastInsertId(); // Récupère l'ID du produit inséré
        log_message("Nouveau produit ajouté avec ID: " . $produitId);
    }

    // Gestion des variantes de produit
    log_message("Gestion des variantes du produit");

    foreach ($_POST['variantId'] as $i => $variant) {
        $variantId = (int)$variant;
        $bind = [
            'variantPrix' => $_POST['variantPrix'][$i],
            'variantFormat' => $_POST['variantFormat'][$i],
            'variantPoids' => $_POST['variantPoids'][$i],
            'variantStockDisponible' => $_POST['variantStockDisponible'][$i],
            'variantHorsStock' => $_POST['variantHorsStock'][$i]
        ];

        if ($variantId > 0) {
            $sql = "UPDATE variant_produit SET variantPrix = :variantPrix, variantFormat = :variantFormat , variantPoids = :variantPoids, variantStockDisponible = :variantStockDisponible, variantHorsStock = :variantHorsStock WHERE variantId = :variantId";
            $bind['variantId'] = $variantId;
        } else {
            $sql = "INSERT INTO variant_produit(variantPrix, variantFormat, variantPoids, variantStockDisponible, variantHorsStock, produitId) VALUES (:variantPrix, :variantFormat, :variantPoids, :variantStockDisponible, :variantHorsStock, :produitId)";
            $bind['produitId'] = $produitId;
        }

        $request = $db->prepare($sql);
        $request->execute($bind);
        log_message("Variante mise à jour ou ajoutée avec les valeurs: " . json_encode($bind));
    }

    // Gestion des photos du produit
    log_message("Gestion des photos du produit");

    foreach ($_POST['photoId'] as $i => $photoId) {
        $bind = [
            'photoUrl' => $_POST['photoUrl'][$i],
            'photoLegende' => $_POST['photoLegende'][$i],
            'photoOrdre' => $_POST['photoOrdre'][$i]
        ];

        if ((int)$photoId > 0) {
            $sql = "UPDATE photo SET photoUrl = :photoUrl, photoLegende = :photoLegende , photoOrdre = :photoOrdre WHERE photoId = :photoId";
            $bind['photoId'] = $photoId;
        } else {
            $sql = "INSERT INTO photo(photoUrl, photoLegende, photoOrdre, produitId) VALUES (:photoUrl, :photoLegende, :photoOrdre, :produitId)";
            $bind['produitId'] = $produitId;
        }

        $request = $db->prepare($sql);
        $request->execute($bind);
        log_message("Photo mise à jour ou ajoutée avec les valeurs: " . json_encode($bind));
    }

    log_message("Fin du traitement du fichier, redirection");
    header('Location: ./produits');

} catch (Exception $e) {
    log_message("Erreur rencontrée: " . $e->getMessage());
    echo "Une erreur s'est produite. Veuillez vérifier les logs.";
}