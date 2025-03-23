<?php
$css = "shop";
$title = "Shop page";

function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);


    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    $text = preg_replace('~[^-\w]+~', '', $text);

    $text = trim($text, '-');

    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/header.php';
require_once $rootPath . '/bdd/connect.php';

$sql = $db->prepare('SELECT categorieLibelle, COUNT(produitId) as countId  from categorie INNER JOIN produits ON produits.categorieId = categorie.categorieId GROUP BY categorieLibelle HAVING countId > 0 ');
$sql->execute();
$categories = $sql->fetchAll();

$sql = $db->prepare('SELECT 
p.produitId,
p.produitLibelle,
p.produitDescription,
c.categorieLibelle,
v.variantPrix,
v.variantFormat,
v.variantPoids,
v.variantStockDisponible,
ph1.photoUrl as photoUrl1,
ph1.photoLegende as photoLegende1,
ph2.photoUrl as photoUrl2,
ph2.photoLegende as photoLegende2
FROM 
produits p
INNER JOIN 
categorie c ON p.categorieId = c.categorieId
INNER JOIN 
(
    SELECT 
        v1.variantId,
        v1.produitId,
        v1.variantPrix,
        v1.variantFormat,
        v1.variantPoids,
        v1.variantStockDisponible
    FROM 
        variant_produit v1
    WHERE 
        v1.variantPrix = (
            SELECT MIN(v2.variantPrix) 
            FROM variant_produit v2 
            WHERE v2.produitId = v1.produitId AND v2.variantHorsStock = 0
        )
) AS v ON p.produitId = v.produitId
LEFT JOIN 
(
    SELECT 
        *
    FROM 
        photo
    WHERE 
        photo.photoOrdre = 1 
) AS ph1 ON p.produitId = ph1.produitId
LEFT JOIN 
(
    SELECT 
        *
    FROM 
        photo
    WHERE 
        photo.photoOrdre = 2 
) AS ph2 ON p.produitId = ph2.produitId
WHERE 
NOT EXISTS (
    SELECT 1 
    FROM variant_produit vp
    WHERE vp.produitId = p.produitId AND vp.variantHorsStock = 1
    
)
ORDER BY p.produitEnAvant DESC,
p.produitEnAvantOrdre ASC;');
$sql->execute();
$products = $sql->fetchAll();


?>
<main>
    <h1>Shop</h1>
    <div class="filters">
        <ul class="categories-list">
            <?php
            foreach ($categories as $category) {
                echo '<li><a href="#" data-category="' . htmlspecialchars($category['categorieLibelle']) . '">' . htmlspecialchars($category['categorieLibelle']) . '</a></li>';
            }
            ?>
        </ul>
        <div id="clear-filters" style="display:none;">
            <a href="#" id="clear-filters-link">clear filters</a>

        </div>
    </div>
    <ul class="shop-list">
        <?php
        foreach ($products as $product) {
            $categoryClass = strtolower($product["categorieLibelle"]);
            $slug = slugify($product['produitLibelle']);

            echo '<li class="product ' . $categoryClass . '">';
            echo '<a href="/pages/article.php?name=' . $slug . '&id=' . $product['produitId'] . '">';
            echo '<div class="pict-container">';
            echo '<img class="main-image" src="' . $product["photoUrl1"] . '" alt="' . $product["photoLegende1"] . '">';
    if (!empty($product["photoUrl2"])) {
        echo '<img class="hover-image" src="' . $product["photoUrl2"] . '" alt="' . $product["photoLegende2"] . '">';
    }
            echo '</div>';
            echo '<div class="informations">';
            echo '<p><span>' . $product["produitLibelle"] . '</span> #' . $product["produitId"] . '</p>';
            echo '<p> from <span>' . $product["variantPrix"] . '€ </span></p>';
            echo '</div>';
            echo '</a>';
            echo '</li>';
        }
        ?>

    </ul>

</main>







<script>
    const categoryLinks = document.querySelectorAll(".categories-list a");
    const clearFiltersLink = document.getElementById("clear-filters-link");
    const clearFiltersDiv = document.getElementById("clear-filters");

    categoryLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();

            const category = this.textContent.trim().toLowerCase();


            if (this.classList.contains("active")) {
                this.classList.remove("active");
                clearFiltersDiv.style.display = "none";

                const products = document.querySelectorAll(".product");
                products.forEach(product => {
                    product.style.display = "block";
                });
            } else {

                categoryLinks.forEach(innerLink => {
                    innerLink.classList.remove("active");
                });
                this.classList.add("active");
                clearFiltersDiv.style.display = "block"; // Show the clear filters link
                const products = document.querySelectorAll(".product");
                products.forEach(product => {
                    if (product.classList.contains(category)) {
                        product.style.display = "block";
                    } else {
                        product.style.display = "none";
                    }
                });
            }
        });
    });
    clearFiltersLink.addEventListener("click", function(e) {
        e.preventDefault();
        clearFiltersDiv.style.display = "none"; // Hide the clear filters link

        categoryLinks.forEach(link => {
            link.classList.remove("active");
        });

        const products = document.querySelectorAll(".product");
        products.forEach(product => {
            product.style.display = "block";
        });
    });
</script>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/pages/header/footer.php';
?>