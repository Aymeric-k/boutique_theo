<?php
session_start();
$css = "code_promos";
$title = "Ajout de code promos";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}


?>
<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li> <a href="/pages/admin/crud/code_promos.php">Code promo</a> </li>
            <li aria-current="page">Ajout de code promo</li>
        </ol>
    </nav>

    <form action="codepromoChange" method="POST">
        <label for="codePromo">Nom du code promo :</label>
        <input type="text" name="codePromo" id="codePromo" required>

        <label for="codePromoDescription">Description du produit</label>
        <textarea name="codePromoDescription" id="codePromoDescription" required></textarea>

        <label for="codePromoValeur">Valeur en % du code promo</label>
        <input type="number" name="codePromoValeur">

        <label for="codePromoDateDebut">Date et heure du début de validité du code promo</label>
        <input type="datetime-local" name="codePromoDateDebut">

        <label for="codePromoDateFin">Date et heure de fin de validité du code promo</label>
        <input type="datetime-local" name="codePromoDateFin">
        
        <label for="freeShipping">Offrir les frais de livraison :</label>
    <input type="checkbox" name="freeShipping" id="freeShipping" value="1">

        <button>Ajouter le code promo</button>
    </form>

</main>










<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>