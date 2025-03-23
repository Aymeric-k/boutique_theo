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
include '../variables.php';

$item = $results[0];
$title = $item['produitLibelle'];
$pictures = array_map(function ($row) {
    return ['photoUrl' => $row['photoUrl'], 'photoLegende' => $row['photoLegende']];
}, $results);

include './header/header.php';

$sqlVariants = $db->prepare('SELECT variantId, variantPrix, variantFormat, produitId FROM variant_produit WHERE produitId = :produitId AND variantHorsStock = 0 ORDER BY variantPrix ASC');
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
    AND produits.produitEnAvant = 1

    ORDER BY produits.produitEnAvantOrdre ASC
LIMIT 4;
');
$sqlothers->execute(['produitId' => $_GET['id']]);
$others = $sqlothers->fetchAll();

?>



<main>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb">
            <li><a href="/index.php">Shop</a></li>
            <li aria-current="page"><?= $item['produitLibelle'] ?></li>
        </ol>
    </nav>


    <section>
        <div class="carousel-container">
            <div class="carousel-slide">
                <?php
                foreach ($pictures as $picture) {
                    echo '<img src="' . $picture['photoUrl'] . '" alt="' . $picture['photoLegende'] . '" class ="carousel-slide-image">';
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
                    echo '<p class="id"> <span>#' . $item['produitId'] . '</span> ' . '<span>' . $item['produitLibelle'] . '</span></p>';
                    if (sizeof($variants) > 1) {
                        echo '<p class="price">  </p>';
                    } else {
                        echo '<p class="price">' . $variants[0]['variantPrix'] . ' €  </p>';
                    }
                    ?>

                </div>
                <form action="" method="post" id="formAddToCart">
                    <div class="variants">
                        <label for="size" id="sizeLabel">Size </label>
                        <?php
                        if (sizeof($variants) > 1) {

                            foreach ($variants as $variant) {

                                $id = 'size_' . $variant['variantId'];
                                echo '<input type="radio" class="variant-btn" id="' . $id . '" data-variant-price="' . $variant['variantPrix'] . '" value="' . $variant['variantId'] . '" name="size">';
                                echo '<label for="' . $id . '" class="variant-label">' . $variant['variantFormat'] . '</label>';
                            }
                        } else {
                            $id = 'size_' . $variants[0]['variantId'];
                            echo '<input type="radio" class="variant-btn" id="' . $id .  '"data-variant-price="' . $variants[0]['variantPrix'] . '" value="' . $variants[0]['variantId'] . '" name="size" checked>';
                            echo '<label for="' . $id . '" class="variant-label">' . $variants[0]['variantFormat'] . '</label>';
                        }

                        ?>
                    </div>
                    <div class="quantity-container">
                        <label for="quantity">Quantity </label>
                        <button type="button" id="decrementBtn">-</button>
                        <input type="number" id="quantity" name="quantity" min="1" max="99" value="1">
                        <button id="incrementBtn" type="button">+</button>
                    </div>
                    <input type="hidden" value="<?= $variants[0]['produitId'] ?>" name="produitId">
                    <div class="cart-button-container">
                        <button type="submit" class="add-cart"> <span class="text-add">Add to cart</span> <img src="../assets/img/icons8-caddie-48 2.png" alt=""> <span class="checkmark fade-in">✔</span></button>
                    </div>
                </form>
                <div class="description">
                    
                    <?php
                    echo '<p>' . nl2br($item['produitDescription']) . '</p>';
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
<script src="/assets/scripts/article.js"></script>







<?php
include './header/footer.php';
?>