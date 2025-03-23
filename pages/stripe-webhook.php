<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';
include '../variables.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

\Stripe\Stripe::setApiKey($stripeApiKey);

$payload = file_get_contents('php://input');
$data = json_decode($payload, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Erreur de décodage JSON: " . json_last_error_msg() . "\nPayload brut: " . htmlspecialchars($payload) . "\n", 3, "../logs.log");
    exit();
}

function log_message($message) {
    error_log($message . "\n", 3, "../logs.log");
}

log_message("Début du traitement du webhook Stripe");

$event = null;
try {
    $event = \Stripe\Event::constructFrom(json_decode($payload, true));
    log_message("Événement Stripe construit avec succès : " . $event->type);
} catch (\UnexpectedValueException $e) {
    log_message("Erreur lors de la construction de l'événement Stripe: " . $e->getMessage());
    http_response_code(400);
    exit();
}

try {
    switch ($event->type) {
        case 'checkout.session.completed':
            date_default_timezone_set('Europe/Paris');
            $session = $event->data->object;

            log_message("Traitement de l'événement 'checkout.session.completed'");

            $sessionCreationTime = new DateTime();
            $sessionCreationTime->setTimestamp($session->created);
            $sessionCreationTime->setTimezone(new DateTimeZone('Europe/Paris'));
            $commandeDate = $sessionCreationTime->format('Y-m-d H:i:s');

            $commandeTotal = $session->amount_total / 100;
            $commandeLivraisonPrix = isset($session->total_details->amount_shipping) ? $session->total_details->amount_shipping / 100 : 0;
            $commandeEtat = 'En attente';

            log_message("Date de commande : $commandeDate, Total : $commandeTotal, Livraison : $commandeLivraisonPrix");

            if (isset($session->customer_details->email)) {
                $clientAdresseMail = $session->customer_details->email;
                log_message("Email du client : $clientAdresseMail");

                $stmtVerifClient = $db->prepare("SELECT clientId FROM clients WHERE clientAdresseMail = ?");
                $stmtVerifClient->execute([$clientAdresseMail]);
                $clientIdExist = $stmtVerifClient->fetchColumn();
                $customerDetails = $session->customer_details;
                $clientNom = $customerDetails->name;
                log_message("Nom du client : $clientNom");

                if ($clientIdExist) {
                    $clientId = $clientIdExist;
                    log_message("Client existant trouvé avec ID : $clientId");
                } else {
                    $clientAdresseLivraison = $customerDetails->address->line1 . ', ' . $customerDetails->address->postal_code . ' ' . $customerDetails->address->city . ', ' . $customerDetails->address->country;
                    $clientTelephone = $session->phone ?? '';
                    $clientPays = $customerDetails->address->country;

                    $stmtClient = $db->prepare("INSERT INTO clients (clientNom, clientAdresseLivraison, clientAdresseMail, clientTelephone, clientPaysLivraison) VALUES (?, ?, ?, ?, ?)");
                    $stmtClient->execute([$clientNom, $clientAdresseLivraison, $clientAdresseMail, $clientTelephone, $clientPays]);
                    $clientId = $db->lastInsertId();
                    log_message("Nouveau client inséré avec ID : $clientId");
                }

                $stmtCheckAdresse = $db->prepare("
                    SELECT adresseId 
                    FROM adresses 
                    WHERE clientId = ? 
                    AND adresseLivraison = ? 
                    AND codePostal = ? 
                    AND ville = ? 
                    AND pays = ?
                ");
                $stmtCheckAdresse->execute([
                    $clientId, 
                    $customerDetails->address->line1, 
                    $customerDetails->address->postal_code, 
                    $customerDetails->address->city, 
                    $customerDetails->address->country
                ]);
                $adresseExistante = $stmtCheckAdresse->fetchColumn();

                if ($adresseExistante) {
                    $adresseId = $adresseExistante;
                    log_message("Adresse existante trouvée avec ID : $adresseId");
                } else {
                    $stmtAdresse = $db->prepare("
                        INSERT INTO adresses (clientId, adresseLivraison, codePostal, ville, pays) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmtAdresse->execute([
                        $clientId, 
                        $customerDetails->address->line1, 
                        $customerDetails->address->postal_code, 
                        $customerDetails->address->city, 
                        $customerDetails->address->country
                    ]);
                    $adresseId = $db->lastInsertId();
                    log_message("Nouvelle adresse insérée avec ID : $adresseId");
                }

                try {
                    $stmtCommande = $db->prepare("
                        INSERT INTO commandes (commandeDate, commandeEtat, commandeTotal, commandeLivraisonPrix, clientId, adresseId) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmtCommande->execute([$commandeDate, $commandeEtat, $commandeTotal, $commandeLivraisonPrix, $clientId, $adresseId]);
                    $commandeId = $db->lastInsertId();
                    log_message("Commande insérée avec succès. ID de la commande : $commandeId");
                } catch (\PDOException $e) {
                    log_message("Erreur lors de l'insertion de la commande : " . $e->getMessage());
                    log_message("Détails de la commande : Date - $commandeDate, État - $commandeEtat, Total - $commandeTotal, Livraison - $commandeLivraisonPrix, Client ID - $clientId, Adresse ID - $adresseId");
                }
            }
            break;

        default:
            http_response_code(400);
            exit();
    }
} catch (\Exception $e) {
    log_message("Erreur lors du traitement de l'événement Stripe : " . $e->getMessage());
}

log_message("Fin du traitement du webhook Stripe");
http_response_code(200);