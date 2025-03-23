<?php
session_start();
$css = "detailsproduits";
$title = "Gestion des produits";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

$sql = "SELECT * FROM produits WHERE produits.produitId = :produitId";
$request = $db->prepare($sql);
$request->execute(['produitId' => $_GET['produitId']]);
$product = $request->fetch();
$produitEnAvant = $product['produitEnAvant'];
$sql = "SELECT * FROM variant_produit WHERE produitId = :produitId";
$request = $db->prepare($sql);
$request->execute(['produitId' => $_GET['produitId']]);
$variants = $request->fetchAll();
$sql = "SELECT * FROM photo WHERE produitId = :produitId";
$request = $db->prepare($sql);
$request->execute(['produitId' => $_GET['produitId']]);
$photos = $request->fetchAll();

$sql = "SELECT * FROM categorie";
$request = $db->prepare($sql);
$request->execute();
$categories = $request->fetchAll();


?>


<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/admin/dashboard.php">Dashboard</a></li>
            <li> <a href="/pages/admin/crud/produits.php">Produits</a> </li>
            <li aria-current="page"><?= $product['produitLibelle'] ?></li>
        </ol>
    </nav>
    <form action="productsChange" method="POST" enctype="multipart/form-data" id="admin-form">

        <label for="produitLibelle">Nom du produit :</label>
        <input type="text" name="produitLibelle" id="produitLibelle" value="<?= htmlspecialchars($product['produitLibelle']) ?? '' ?>" required>

        <label for="produitDescription">Description du produit</label>
        <textarea name="produitDescription" id="produitDescription" required><?= htmlspecialchars($product['produitDescription']) ?? '' ?></textarea>
        <label for="produitEnAvant">Mettre en avant le produit</label>
        <div class="priority">
            <?php


            // Affichez le choix précédemment sélectionné dans le formulaire


            echo '<br>';

             if ($produitEnAvant == 1): ?>
                <input type="radio" class="produitEnAvant" name="produitEnAvant" value="1" data-produitEnAvantOrdre="<?= htmlspecialchars($product['produitEnAvantOrdre']) ?>" checked> En avant
                <input type="radio" class="produitEnAvant" name="produitEnAvant" value="0" data-produitEnAvantOrdre=""> Pas en avant
            <?php else: ?>
                <input type="radio" class="produitEnAvant" name="produitEnAvant" value="1" data-produitEnAvantOrdre="<?= htmlspecialchars($product['produitEnAvantOrdre']) ?>"> En avant
                <input type="radio" class="produitEnAvant" name="produitEnAvant" value="0" data-produitEnAvantOrdre="" checked> Pas en avant
            <?php endif; 
           
            ?>
        </div>
        <div class="ordreproduit">
        <?php if (isset($produitEnAvant) && $produitEnAvant == 1) {
             
                echo '<label for="produitEnAvantOrdre">Ordre:</label>';
                echo '<input type="number" name="produitEnAvantOrdre" id="produitEnAvantOrdre" value="' . htmlspecialchars($product['produitEnAvantOrdre']) . '" min="1" max="8" required>';
        }?>
               </div>
            
        <label for="category">Categorie:</label>
        <select name="category" id="category" required>
            <?php foreach ($categories as $categorie) {
                if ($categorie['categorieId'] == $product['categorieId']) { ?>
                    <option value="<?= $categorie['categorieId'] ?>" selected><?= htmlspecialchars($categorie['categorieLibelle']) ?> </option>

                <?php } else { ?>
                    <option value="<?= $categorie['categorieId'] ?>"><?= htmlspecialchars($categorie['categorieLibelle']) ?> </option>
            <?php }
            } ?>

        </select>

        <?php
        $i = 0;
        foreach ($variants as $variant) {
        ?>

            <div class="variant">

                <h3>Variant n°<?= $i + 1 ?></h3>

                <label for="variantFormat">Format:</label>
                <input type="text" name="variantFormat[]" id="variantFormat" value="<?= htmlspecialchars($variant['variantFormat']) ?>" required>

                <label for="variantPrice">Prix:</label>
                <input type="text" name="variantPrix[]" id="variantPrix" value="<?= htmlspecialchars($variant['variantPrix']) ?> €" required>

                <label for="variantPoids">Poids de la variante :</label>
                <input type="text" name="variantPoids[]" id="variantPoids" value="<?= htmlspecialchars($variant['variantPoids']) ?>" required>

                <label for="variantStockDisponible">Stock disponible de la variante :</label>
                <input type="text" name="variantStockDisponible[]" id="variantStockDisponible" value="<?= htmlspecialchars($variant['variantStockDisponible']) ?>" required>

                <div class="stocking">
                    <label for="variantHorsStock">En vente</label>
                    <input type="radio" name="variantHorsStock[<?= $i ?>]" value="0" <?= $variant['variantHorsStock'] == 0 ? 'checked' : '' ?> required>
                    <label for="variantHorsStock">Pas en vente</label>
                    <input type="radio" name="variantHorsStock[<?= $i ?>]" value="1" <?= $variant['variantHorsStock'] == 1 ? 'checked' : '' ?> required>
                </div>
                <input type="hidden" value="<?= $variant['variantId'] ?>" name="variantId[]">
                <button type="button" class="delete-variant" data-variantId="<?= $variant['variantId'] ?>">Supprimer</button>
            </div>

        <?php
            $i++;
        }


        ?>
        <button type="button" id="add-variant">Add Variant</button>

        <button type="button" id="add-photo">Ajouter une photo</button>
        <div id="photos">



            <?php
            $i = 0;
            foreach ($photos as $photo) {
            ?>
                <div class="photo">

                    <h3>Photo n°<?= $i + 1 ?></h3>
                    <img id="photo-img-<?= $i ?>" src="<?= $photo['photoUrl'] ?>" alt="<?= htmlspecialchars($photo['photoLegende']) ?>">
                    <input type="file" name="photos[]" id="photo-input-<?= $i ?>">

                    <label for="photoLegende">Legende:</label>
                    <input type="text" name="photoLegende[]" id="photoLegende" value="<?= htmlspecialchars($photo['photoLegende']) ?>" required>

                    <label for="photoOrdre">Ordre:</label>
                    <input type="text" name="photoOrdre[]" id="photoOrdre" value="<?= htmlspecialchars($photo['photoOrdre']) ?>" required>

                    <label for="photoUrl">Nom du fichier, extension jpg obligé</label>
                    <input type="text" name="photoUrl[]" id="photoUrl" value="<?= htmlspecialchars($photo['photoUrl']) ?>" required>
                    <input type="hidden" value="<?= $photo['photoId'] ?>" name="photoId[]">
                    <button type="button" class="delete-photo" data-photoId="<?= $photo['photoId'] ?>">Supprimer</button>
                </div>
            <?php
                $i++;
            }
            ?>


        </div>

        <input type="hidden" value="<?= $_GET['produitId'] ?>" name="produitId">
        <input type="submit" value="Envoyer" id="submit-button">
    </form>

</main>
<script src="/assets/scripts/detailsProduits.js"></script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>