<?php
session_start();
$css = "code_promos";
$title = "Gestion des codes promos";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

$order_by = 'codePromoId';  // Par défaut, trier par 'codePromoId'
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


$sql = "SELECT codePromoId, codePromo, codePromoValeur, DATE_FORMAT(codePromoDateDebut, '%d/%m/%y %H:%i') as DateDebut, DATE_FORMAT(codePromoDateFin, '%d/%m/%y %H:%i') as DateFin
FROM code_promo WHERE DATEDIFF(NOW(), codePromoDateFin)<0";
if (!$remove_order) {
    $sql .= " ORDER BY {$order_by} {$order_direction}";
}
$request = $db->prepare($sql);
$request->execute();
$codePromos = $request->fetchAll();
$sql = "SELECT codePromoId, codePromo, codePromoValeur, DATE_FORMAT(codePromoDateDebut, '%d/%m/%y %H:%i') as DateDebut, DATE_FORMAT(codePromoDateFin, '%d/%m/%y %H:%i') as DateFin
FROM code_promo WHERE DATEDIFF(NOW(), codePromoDateFin)>0";
if (!$remove_order) {
    $sql .= " ORDER BY {$order_by} {$order_direction}";
}

$request = $db->prepare($sql);
$request->execute();
$codePromosExpire = $request->fetchAll();


?>


<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li aria-current="page">Code promos</li>
        </ol>
    </nav>
    <a href="./addPromoCode.php"><button class="button-add">Ajouter un code promo</button></a>
    <h2>Code promos valides ou bientôt valide</h2>
    <table>

        <thead>
            <tr>
                <th><a href="?orderby=codePromoId">Id <?php echo ($order_by === 'codePromoId' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=codePromo">Code promo <?php echo ($order_by === 'codePromo' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=codePromoValeur">Valeur en % <?php echo ($order_by === 'codePromoValeur' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=codePromoDateDebut"> Date de début <?php echo ($order_by === 'codePromoDateDebut' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th><a href="?orderby=codePromoDateFin"> Date de fin <?php echo ($order_by === 'codePromoDateFin' && !$remove_order) ? ($order_direction === 'ASC' ? '<span class="asc"></span>' : '<span class="desc"></span>') : ''; ?></a></th>
                <th>Modifier</th>
            </tr>
        </thead>
        <?php
        foreach ($codePromos as $codePromo) { ?>
            <tr>
                <td><?= $codePromo['codePromoId'] ?></td>
                <td><?= $codePromo['codePromo'] ?></td>
                <td><?= $codePromo['codePromoValeur'] ?></td>
                <td><?= $codePromo['DateDebut'] ?></td>
                <td><?= $codePromo['DateFin'] ?></td>
                <td><a href="/pages/admin/crud/modifycodepromo.php?codePromoId=<?= $codePromo['codePromoId'] ?>"><img src="/assets/img/crayon.png" alt="pencil icon" class="eye-icon"></a></td>
            </tr>
    
            <?php } ?></table>
            <h2>Code promos expiré</h2>
<table>
    <?php foreach ($codePromosExpire as $codePromoExpire) { ?>
        <tr class="expire">
            <td><?= $codePromoExpire['codePromoId'] ?></td>
            <td><?= $codePromoExpire['codePromo'] ?></td>
            <td><?= $codePromoExpire['codePromoValeur'] ?></td>
            <td><?= $codePromoExpire['DateDebut'] ?></td>
            <td><?= $codePromoExpire['DateFin'] ?></td>
            <td><a href="/pages/admin/crud/modifycodepromo.php?codePromoId=<?= $codePromoExpire['codePromoId'] ?>"><img src="/assets/img/crayon.png" alt="pencil icon" class="eye-icon"></a></td>
        </tr>
        <?php } ?>
    </table>

</main>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>