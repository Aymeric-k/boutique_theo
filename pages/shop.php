<?php
$css = "shop";
$title = "Shop page";
require_once '../bdd/connect.php';
function slugify($text) {
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
include './header/header.php';


$sql = $db->prepare('SELECT categorieLibelle from categorie');
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
ph.photoUrl,
ph.photoLegende
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
) AS ph ON p.produitId = ph.produitId
WHERE 
NOT EXISTS (
    SELECT 1 
    FROM variant_produit vp
    WHERE vp.produitId = p.produitId AND vp.variantHorsStock = 1
)');
$sql->execute();
$products = $sql->fetchAll();


?>
<main>
    <h1>Shop</h1>
    <ul class="categories-list">
        <?php
        foreach ($categories as $category) {
            echo '<li><a href="#" data-category="' . htmlspecialchars($category['categorieLibelle']) . '">' . htmlspecialchars($category['categorieLibelle']) . '</a></li>';
        }
        ?>
    </ul>
    <ul class="shop-list">
        <?php
        foreach ($products as $product) {
            $categoryClass = strtolower($product["categorieLibelle"]);
            $slug = slugify($product['produitLibelle']);

            echo '<li class="product ' . $categoryClass . '">';
            echo '<a href="./article.php?name=' . $slug . '&id=' . $product['produitId'] . '">';
            echo '<div class="pict-container">';
            echo '<img src="' . $product["photoUrl"] . '" alt="'.$product["photoLegende"].'">';
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


    categoryLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();

            const category = this.textContent.trim().toLowerCase();


            if (this.classList.contains("active")) {
                this.classList.remove("active");

                const products = document.querySelectorAll(".product");
                products.forEach(product => {
                    product.style.display = "block";
                });
            } else {

                categoryLinks.forEach(innerLink => {
                    innerLink.classList.remove("active");
                });
                this.classList.add("active");

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
</script>
<?php
include './header/footer.php';
?>