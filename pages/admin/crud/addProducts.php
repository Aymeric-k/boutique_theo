<?php
session_start();
$css = "detailsproduits";
$title = "Ajout de produits";
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header-admin.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bdd/connect.php';

// Vérifier si l'utilisateur est bien connecté
if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}

// Récupérer les catégories pour les options du produit
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
            <li aria-current="page">Ajout d'article</li>
        </ol>
    </nav>

    <!-- Formulaire pour ajouter un nouveau produit -->
    <form action="productsChange" method="POST" enctype="multipart/form-data" id="admin-form">

        <!-- Nom du produit -->
        <label for="produitLibelle">Nom du produit :</label>
        <input type="text" name="produitLibelle" id="produitLibelle" required>

        <!-- Description du produit -->
        <label for="produitDescription">Description du produit</label>
        <textarea name="produitDescription" id="produitDescription" required></textarea>

        <!-- Mettre en avant le produit -->
        <label for="produitEnAvant">Mettre en avant le produit :</label>
        <div class="priority">
            <input type="radio" class="produitEnAvant" name="produitEnAvant" value="1"> En avant
            <input type="radio" class="produitEnAvant" name="produitEnAvant" value="0" checked> Pas en avant
        </div>

        <!-- Champ pour l'ordre si le produit est mis en avant -->
        <div class="ordreproduit" style="display: none;">
            <label for="produitEnAvantOrdre">Ordre :</label>
            <input type="number" name="produitEnAvantOrdre" id="produitEnAvantOrdre" value="" min="1" max="8">
        </div>

        <!-- Catégorie du produit -->
        <label for="category">Catégorie :</label>
        <select name="category" id="category" required>
            <?php foreach ($categories as $categorie) : ?>
                <option value="<?= $categorie['categorieId'] ?>"><?= htmlspecialchars($categorie['categorieLibelle']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Gestion des variantes du produit -->
        <div id="variants">
            <div class="variant">
                <h3>Variante n°1</h3>
                <label for="variantFormat">Format :</label>
                <input type="text" name="variantFormat[]" id="variantFormat" required>

                <label for="variantPrix">Prix :</label>
                <input type="text" name="variantPrix[]" id="variantPrix" required>

                <label for="variantPoids">Poids :</label>
                <input type="text" name="variantPoids[]" id="variantPoids" required>

                <label for="variantStockDisponible">Stock disponible :</label>
                <input type="text" name="variantStockDisponible[]" id="variantStockDisponible" required>

                <div class="stocking">
                    <label for="variantHorsStock">En vente :</label>
                    <input type="radio" name="variantHorsStock[0]" value="0" required> Oui
                    <input type="radio" name="variantHorsStock[0]" value="1"> Non
                </div>
                <input type="hidden" name="variantId[]" value="0">
                <button type="button" class="delete-variant">Supprimer</button>
            </div>
        </div>

        <!-- Bouton pour ajouter une nouvelle variante -->
        <button type="button" id="add-variant">Ajouter une variante</button>

        <!-- Gestion des photos -->
        <div id="photos">
            <div class="photo">
                <h3>Photo n°1</h3>
                <input type="file" name="photos[]" id="photo-input-0" required>

                <label for="photoLegende">Légende :</label>
                <input type="text" name="photoLegende[]" id="photoLegende" required>

                <label for="photoOrdre">Ordre :</label>
                <input type="text" name="photoOrdre[]" id="photoOrdre" required>
                
                      <label for="photoUrl">Nom du fichier, extension jpg obligé</label>
                    <input type="text" name="photoUrl[]" id="photoUrl" value="<?= htmlspecialchars($photo['photoUrl']) ?>" required>

                <input type="hidden" name="photoId[]" value="0">
                <button type="button" class="delete-photo">Supprimer</button>
            </div>
        </div>

        <!-- Bouton pour ajouter une nouvelle photo -->
        <button type="button" id="add-photo">Ajouter une photo</button>

        <!-- Champs cachés -->
        <input type="hidden" name="produitId" value="0">

        <!-- Bouton pour soumettre le formulaire -->
        <input type="submit" value="Envoyer" id="submit-button">
    </form>
</main>

<!-- Script JavaScript pour gérer l'ajout dynamique de variantes, photos et la logique de produit en avant -->
<script src="/assets/scripts/detailsProduits.js"></script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>