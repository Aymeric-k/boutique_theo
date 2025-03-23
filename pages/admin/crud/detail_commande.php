<?php
session_start();
$css = "detail_commandes";
$title = "Details de la commande";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    $text = preg_replace('~[^-\w]+~', '', $text);

    $text = trim($text, '-');

    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

if (isset($_POST['statut'])) {
    $sqlEtat = "UPDATE commandes SET commandeEtat = :statut WHERE commandeId = :commandeId";
    $stmt = $db->prepare($sqlEtat);
    $stmt->execute(["commandeId" => $_GET['commandeId'], ":statut" => $_POST['statut']]);
}

$sql = "SELECT * FROM detail_commande 
        INNER JOIN variant_produit ON variant_produit.variantId = detail_commande.variantId 
        INNER JOIN produits ON variant_produit.produitId = produits.produitId 
        INNER JOIN commandes ON commandes.commandeId = detail_commande.commandeId 
        INNER JOIN clients ON clients.clientId = commandes.clientId 
        WHERE detail_commande.commandeId = :commandeId";
$request = $db->prepare($sql);
$request->execute([":commandeId" => $_GET['commandeId']]);
$commandeId = $request->fetchAll();
?>

<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li><a href="/pages/admin/crud/commandes.php">Commandes</a></li>
            <li aria-current="page">Commande n°<?=$_GET['commandeId']?> </li>
        </ol>
    </nav>
    <h1>Détails de la commande n° <?= $_GET['commandeId'] ?></h1>
    <section>
        <h2>Client </h2>
        <div class="info-client-adresse">
            <p>
                Nom : <?= $commandeId[0]['clientNom'] ?>
            </p>
            <p>
                Adresse : <?= $commandeId[0]['clientAdresseLivraison'] ?>
            </p>
        </div>
        <div class="info-client-mail">
            <p>
                Mail : <?= $commandeId[0]['clientAdresseMail'] ?>
            </p>
            <p>
                <?php if ($commandeId[0]['clientTelephone'] != null) {
                    echo ' <p>Télèphone :' .  $commandeId[0]['clientTelephone'] . '</p>';
                } else {
                    echo '<p>Télèphone : Non renseigné';
                }
                ?>
            </p>
        </div>
        <div class="info-client-livraison">
            <p>Pays choisi : <?=$commandeId[0]['commandeLivraisonPays']?></p>
            <p>Prix : <?=$commandeId[0]['commandeLivraisonPrix']?> €</p>
        </div>
    </section>
    <section>
        <table>
            <thead>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        Nom
                    </th>
                    <th>
                        Format
                    </th>
                    <th>
                        Quantité
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($commandeId as $commande) {
                    $slug = slugify($commande['produitLibelle']);
                    echo '<tr> <td><a href="/pages/article.php?name=' . $slug . '&id=' . $commande['produitId'] . '" target="_blank">' . $commande['variantId'] . '</a></td>
                    <td>' . $commande['produitLibelle'] . '</td>
                    <td>' . $commande['variantFormat'] . '</td>
                    <td>' . $commande['commandeProduitQuantite'] . '</td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
        <form action="detail_commande.php?commandeId=<?= $_GET['commandeId'] ?>" method="post">
            <label for="statut">Statut de la commande</label>
            <select name="statut" id="statut">
                <option value="en attente" <?php if ($commandeId[0]['commandeEtat'] == 'en attente') echo 'selected'; ?>>En attente</option>
                <option value="envoyé" <?php if ($commandeId[0]['commandeEtat'] == 'envoyé') echo 'selected'; ?>>Envoyé</option>
                <option value="mail envoyé" <?php if ($commandeId[0]['commandeEtat'] == 'mail envoyé') echo 'selected'; ?>>Mail envoyé</option>
                <option value="refusé" <?php if ($commandeId[0]['commandeEtat'] == 'refusé') echo 'selected'; ?>>Refusé</option>
                <option value="problème pays" <?php if ($commandeId[0]['commandeEtat'] == 'problème pays') echo 'selected'; ?>>Problème pays</option>
            </select>
            <button>Envoyer</button>
        </form>
    </section>
</main>
