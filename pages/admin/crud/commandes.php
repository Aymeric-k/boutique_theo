<?php
session_start();
$css = "commandes";
$title = "Gestion des codes promos";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}
$order_by = 'commandeId';  // Par défaut, trier par 'produitId'
$order_direction = 'ASC';  // Direction par défaut
$remove_order = false;     // Flag pour supprimer l'ordre de tri
if (isset($_GET['orderby'])) {
    $order_by = $_GET['orderby'];

    // Vérifiez si la direction est déjà définie en session pour cette colonne
    if (isset($_SESSION['orderby'][$order_by])) {
        $order_direction = $_SESSION['orderby'][$order_by];

        if ($order_direction == 'ASC') {
            $order_direction = 'DESC';
        } elseif ($order_direction == 'DESC') {
            $remove_order = true; // Supprimer le tri pour le prochain clic
        }
    }

    // Sauvegardez la direction dans la session pour s'en souvenir
    if ($remove_order) {
        unset($_SESSION['orderby'][$order_by]);  // Supprime le tri pour cette colonne
    } else {
        $_SESSION['orderby'][$order_by] = $order_direction; // Mémorise la direction
    }
}
$sql = "SELECT commandes.commandeId, commandes.commandeDate, commandes.commandeEtat
        FROM commandes 
        JOIN detail_commande ON commandes.commandeId = detail_commande.commandeId 
        GROUP BY commandes.commandeId, commandes.commandeDate";
if (!$remove_order) {
    $sql .= " ORDER BY {$order_by} {$order_direction}";
}

$request = $db->prepare($sql);
$request->execute();
$commandes = $request->fetchAll();


?>


<main>
<nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li aria-current="page">Commandes</li>
        </ol>
    </nav>
    <section>
        <h1>Commandes</h1>
        <table>
            <thead>
                <tr>
                    <th><a href="?orderby=commandeId">Id</a></th>
                    <th> <a href="?orderby=commandeDate">Date</a></th>
                    <th>Poids</th>
                    <th> <a href="?orderby=commandeEtat">Etat</a></th>
                    <th>Lien</th>
                </tr>
            </thead>

        </table>
        <ul>
            <?php
            foreach ($commandes as $commande) {
                $parts = explode(" ", $commande['commandeDate']);

                $sqlPoids = "SELECT SUM(variantPoids) as poids FROM variant_produit JOIN detail_commande ON variant_produit.variantId = detail_commande.variantId JOIN commandes ON commandes.commandeId = detail_commande.commandeId WHERE detail_commande.commandeId = :commandeId";
                $request = $db->prepare($sqlPoids);
                $request->execute([":commandeId" => $commande['commandeId']]);
                $commandePoids = $request->fetch();


                $date = $parts[0];

                $time = $parts[1];
                echo '<li id ="' . $commande['commandeId'] . '"> <p><span class="bold">' . $commande['commandeId'] . '</span> </p>
            <p><span class="bold">' . $date . '</span> à <span class="bold">' . $time . '</span></p>  
            <p> <span class="bold">' . $commandePoids['poids'] . ' </span> kgs </p>
            <p>  <span class="bold">' . $commande['commandeEtat'] . ' </span></p>
            <a href="detail_commande.php?commandeId=' . $commande['commandeId'] . '">Details </a></li>';
            } ?>
        </ul>
    </section>

</main>