<?php
session_start();

require_once '../bdd/connect.php';
require_once '../variables.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$sql = 'SELECT * FROM commandes ORDER BY commandeId DESC LIMIT 1';
$request = $db->prepare($sql);
$request->execute();
$lastCommand = $request->fetch();
$lastCommandId = $lastCommand['commandeId'];

// Récupérer les informations sur la commande pour l'email de l'administrateur
$stmtCommande = $db->prepare("SELECT * FROM commandes WHERE commandeId = ?");
$stmtCommande->execute([$lastCommandId]);
$commande = $stmtCommande->fetch();
$adresseId = $commande['adresseId'];

// Récupérer les informations du client pour l'email de l'admin
$stmtClient = $db->prepare("SELECT clientNom, clientAdresseMail, clientAdresseLivraison, clientTelephone FROM clients WHERE clientId = ?");
$stmtClient->execute([$lastCommand['clientId']]);
$client = $stmtClient->fetch();
$clientNom = $client['clientNom'];
$clientPrenom = explode(' ', trim($clientNom))[0];
$clientAdresse = $client['clientAdresseLivraison'];
$clientEmail = $client['clientAdresseMail'];
$clientTelephone = !empty($client['clientTelephone']) ? $client['clientTelephone'] : 'Not provided';
$sousTotal = $lastCommand['commandeTotal'] - $lastCommand['commandeLivraisonPrix']; // Sous-total

try {
    foreach ($_SESSION['panier'] as $article) {
        if ($article['libelle'] !== 'Shipping fees') {
            $variantId = $article['variantId'];
            $quantite = $article['quantite'];
            $prix = $article['prix'];

            $stmtDetailCommande = $db->prepare("INSERT INTO detail_commande (commandeId, variantId, commandeProduitQuantite, commandeProduitPrix) VALUES (?, ?, ?, ?)");
            $stmtDetailCommande->execute([$lastCommandId, $variantId, $quantite, $prix]);
        }
    }
    
    // Ajouter les frais de port à la commande
    foreach ($_SESSION['panier'] as $article) {
        if ($article['libelle'] === 'Shipping fees') {
            $commandeLivraisonPays = $article['format'];
            $commandeLivraisonPrix = $article['prix'];
            $stmtCommande = $db->prepare("UPDATE commandes SET commandeLivraisonPays = ?, commandeLivraisonPrix = ? WHERE commandeId = ?");
            $stmtCommande->execute([$commandeLivraisonPays, $commandeLivraisonPrix, $lastCommandId]);
        }
    }

    // Récupérer les détails de la commande pour l'email
    $sqlCommandeDetails = "SELECT 
        dc.*, 
        vp.*, 
        p.*, 
        c.*, 
        cli.*, 
        ph.photoUrl
    FROM 
        detail_commande dc
    INNER JOIN 
        variant_produit vp ON vp.variantId = dc.variantId
    INNER JOIN 
        produits p ON vp.produitId = p.produitId
    INNER JOIN 
        commandes c ON c.commandeId = dc.commandeId
    INNER JOIN 
        clients cli ON cli.clientId = c.clientId
    INNER JOIN 
        photo ph ON ph.produitId = p.produitId
    WHERE 
        dc.commandeId = :commandeId
    AND 
        ph.photoId = (
            SELECT MIN(ph_inner.photoId) 
            FROM photo ph_inner 
            WHERE ph_inner.produitId = p.produitId
        )";
    $request = $db->prepare($sqlCommandeDetails);
    $request->execute([":commandeId" => $lastCommandId]);
    $commandeDetails = $request->fetchAll();

    // Construire le tableau des articles commandés
    $itemsHtml = '<table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Product</th>
                <th>Format</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($commandeDetails as $detail) {
        $itemsHtml .= '<tr>
            <td>
                <img src="' . $site. htmlspecialchars($detail['photoUrl']) . '" alt="' . htmlspecialchars($detail['produitLibelle']) . '" style="width: 100px; height: auto;">
            </td>
            <td>' . htmlspecialchars($detail['produitLibelle']) . '</td>
            <td>' . htmlspecialchars($detail['variantFormat']) . '</td>
            <td>' . htmlspecialchars($detail['commandeProduitQuantite']) . '</td>
        </tr>';
    }

    $itemsHtml .= '</tbody></table>';

    // Récupérer les informations du client pour l'email
    $stmtClient = $db->prepare("SELECT clientNom, clientAdresseMail FROM clients WHERE clientId = ?");
    $stmtClient->execute([$lastCommand['clientId']]);
    $client = $stmtClient->fetch();
    $clientPrenom = explode(' ', trim($client['clientNom']))[0];

    // Envoi de l'email de confirmation au client
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'theo.kreichershop@gmail.com';
        $mail->Password = 'rnmxdybodhfmhiey';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('theo.kreichershop@gmail.com', 'Theo Kreicher - Shop');
        $mail->addAddress($client['clientAdresseMail'], $client['clientNom']);

        $mail->isHTML(true);
        $mail->Subject = 'Order confirmed';

        $mail->Body = '
        <html>
        <head>
        <style>
            @font-face {
                font-family: "Lato";
                src: url("' . $site . '/assets/css/font/Lato-Regular.ttf");
            }
            body, table, td {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                font-family: "Lato", sans-serif;
            }
            .background {
                background-image: url("' . $site . '/assets/img/thankyoucard.png");
                background-size: cover;
                background-repeat: no-repeat;
                width: 539px;
                height: 567px;
                text-align: center;
            }
            .content {
                padding-top: 260px; /* Adjust as necessary to position text correctly */
            }
            .content p {
                font-size: 21px;
                margin: 0;
                padding: 0;
            }
            .order-details {
                margin-top: 20px;
                width: 100%;
                text-align: center;
                color: #5F9EA0; /* Cadet blue color */
            }
            .order-details table {
                margin: 0 auto;
                border-collapse: collapse;
                width: 80%;
            }
            .order-details th, .order-details td {
                border: 1px solid #ddd;
                padding: 8px;
                width:20%;
            }
            .order-details th {
                background-color: #f2f2f2;
            }
        </style>
        </head>
        <body>
            <table role="presentation" width="100%" height="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">
                        <div class="background">
                            <div class="content">
                                <p>Hi ' . htmlspecialchars($clientPrenom) . ',</p>
                            </div>
                        </div>
                        <div class="order-details">
                            <p>Here are the details:</p>
                            ' . $itemsHtml . '
                        </div>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ';

        $mail->send();
        error_log('Le message de confirmation a été envoyé à ' . $client['clientAdresseMail']);
    } catch (Exception $e) {
        error_log("Le message n'a pas pu être envoyé. Erreur de Mailer: {$mail->ErrorInfo}");
    }

    // Envoyer un email à l'administrateur
try {
    $adminMail = clone $mail; // Cloner l'instance de PHPMailer
    $adminMail->clearAllRecipients(); // Supprimer les destinataires précédents
    $adminMail->addAddress('theo.kreichershop@gmail.com'); // Votre adresse email

    $adminMail->Subject = 'Nouvelle commande reçue - ID: ' . $lastCommandId;
    $stmtAdresse = $db->prepare("SELECT adresseLivraison, codePostal, ville, pays FROM adresses WHERE adresseId = ?");
$stmtAdresse->execute([$adresseId]);
$adresseDetails = $stmtAdresse->fetch();

// Inclure les informations d'adresse dans l'email
$clientAdresseComplete = htmlspecialchars($adresseDetails['adresseLivraison']) . ', ' . htmlspecialchars($adresseDetails['codePostal']) . ' ' . htmlspecialchars($adresseDetails['ville']) . ', ' . htmlspecialchars($adresseDetails['pays']);
    $adminMail->Body = '
<html>
<head>
<style>
    @font-face {
        font-family: "Lato";
        src: url("' . $site . '/assets/css/font/Lato-Regular.ttf");
    }
    body, table, td {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        font-family: "Lato", sans-serif;
    }
    .order-details {
        margin-top: 20px;
        width: 100%;
        text-align: center;
        color: #5F9EA0; /* Cadet blue color */
    }
    .order-details table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%;
    }
    .order-details th, .order-details td {
        border: 1px solid #ddd;
        padding: 8px;
        width:20%;
    }
    .order-details th {
        background-color: #f2f2f2;
    }
</style>
</head>
<body>
    <div class="order-details">
        <h2>New Order Details</h2>
        <p><strong>Order ID:</strong> ' . $lastCommandId . '</p>
        <p><strong>Client Name:</strong> ' . htmlspecialchars($clientNom) . '</p>
        <p><strong>Client Address:</strong> ' . $clientAdresseComplete . '</p>
        <p><strong>Client Email:</strong> ' . htmlspecialchars($clientEmail) . '</p>
        <p><strong>Client Phone:</strong> ' . htmlspecialchars($clientTelephone) . '</p>
        <p><strong>Subtotal:</strong> ' . number_format($sousTotal, 2) . ' €</p>
        <p><strong>Shipping Cost:</strong> ' . number_format($lastCommand['commandeLivraisonPrix'], 2) . ' €</p>
        <p><strong>Total:</strong> ' . number_format($lastCommand['commandeTotal'], 2) . ' €</p>

        <h3>Order Items:</h3>
        ' . $itemsHtml . '
    </div>
</body>
</html>
';

    $adminMail->send();
    error_log('Le message de notification de commande a été envoyé à theo.kreichershop@gmail.com');
} catch (Exception $e) {
    error_log("Le message n\'a pas pu être envoyé à l\'administrateur. Erreur de Mailer: {$adminMail->ErrorInfo}");
}

    $_SESSION['panier'] = [];
} catch (\Exception $e) {
    error_log("Erreur lors des enregistrements de détails de commandes " . $e->getMessage() . "\n", 3, "../logs.log");
}

header('Location: ./success.php');
exit;