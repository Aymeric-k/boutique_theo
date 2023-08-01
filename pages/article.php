<?php
$css = "article";
require_once '../bdd/connect.php';


$sql = $db->prepare('
    SELECT p.*, ph.photoUrl, ph.photoLegende , ph.photoOrdre
    FROM produits p 
    LEFT JOIN photo ph ON p.produitId = ph.produitId 
    WHERE p.produitId = :produitId
    ORDER BY ph.photoOrdre
');
$sql->execute(['produitId' => $_GET['id']]);
$results = $sql->fetchAll();


$article = $results[0];
$title = $article['produitLibelle'];
$pictures = array_map(function ($row) {
    return ['photoUrl' => $row['photoUrl'], 'photoLegende' => $row['photoLegende']];
}, $results);

include './header/header.php';

$sqlVariants = $db->prepare('SELECT variantId, variantPrix, variantFormat FROM variant_produit WHERE produitId = :produitId AND variantHorsStock = 0 ORDER BY variantPrix ASC');
$sqlVariants->execute(['produitId' => $_GET['id']]);
$variants = $sqlVariants->fetchAll();
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // valeur par défaut


$sqlothers = $db->prepare('
SELECT 
    produits.produitLibelle,
    produits.produitId,
    ph.*
FROM 
    produits

INNER JOIN (
    SELECT DISTINCT 
        produitId
    FROM 
        variant_produit
    WHERE 
        variantHorsStock = 0
) AS vp ON vp.produitId = produits.produitId

LEFT JOIN (
    SELECT 
        *
    FROM 
        photo
    WHERE 
        photoOrdre = 1
) AS ph ON ph.produitId = produits.produitId

INNER JOIN 
    categorie ON categorie.categorieId = produits.categorieId 
WHERE 
    produits.produitId != :produitId
AND 
    categorie.categorieId = :categorie
    ORDER BY RAND()
LIMIT 4;
');
$sqlothers->execute(['produitId' => $_GET['id'], 'categorie' => $article['categorieId']]);
$others = $sqlothers->fetchAll();

?>


<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/pages/shop.php">Shop</a></li>
            <li aria-current="page"><?= $article['produitLibelle'] ?></li>
        </ol>
    </nav>


    <section>
        <div class="carousel-container">
            <div class="carousel-slide">
                <?php
                foreach ($pictures as $picture) {
                    echo '<img src="' . $picture['photoUrl'] . '" alt="' . $picture['photoLegende'] . '" class ="carousel-slide-image" loading="lazy">';
                }
                ?>
            </div>
            <button id="prevBtn"><img src="../assets/img/icons8-chevron-gauche-60 (1) 1.png" alt=""></button>
            <button id="nextBtn"><img src="../assets/img/icons8-chevron-droit-60 1.png" alt=""></button>
            <div class="carousel-indicators">
                <?php
                foreach ($pictures as $index => $picture) {
                    echo '<span class="indicator" data-index="' . $index . '"></span>';
                }
                ?>
            </div>
        </div>

        <div>
            <div class="right-col-wrapper">
                <div class="details">
                    <?php
                    echo '<p class="id"> <span>#' . $article['produitId'] . '</span> ' . '<span>' . $article['produitLibelle'] . '</span></p>';
                    if (sizeof($variants) > 1) {
                        echo '<p class="price">  </p>';
                    } else {
                        echo '<p class="price">' . $variants[0]['variantPrix'] . '  </p>';
                    }
                    ?>

                </div>
                <div class="variants">
                    <p id="sizeLabel">Size :</p>
                    <?php
                    if (sizeof($variants) > 1) {
                        foreach ($variants as $variant) {
                            echo '<button class="variant-btn mobile-btn" data-variant-id="' . $variant['variantId'] . '" data-variant-price="' . $variant['variantPrix'] . '">' . $variant['variantFormat'] . '</button>';
                        }
                    ?>
                        <select class="sizeSelect" aria-labelledby="sizeLabel">
                        <?php
                        foreach ($variants as $variant) {
                            echo '<option value="' . $variant['variantId'] . '">' . $variant['variantFormat'] . '</option>';
                        }
                    } else {
                        echo '<p class ="sizeSelect">' . $variants[0]['variantFormat'] . '</p>';

                        echo '<input type="hidden" name="selectedVariant" value="' . $variants[0]['variantId'] . '">';
                    }
                        ?>
                        </select>
                </div>
                <div class="quantity-container">
                    <label for="quantity">Quantity :</label>
                    <button id="decrementBtn">-</button>
                    <input type="number" id="quantity" name="quantity" min="1" max="99" value="1">
                    <button id="incrementBtn">+</button>
                </div>
                <div class="cart-button-container">
                    <button class="add-cart"> Add to cart <img src="../assets/img/icons8-caddie-48 2.png" alt=""> </button>
                </div>
                <div class="description">
                    <p>Descrition :</p>
                    <?php
                    echo '<p>' . nl2br($article['produitDescription']) . '</p>';
                    ?>
                </div>
                <div id="look-a-like">
                    <div class="padding-container">
                        <p>
                            You may also like
                        </p>
                    </div>
                    <ul>
                        <?php
                        foreach ($others as $other) {

                            echo '<li>';
                            echo '<a href ="article.php?name=' . $other['produitLibelle'] . '&id=' . $other['produitId'] . '">';
                            echo '<p>' . $other['produitLibelle'] . ' #' . $other['produitId'] . '</p>';
                            echo '<article style="background-image: url(' . $other['photoUrl'] . ')"> </article>';
                            echo '</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>







<script src="../assets/scripts/article.js" async></script>
<?php
include './header/footer.php';
?>