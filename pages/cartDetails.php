<?php



$css = "cartDetails";
$title = "Cart details";
include './header/header.php';
require_once '../bdd/connect.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}
?>
<main>
        
    <section class="article-list">
        <ul>
        <?php 
        $total = 0;
        $totalPoids =0;
        foreach ($_SESSION['panier'] as $item){ 
             $variantId = $item['variantId'];
            echo '<li>';
        
            echo '<img src="'.$item['photoUrl'].'" alt="'.$item['photoLegende'].'">';          
            echo '<div>';
                echo '<p class ="top-p"> <span>'.$item['libelle'].'</span> #'.$item['produitId'].'</p>';
                echo '<div class="details-container">';
                    echo '<p>'.$item['format'].'</p>';
                    echo' <div class="quantity-container">';
                        echo '<button type="button" class="decrementBtn">-</button>';
                        echo '<input type="number" class="quantity" name="quantity" min="1" max="99" value="'.$item['quantite'].'">';
                        echo '<button class="incrementBtn" type="button">+</button>';
                    echo '</div>';
                    echo '<a href ="#" data-variant-id="'.$variantId.'"class="remove-link"> <img src="/assets/img/cross-delete.png"></a>';
                echo '</div> ';
                echo '<p class ="price">'.$item['prix'].' €</p>';
                $total += $item['prix']*$item['quantite'];
                $totalPoids += $item['poids']*$item['quantite'];

            echo '</div>';
        }
        if($total == 0){
            echo '<li class="no-articles">Your basket is empty</li>';
        }
        ?>
        </ul>
    </section>
        <div class="subtotal">
            <p id="subtotal"><span>Subtotal :</span> <?=$total?> €</p>
            <p>Taxes and shipping calculated at checkout</p>
        </div>
        <div class="button-container-cart">
        <?php if ($total > 0): ?>
        <button class="clear-cart">clear the cart</button>
        <a href="./checkout.php" class="checkout"><button>checkout</button>
    <?php else: ?>
        <button class="clear-cart" disabled>clear the cart</button>
        <button class="checkout" disabled>checkout</button>
    <?php endif; ?>
        </div>
</main>
<script src="../assets/scripts/cartDetails.js"></script>

<?php
include './header/footer.php';
?>