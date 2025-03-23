<?php



$css = "checkout";
$title = "Checkout";
include './header/header.php';
require_once '../bdd/connect.php';

$sql = "SELECT * FROM pays";
$request = $db->prepare($sql);
$request->execute();
$pays = $request->fetchAll();
$rugs = false;
// Définir le chemin du fichier de logs
$logFile = __DIR__ . '/../logs.log';

// Fonction pour enregistrer les logs
function log_message($message)
{
    global $logFile;
    error_log($message . "\n", 3, $logFile);
}
?>

<script>
    var fraisLivraison = <?php echo json_encode($pays); ?>;
</script>
<main>
    <section class="article-list">
        <ul>
            <?php
            $total = 0;
            $totalPoids = 0;
            foreach ($_SESSION['panier'] as $item) {
                 $variantId = $item['variantId'];
                if ($item['category'] === "Rugs") {
                    $rugs = true;
                }
                echo '<li>';
                echo '<img src="' . $item['photoUrl'] . '" alt="' . $item['photoLegende'] . '">';
                echo '<div>';
                echo '<p class ="top-p"> <span>' . $item['libelle'] . ' </span> #' . $item['produitId'] . '</p>';
                echo '<p data-variant-id="'.$variantId.'">' . $item['format'] . '</p>';
                echo '<p> x  ' . $item['quantite'] . '</p>';
                echo '<p class ="price">' . $item['prix'] . ' €</p>';
                $total += $item['prix'] * $item['quantite'];
                $totalPoids += $item['poids'] * $item['quantite'];
                echo '</div>';
                echo '</li>';
            }

            ?>
        </ul>
    </section>
    <section class="form-paiement">
        <form action="./command" method="post" id="payment-form">
        <p class="subtotal-mobile"> <span>Subtotal :</span> <?= $total?> €</p>
            <div class="details-paiement">
                <div class="codeVerif">
                    <label for="promoCode">Promo Code</label>
                    <input type="text" name="promoCode" placeholder="Promo code">
                    <button type="button"> ✔ </button>
                </div>
                <h2 id="order">Order delivery adress</h2>
                <div class="adress">
                    <fieldset>
                        <label for="country">Country</label>
                        <select name="country" id="country" required>
                            <option value="" disabled selected>Country</option>
                            <?php
                            foreach ($pays as $lieu) {
                                echo '<option value="' . $lieu['paysId'] . '">' . $lieu['paysLibelle'] . '</option>';
                            }

                            ?>
                        </select>
                    </fieldset>

                </div>
            </div>
            
            <div class="delivery-infos">
                <h2>Delivery price</h2>
                <p id="delivery-cost">Select a country</p>
                <p class="subtotal-desktop"><span>Subtotal :</span> <?= $total?> €</p>
                <p id="total">Total : <span id="finalTotal"></span></p>
            </div>
            <div id="card-element">

            </div>
            <div id="card-errors" role="alert">

            </div>
            <input type="hidden" name="amount" id="amount">
            <button type="submit" id="checkout-button">
                Checkout with Stripe
            </button>
        </form>
    </section>
</main>
<?php
include './header/footer.php';
?>
<script>
    let total = <?php echo $total; ?>;
    document.getElementById('country').addEventListener('change', function() {
        let countryId = this.value;
        let totalPoids = <?php echo $totalPoids; ?>; // Le poids total du panier
        console.log(totalPoids)
        let contientTapis = <?php echo json_encode($rugs); ?>; // Booléen indiquant la présence de tapis dans le panier

        // Tableaux de tarifs adaptés de votre fonction PHP
        let tarifsFranceStandard = {
            20: 179,
            100: 308,
            250: 480,
            500: 680,
            1000: 820,
            2000: 979,
        };

        let tarifsFranceRugs = {
            250: 499,
            500: 699,
            750: 810,
            1000: 880,
            2000: 1015,
            5000: 1560,
            10000: 2270,
            15000: 2870,
            30000: 3555
        };

        let tarifsEuropeStandard = {
            20: 476,
            100: 695,
            250: 1265,
            500: 1735,
            1000: 2930,
            2000: 2930,
        };

        let tarifsEuropeRugs = {
            250: 790,
            500: 925,
            1000: 1100,
            2000: 1565
        };

        // Logique de sélection des tarifs en fonction du pays et de la présence de tapis
        let tarifs;
        if (countryId == 1) {
            tarifs = contientTapis ? tarifsFranceRugs : tarifsFranceStandard;
        } else {
            tarifs = contientTapis ? tarifsEuropeRugs : tarifsEuropeStandard;
        }

        // Calcul des frais de port en fonction du poids total
        let deliveryCost = 0;
        let poidsMaxKeys = Object.keys(tarifs).map(Number)



        for (let i = 0; i < poidsMaxKeys.length; i++) {
            if (totalPoids <= poidsMaxKeys[i]) {
                deliveryCost = tarifs[poidsMaxKeys[i]];
                break;
            }
        }
        deliveryCost = deliveryCost + 200


        let finalTotal = total + deliveryCost / 100; // Convertir les centimes en euros pour l'addition
        finalTotal = finalTotal.toFixed(2);

        document.querySelector('#delivery-cost').textContent = deliveryCost / 100 + ' €';
        document.getElementById('finalTotal').textContent = finalTotal + ' €';
        document.querySelector('#amount').setAttribute('value', finalTotal);
    });
</script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe('pk_test_51NzNQxKmtjb00k01CHzRZFds1JiopW3Yr7EJSBmqmamUjihe3WvhX3jyCr0FYBHkX2MjnHvFeJo1nXAKmgoT72la00SNjYL6dL');

    let checkoutButton = document.getElementById('checkout-button');
    checkoutButton.addEventListener('click', function(e) {
        e.preventDefault();

        let form = document.querySelector('#payment-form');
        let formData = new FormData(form);

        fetch('./command', {
                method: 'POST',
                body: formData,
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(session) {
                if (session.error) {
                    throw new Error(session.error);
                }
                return stripe.redirectToCheckout({
                    sessionId: session.id
                });
            })
            .then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function(error) {
                // Envoi de l'erreur au serveur pour l'écriture dans le fichier de logs
              log_message(error.message);
                    })
               
           

    });
</script>