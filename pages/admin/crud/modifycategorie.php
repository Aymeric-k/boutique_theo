<?php
session_start();
$css = "categories";
$title = "Modification des catégories";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

$sql = "SELECT * FROM categorie WHERE categorieId = :categorieId";
$request = $db->prepare($sql);
$request->execute(['categorieId'=>$_GET['categorieId']]);
$categorie = $request->fetch();
?>

<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li> <a href="/pages/admin/crud/categories.php">Categories</a> </li>
            <li aria-current="page"></li>
        </ol>
    </nav>

    <form action="categoriesChange" method="POST">
        <label for="categorieLibelle">Nom de la catégorie</label>
        <input type="text" name="categorieLibelle" value ="<?=$categorie['categorieLibelle']?>" required>
        <input type="hidden" value="<?=$categorie['categorieId']?>" name="categorieId">
        <button>Ajouter la catégorie</button>
    </form>



</main>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>