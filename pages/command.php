<?php
session_start();
require_once '../bdd/connect.php';
require '../vendor/autoload.php';
include '../variables.php';

\Stripe\Stripe::setApiKey($stripeApiKey);

// Définir le chemin du fichier de logs
$logFile = __DIR__ . '/../logs.log';

// Fonction pour enregistrer les logs
function log_message($message)
{
    global $logFile;
    error_log($message . "\n", 3, $logFile);
}

try {
    $poidsTotal = 0;
    $paysDestination = $_POST['country'];
    log_message("Pays destination (ID): " . $paysDestination);

    // Tarifs pour la livraison
    $tarifsFranceStandard = [20 => 179, 100 => 308, 250 => 480, 500 => 680, 1000 => 820, 2000 => 979];
    $tarifsFranceRugs = [250 => 499, 500 => 699, 750 => 810, 1000 => 880, 2000 => 1015];
    $tarifsEuropeStandard = [20 => 476, 100 => 695, 250 => 1265, 500 => 1735, 1000 => 2930];
    $tarifsEuropeRugs = [250 => 790, 500 => 925, 1000 => 1100, 2000 => 1565];

    // Fonction pour calculer les frais de port
    function calculerFraisDePortSelonPoids($panier, $paysDestination)
    {
        global $tarifsFranceStandard, $tarifsFranceRugs, $tarifsEuropeStandard, $tarifsEuropeRugs;

        $poidsTotal = 0;
        $tarifSpecialApplique = false;

        foreach ($panier as $item) {
            $poidsTotal += $item['poids'] * $item['quantite'];
            if ($item['category'] === 'Rugs') {
                $tarifSpecialApplique = true;
            }
        }

        if ((int)$paysDestination === 1) {
            $tarifs = $tarifSpecialApplique ? $tarifsFranceRugs : $tarifsFranceStandard;
            log_message("Grille tarifaire France sélectionnée");
        } else {
            $tarifs = $tarifSpecialApplique ? $tarifsEuropeRugs : $tarifsEuropeStandard;
            log_message("Grille tarifaire Europe sélectionnée");
        }

        foreach ($tarifs as $poidsMax => $cout) {
            if ($poidsTotal <= $poidsMax) {
                log_message("Frais de port calculé : " . $cout . " pour un poids maximum de " . $poidsMax);
                return $cout;
            }
        }
        $lastTarif = end($tarifs);
        log_message("Frais de port maximal appliqué : " . $lastTarif);
        return $lastTarif;
    }

    // Initialisation des variables
    $fraisDePort = calculerFraisDePortSelonPoids($_SESSION['panier'], $paysDestination);
    $reduction = 0;

    // Vérification du code promo
    if (isset($_POST['promoCode']) && !empty($_POST['promoCode'])) {
        log_message("Code promo soumis : " . $_POST['promoCode']);
        $sql = "SELECT * FROM code_promo WHERE codePromo = :codePromo AND NOW() BETWEEN codePromoDateDebut AND codePromoDateFin";
        $request = $db->prepare($sql);
        $request->execute(['codePromo' => $_POST['promoCode']]);
        $codePromo = $request->fetch();

        if ($codePromo) {
            log_message("Code promo valide trouvé : " . print_r($codePromo, true));
            
            // Appliquer la réduction
            $reduction = (float)$codePromo['codePromoValeur'] / 100;
            log_message("Réduction appliquée : " . ($reduction * 100) . "%");

            // Offrir les frais de livraison si freeShipping = 1
            if ($codePromo['freeShipping'] == 1) {
                $fraisDePort = 0;
                log_message("Le code promo offre les frais de livraison. Frais de port = 0.");
            }
        } else {
            log_message("Code promo invalide ou expiré.");
        }
    }

    // Calcul du total avec réduction
    $total = 0;
    foreach ($_SESSION['panier'] as &$item) {
        $item['prix'] -= $item['prix'] * $reduction; // Appliquer la réduction sur chaque article
        $total += $item['prix'] * $item['quantite'];
    }
    unset($item); // Nettoyer la référence

    // Ajouter un tarif fixe de 200 centimes si les frais de port ne sont pas offerts
    if ($fraisDePort > 0) {
        $fraisDePort += 200;
        log_message("Frais fixes ajoutés aux frais de port : 200 centimes.");
    }

    // Ajouter les frais de port au panier
    $sql = 'SELECT paysLibelle FROM pays WHERE pays.paysId = :pays';
    $request = $db->prepare($sql);
    $request->execute(['pays' => $paysDestination]);
    $pays = $request->fetch();

    $_SESSION['panier'][] = [
        'libelle' => 'Shipping fees',
        'format' => $pays['paysLibelle'],
        'photoUrl' => '',
        'prix' => $fraisDePort / 100, // En euros
        'quantite' => 1,
        'poids' => 0,
        'category' => 'Shipping'
    ];

    // Préparer les items pour Stripe
    $line_items = array_map(function ($item) use ($site) {
        $imageUrl = isset($item['photoUrl']) ? $site . $item['photoUrl'] : '';

        return [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['libelle'],
                    'description' => 'Format: ' . $item['format'],
                    'images' => $imageUrl ? [$imageUrl] : [],
                ],
                'unit_amount' => $item['prix'] * 100,
            ],
            'quantity' => $item['quantite'],
        ];
    }, $_SESSION['panier']);

    // Création de la session Stripe Checkout
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => $successUrl,
        'cancel_url' => $site,
        'metadata' => ['paysDestination' => $paysDestination],
    ]);

    log_message("Session Stripe créée avec succès. ID : " . $session->id);
    echo json_encode(['id' => $session->id]);
} catch (\Exception $e) {
    log_message('Erreur générale: ' . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>
