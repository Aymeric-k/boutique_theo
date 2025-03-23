<?php
session_start();
$css = "categories";
$title = "Gestion des catégories";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

$categoryId =0
?>

<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li> <a href="/pages/admin/crud/categories.php">Categories</a> </li>
            <li aria-current="page">Ajout de catégorie</li>
        </ol>
    </nav>

    <form action="categoriesChange" method="POST">
        <label for="categorieLibelle">Nom de la catégorie</label>
        <input type="text" name="categorieLibelle" required>

        <button>Ajouter la catégorie</button>
    </form>

</main>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>