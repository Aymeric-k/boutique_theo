<?php
session_start();
$css = "produits";
$title = "Gestion des produits";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

$order_by = 'produitId';  // Par défaut, trier par 'produitId'
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
$sql = "SELECT
p.produitId,
p.produitLibelle,
c.categorieLibelle,
COUNT(DISTINCT vp.variantId) AS numVariants,
COUNT(DISTINCT ph.photoId) AS numPhotos
FROM 
produits p
LEFT JOIN 
categorie c ON p.categorieId = c.categorieId
LEFT JOIN 
variant_produit vp ON p.produitId = vp.produitId
LEFT JOIN 
photo ph ON p.produitId = ph.produitId
GROUP BY 
p.produitId,
p.produitLibelle,
c.categorieLibelle";
if (!$remove_order) {
    $sql .= " ORDER BY {$order_by} {$order_direction}";
}
$request = $db->prepare($sql);
$request->execute();
$products = $request->fetchAll();

?>

<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li aria-current="page">Produits</li>
        </ol>
    </nav>
    <a href="./addProducts"><button class="button-add">Ajouter un produit</button></a>
    <table>

        <thead>
            <tr>
                <th><a href="?orderby=produitId">Id <?php echo ($order_by === 'produitId' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=produitLibelle">Libelle <?php echo ($order_by === 'produitLibelle' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=categorieLibelle">Catégories <?php echo ($order_by === 'categorieLibelle' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=numVariants">Nombre de Variants <?php echo ($order_by === 'numVariants' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=numPhotos">Nombre de Photos <?php echo ($order_by === 'numPhotos' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th>Details</th>
            </tr>
        </thead>
        <?php
        foreach ($products as $product) { ?>
            <tr>
                <td><?= $product['produitId'] ?></td>
                <td><?= $product['produitLibelle'] ?></td>
                <td><?= $product['categorieLibelle'] ?></td>
                <td><?= $product['numVariants'] ?></td>
                <td><?= $product['numPhotos'] ?></td>
                <td><a href="/pages/admin/crud/detailsproduits.php?produitId=<?= $product['produitId'] ?>"><img src="/assets/img/eye.png" alt="eye icon" class="eye-icon"></a></td>
            </tr>
        <?php } ?>
    </table>
</main>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>