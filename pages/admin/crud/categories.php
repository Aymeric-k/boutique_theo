<?php
session_start();
$css = "produits";
$title = "Gestion des catégories";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index');
    exit;
}

$order_by = 'categorieId';  // Par défaut, trier par 'categorieId'
$order_direction = 'ASC';  // Direction par défaut
$remove_order = false;
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
c.categorieId, 
c.categorieLibelle, 
COUNT(p.produitId) AS productCount
FROM 
categorie AS c
LEFT JOIN 
produits AS p ON c.categorieId = p.categorieId
GROUP BY 
c.categorieId, c.categorieLibelle";
if (!$remove_order) {
    $sql .= " ORDER BY {$order_by} {$order_direction}";
}
$request = $db->prepare($sql);
$request->execute();
$categories = $request->fetchAll();
?>

<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard">Dashboard</a></li>
            <li aria-current="page">Categories</li>
        </ol>
    </nav>
    <a href="./addCategories"><button class="button-add">Ajouter une categorie</button></a>
    <table>

        <thead>
            <tr>
                <th><a href="?orderby=categorieId">Id <?php echo ($order_by === 'categorieId' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=categorieLibelle">Catégories <?php echo ($order_by === 'categorieLibelle' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=productCount">Nombre de produits <?php echo ($order_by === 'productCount' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th>Modifier</th>
            </tr>
        </thead>
        <?php
        foreach ($categories as $categorie) { ?>
            <tr>
                <td><?= $categorie['categorieId'] ?></td>
                <td><?= $categorie['categorieLibelle'] ?></td>
                <td><?= $categorie['productCount'] ?></td>
                <td><a href="/pages/admin/crud/modifycategorie.php?categorieId=<?= $categorie['categorieId'] ?>"><img src="/assets/img/crayon.png" alt="pencil icon" class="eye-icon"></a></td>
            </tr>
        <?php } ?>
    </table>
</main>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer';
?>