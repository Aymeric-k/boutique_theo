<?php
session_start();
$rootPath = $_SERVER['DOCUMENT_ROOT'];
function remove_shipping_fees() {
    if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $key => $item) {
            if ($item['libelle'] === 'Shipping fees') {
                unset($_SESSION['panier'][$key]);
            }
        }
        // Réindexer le panier pour éviter les clés non séquentielles
        $_SESSION['panier'] = array_values($_SESSION['panier']);
    }
}

// Appeler la fonction pour supprimer les frais de livraison
remove_shipping_fees();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="description" content="Buy unique, high-quality prints and stickers. Explore my diverse collection for all tastes and occasions. Fast international shipping.">

    <!-- <link rel="canonical" href="https://www.idk.com" /> -->
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <!-- <meta property="og:url" content="https://www.idk.com/"> -->
    <meta property="og:title" content="Théo Kreicher Shop">
    <meta property="og:description" content="Buy unique, high-quality prints and stickers. Explore my diverse collection for all tastes and occasions. Fast international shipping.">
     <meta property="og:image" content="/assets/img/Favicon.svg"> 
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <!-- <meta property="twitter:url" content="https://www.idk.com/"> -->
    <meta property="twitter:title" content="Théo Kreicher Shop">
    <meta property="twitter:description" content="Buy unique, high-quality prints and stickers. Explore my diverse collection for all tastes and occasions. Fast international shipping.">
     <meta property="twitter:image" content="/assets/img/Favicon.svg"> 
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/<?= $css ?>.css">
    <link rel="icon" type="image/x-icon" href="/assets/img/Favicon.svg">

</head>

<body>
    <header id="nav-block">

        <nav>
            <div class="nav-gridbox-desktop">
                <a href="/../index.php">
                    <div class="logo-container-desktop">

                    </div>
                </a>
                <div class="nav-menu-desktop">
                    <ul class="bold">
                        <li>
                            <a href="/index.php" data-page="index">home</a>
                        </li>
                        <li>
                            <a href="/pages/about.php" data-page="about">about</a>
                        </li>
                        <li>
                            <a href="/pages/contact.php" data-page="contact">contact</a>
                        </li>
                    </ul>
                </div>
                <div class="cart-container">
                    <?php
                    if (isset($_SESSION['panier'])) {
                        $totalItems = 0;
                        if (sizeof($_SESSION['panier']) > 0) {
                            foreach ($_SESSION['panier'] as $article) {
                                $totalItems += $article['quantite'];
                            } ?>

                            <div class="cart-count-container">
                                <p id="cart-count"><?= $totalItems ?></p>
                            </div>
                    <?php

                        }
                    }
                    ?>
                    <svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="##235af0" stroke-width="0.00024000000000000003" class="cart">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.288"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.7351 14.0181C8.91085 13.6985 9.24662 13.5 9.61132 13.5H16.47C17.22 13.5 17.88 13.09 18.22 12.47L21.6008 6.33041C21.7106 6.13097 21.7448 5.91025 21.7129 5.70131C21.8904 5.52082 22 5.27321 22 5C22 4.44772 21.5523 4 21 4H6C5.96703 4 5.93443 4.0016 5.90228 4.00471L5.7317 3.64435C5.40095 2.94557 4.69708 2.5 3.92398 2.5H2.92004C2.36776 2.5 1.92004 2.94772 1.92004 3.5C1.92004 4.05228 2.36776 4.5 2.92004 4.5H3.14518C3.6184 4.5 4.04931 4.77254 4.25211 5.20011L7.08022 11.1627C7.35632 11.7448 7.33509 12.4243 7.02318 12.988L6.17004 14.53C5.44004 15.87 6.40004 17.5 7.92004 17.5H18.92C19.4723 17.5 19.92 17.0523 19.92 16.5C19.92 15.9477 19.4723 15.5 18.92 15.5H9.61131C8.85071 15.5 8.36855 14.6845 8.7351 14.0181ZM17.0408 10.4677L19.5108 6H6.84671L8.90839 10.3557C9.23914 11.0544 9.94301 11.5 10.7161 11.5H15.2905C16.0183 11.5 16.6886 11.1046 17.0408 10.4677Z" fill="#235af0"></path>
                            <path d="M7.92005 18.5C6.82005 18.5 5.93005 19.4 5.93005 20.5C5.93005 21.6 6.82005 22.5 7.92005 22.5C9.02005 22.5 9.92005 21.6 9.92005 20.5C9.92005 19.4 9.02005 18.5 7.92005 18.5Z" fill="#235af0"></path>
                            <path d="M17.9201 18.5C16.8201 18.5 15.9301 19.4 15.9301 20.5C15.9301 21.6 16.8201 22.5 17.9201 22.5C19.0201 22.5 19.9201 21.6 19.9201 20.5C19.9201 19.4 19.0201 18.5 17.9201 18.5Z" fill="#235af0"></path>
                        </g>
                    </svg>
                </div>
            </div>
            <div class="nav-gridbox-mobile">
                <a href="/../index.php">
                    <div class="logo-container">

                    </div>
                </a>
                <div class="cart-container">
                    <?php
                    if (isset($_SESSION['panier'])) {
                        if (sizeof($_SESSION['panier']) > 0) { ?>
                            <div class="cart-count-container">
                                <p id="cart-count"><?= sizeof($_SESSION['panier']) ?></p>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <svg viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="##235af0" stroke-width="0.00024000000000000003" class="cart">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.288"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.7351 14.0181C8.91085 13.6985 9.24662 13.5 9.61132 13.5H16.47C17.22 13.5 17.88 13.09 18.22 12.47L21.6008 6.33041C21.7106 6.13097 21.7448 5.91025 21.7129 5.70131C21.8904 5.52082 22 5.27321 22 5C22 4.44772 21.5523 4 21 4H6C5.96703 4 5.93443 4.0016 5.90228 4.00471L5.7317 3.64435C5.40095 2.94557 4.69708 2.5 3.92398 2.5H2.92004C2.36776 2.5 1.92004 2.94772 1.92004 3.5C1.92004 4.05228 2.36776 4.5 2.92004 4.5H3.14518C3.6184 4.5 4.04931 4.77254 4.25211 5.20011L7.08022 11.1627C7.35632 11.7448 7.33509 12.4243 7.02318 12.988L6.17004 14.53C5.44004 15.87 6.40004 17.5 7.92004 17.5H18.92C19.4723 17.5 19.92 17.0523 19.92 16.5C19.92 15.9477 19.4723 15.5 18.92 15.5H9.61131C8.85071 15.5 8.36855 14.6845 8.7351 14.0181ZM17.0408 10.4677L19.5108 6H6.84671L8.90839 10.3557C9.23914 11.0544 9.94301 11.5 10.7161 11.5H15.2905C16.0183 11.5 16.6886 11.1046 17.0408 10.4677Z" fill="#235af0"></path>
                            <path d="M7.92005 18.5C6.82005 18.5 5.93005 19.4 5.93005 20.5C5.93005 21.6 6.82005 22.5 7.92005 22.5C9.02005 22.5 9.92005 21.6 9.92005 20.5C9.92005 19.4 9.02005 18.5 7.92005 18.5Z" fill="#235af0"></path>
                            <path d="M17.9201 18.5C16.8201 18.5 15.9301 19.4 15.9301 20.5C15.9301 21.6 16.8201 22.5 17.9201 22.5C19.0201 22.5 19.9201 21.6 19.9201 20.5C19.9201 19.4 19.0201 18.5 17.9201 18.5Z" fill="#235af0"></path>
                        </g>
                    </svg>
                </div>
                <div class="burger-menu">
                    <svg width="30" height="30" viewBox="0 -4 30 36" fill="none" xmlns="http://www.w3.org/2000/svg" class="burger-icon">
                        <line y1="0" x2="30" y2="0" stroke="#235AF0" stroke-width="2.5" />
                        <line y1="15" x2="30" y2="15" stroke="#235AF0" stroke-width="2.5" />
                        <line y1="30" x2="30" y2="30" stroke="#235AF0" stroke-width="2.5" />
                    </svg>
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="cross-icon invisible">
                        <circle cx="20" cy="20" r="19.5" stroke="white" stroke-width="2" />
                        <line x1="8.64645" y1="30.8596" x2="29.8597" y2="9.64644" stroke="white" stroke-width="2.5" />
                        <line x1="9.35355" y1="9.64645" x2="30.5668" y2="30.8596" stroke="white" stroke-width="2.5" />
                    </svg>
                </div>
            </div>
        </nav>
        <div class="nav-div">
            <div class="content">
                <ul class="nav-menu bold bold">
                    <li class="animated-item"><a href="/index.php">home</a></li>
                    <li class="animated-item"><a href="../pages/about.php">about</a></li>
                    <li class="animated-item"><a href="../pages/contact.php">contact</a></li>
                </ul>
                <ul class="socials bold">
                    <li class="animated-item"><a href="https://www.instagram.com/theokreicher/">Instagram</a></li>
                    <li class="animated-item"><a href="https://dribbble.com/theorz">Dribbble</a></li>
                    <li class="animated-item"><a href="https://www.behance.net/theorz">Behance</a></li>
                    <li class="animated-item"><a href="https://twitter.com/theokreicher">Twitter</a></li>
                </ul>
            </div>
        </div>
        <div class="cart-list">
            
            <ul class="shopping-list">
                <?php
             
                $total = 0;
                $totalItems = 0;
                if (isset($_SESSION['panier'])) {

                    if (sizeof($_SESSION['panier'])) {


                        foreach ($_SESSION['panier'] as $article) {
                            echo '<li>';

                            echo '<img src="' . $article['photoUrl'] . '" alt="' . $article['photoLegende'] . '" class="product-img">';
                            echo '<div>';
                            echo '              <p>' . $article['libelle'] . ' #' .  $article['produitId'] . '</p>';
                      
                            echo '<p>' . $article['format'] . ' <span>' . $article['prix'] . ' €</span> <a href="#" data-variant-id="' . $article['variantId'] . '" class="remove-link-header"><img src="/assets/img/cross-delete.png" class="remove-img-header"></a> <a href="/pages/article.php?name=' . $article['libelle'] . '&id=' . $article['produitId'] . '" class="product-link">article page</a></p>';
                            echo '<p> x ' . $article['quantite'] . '</p>';
                            echo '</div>';
                            echo '</li>';

                            $totalItems += $article['quantite'];
                            $total += $article['prix'] * $article['quantite'];
                        }
                    } else {
                        echo '<li class="no-articles">Your basket is empty</li>';
                    }
                } else {
                    echo '<li class="no-articles">Your basket is empty</li>';
                }
                ?>
            </ul>
            <?php

            ?>
            <div class="subtotal-container">
                <p>recap : <span class="item-count"><?= $totalItems ?> items </span></p>
                <p>Subtotal : <span class="subtotal-nbr"><?= $total ?> €</span> </p>
            </div>
     <div class="button-container">
                <a href="/pages/cartDetails.php"><button>cart details</button></a>
                <?php
                if (isset($_SESSION['panier'])) {

                    if (sizeof($_SESSION['panier'])) {
                ?>
                        <a href="/pages/checkout.php" class="checkout"><button id="disabled">checkout</button></a>
                    <?php } else { ?>
                        <a href="/pages/checkout.php" class="checkout"><button id="disabled" disabled>checkout</button></a>
                <?php
                    }
                }else { ?>
                    <a href="/pages/checkout.php" class="checkout"><button id="disabled" disabled>checkout</button></a>
                    <?php
                }
                ?>
            </div>
        </div>
    </header>
    <aside class="socials">
        <ul>
            <li>
                <a href="https://www.instagram.com/theokreicher/">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="64px" height="64px">
                        <g fill="#235af0" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                            <g transform="scale(8,8)">
                                <path d="M11.46875,5c-3.55078,0 -6.46875,2.91406 -6.46875,6.46875v9.0625c0,3.55078 2.91406,6.46875 6.46875,6.46875h9.0625c3.55078,0 6.46875,-2.91406 6.46875,-6.46875v-9.0625c0,-3.55078 -2.91406,-6.46875 -6.46875,-6.46875zM11.46875,7h9.0625c2.47266,0 4.46875,1.99609 4.46875,4.46875v9.0625c0,2.47266 -1.99609,4.46875 -4.46875,4.46875h-9.0625c-2.47266,0 -4.46875,-1.99609 -4.46875,-4.46875v-9.0625c0,-2.47266 1.99609,-4.46875 4.46875,-4.46875zM21.90625,9.1875c-0.50391,0 -0.90625,0.40234 -0.90625,0.90625c0,0.50391 0.40234,0.90625 0.90625,0.90625c0.50391,0 0.90625,-0.40234 0.90625,-0.90625c0,-0.50391 -0.40234,-0.90625 -0.90625,-0.90625zM16,10c-3.30078,0 -6,2.69922 -6,6c0,3.30078 2.69922,6 6,6c3.30078,0 6,-2.69922 6,-6c0,-3.30078 -2.69922,-6 -6,-6zM16,12c2.22266,0 4,1.77734 4,4c0,2.22266 -1.77734,4 -4,4c-2.22266,0 -4,-1.77734 -4,-4c0,-2.22266 1.77734,-4 4,-4z"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://dribbble.com/theorz">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="64px" height="64px" id="dribble-logo">
                        <g fill="#235af0" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                            <g transform="scale(8,8)">
                                <path d="M16,4c-6.61719,0 -12,5.38281 -12,12c0,6.61719 5.38281,12 12,12c6.61719,0 12,-5.38281 12,-12c0,-6.61719 -5.38281,-12 -12,-12zM16,6c2.53516,0 4.83203,0.95313 6.59375,2.5c-0.1875,0.26172 -0.44141,0.57813 -0.84375,0.96875c-0.85547,0.82813 -2.22266,1.82422 -4.3125,2.59375c-1.41406,-2.61328 -2.80078,-4.57812 -3.71875,-5.8125c0.73047,-0.16797 1.49609,-0.25 2.28125,-0.25zM11.75,6.9375c0.82031,1.07422 2.28125,3.06641 3.75,5.71875c-4.06641,1.07813 -7.79687,1.21484 -9.28125,1.21875c0.66406,-3.08984 2.74219,-5.63281 5.53125,-6.9375zM23.96875,9.96875c1.21875,1.61328 1.97656,3.60938 2.03125,5.78125c-0.89062,-0.19922 -2.20312,-0.39453 -3.90625,-0.40625c-0.88672,-0.00391 -1.89062,0.05859 -2.96875,0.1875c-0.25,-0.57031 -0.51953,-1.12109 -0.78125,-1.65625c2.24219,-0.85937 3.76953,-1.99219 4.78125,-2.96875c0.34375,-0.33594 0.61328,-0.64453 0.84375,-0.9375zM16.40625,14.46875c0.23047,0.46484 0.46484,0.94141 0.6875,1.4375c-4.27344,1.18359 -7.34375,4.80859 -8.65625,6.625c-1.51172,-1.75 -2.4375,-4.03125 -2.4375,-6.53125c0,-0.04297 0,-0.08203 0,-0.125c1.35156,0.01563 5.64844,-0.07812 10.40625,-1.40625zM22.09375,17.3125c1.78516,0 3.01953,0.25 3.75,0.4375c-0.46484,2.66406 -1.96875,4.94922 -4.09375,6.4375c-0.39844,-2.39062 -1.05469,-4.66406 -1.84375,-6.75c0.78906,-0.07812 1.53516,-0.125 2.1875,-0.125zM17.875,17.78125c0.89844,2.28125 1.65234,4.78516 2.03125,7.4375c-1.19531,0.50391 -2.52344,0.78125 -3.90625,0.78125c-2.29297,0 -4.41016,-0.76953 -6.09375,-2.0625c1.02734,-1.40625 4.04688,-5.14844 7.96875,-6.15625z"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://www.behance.net/theorz">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="64px" height="64px">
                        <g fill="#235af0" fill-rule="none" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                            <g transform="scale(4,4)">
                                <path d="M43.462,52h-22.924c-2.28,0 -4.424,-0.888 -6.037,-2.5c-1.612,-1.613 -2.501,-3.757 -2.501,-6.039v-22.922c0,-2.281 0.889,-4.426 2.501,-6.038c1.613,-1.612 3.757,-2.5 6.037,-2.5h22.924c2.28,0 4.424,0.888 6.037,2.5c1.612,1.612 2.501,3.756 2.501,6.038v22.923c0,2.281 -0.889,4.426 -2.501,6.038c-1.613,1.612 -3.757,2.5 -6.037,2.5zM20.538,16c-1.212,0 -2.352,0.472 -3.209,1.33c-0.857,0.856 -1.329,1.996 -1.329,3.209v22.923c0,1.213 0.472,2.353 1.329,3.209c0.857,0.857 1.997,1.33 3.209,1.33h22.924c1.212,0 2.352,-0.472 3.209,-1.33c0.857,-0.857 1.329,-1.997 1.329,-3.21v-22.922c0,-1.213 -0.472,-2.353 -1.329,-3.209c-0.858,-0.858 -1.997,-1.33 -3.209,-1.33z" fill-rule="nonzero"></path>
                                <path d="M43.487,24.999h-6c-0.553,0 -1,-0.448 -1,-1c0,-0.552 0.447,-1 1,-1h6c0.553,0 1,0.448 1,1c0,0.552 -0.447,1 -1,1z" fill-rule="nonzero"></path>
                                <path d="M28.793,31.345c0.951,0.326 2.695,1.252 2.695,4.081c0,4.384 -4.756,4.573 -5.39,4.574h-7.61v-16.001h7.293c0.792,0 4.707,-0.134 4.707,4c0,2.198 -1.062,3.02 -1.695,3.346zM22.488,26.999v3h2.619c0.357,0 1.547,-0.226 1.547,-1.565c0,-1.34 -1.548,-1.435 -1.785,-1.435zM25.418,36.981c0.254,0 2.037,-0.098 2.037,-1.916c0,-1.82 -1.401,-2.065 -2.037,-2.065h-2.93v3.981z" fill-rule="evenodd"></path>
                                <path d="M43.141,36h3.182c-0.563,1.868 -2.591,3.999 -5.667,3.999c-2.028,0 -6.169,-1.127 -6.169,-6.5c0,-5.807 4.479,-6.5 6,-6.5c5.375,0 6.125,4.5 6,8v0.001h-2.68v-0.001h-5.321c0,0 0.512,1.982 2.512,1.982c0.992,0 1.69,-0.488 2.143,-0.981zM40.573,29.6c-1.326,-0.023 -2.823,1.15 -2.788,2.4h5.408c-0.085,-0.775 -0.338,-1.394 -0.761,-1.781c-0.422,-0.387 -0.934,-0.603 -1.859,-0.619z" fill-rule="evenodd"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li>
                <a href="https://twitter.com/theokreicher">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="64px" height="64px">
                        <g fill="#235af0" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                            <g transform="scale(10.66667,10.66667)">
                                <path d="M22,3.999c-0.78,0.463 -2.345,1.094 -3.265,1.276c-0.027,0.007 -0.049,0.016 -0.075,0.023c-0.813,-0.802 -1.927,-1.299 -3.16,-1.299c-2.485,0 -4.5,2.015 -4.5,4.5c0,0.131 -0.011,0.372 0,0.5c-3.353,0 -5.905,-1.756 -7.735,-4c-0.199,0.5 -0.286,1.29 -0.286,2.032c0,1.401 1.095,2.777 2.8,3.63c-0.314,0.081 -0.66,0.139 -1.02,0.139c-0.581,0 -1.196,-0.153 -1.759,-0.617c0,0.017 0,0.033 0,0.051c0,1.958 2.078,3.291 3.926,3.662c-0.375,0.221 -1.131,0.243 -1.5,0.243c-0.26,0 -1.18,-0.119 -1.426,-0.165c0.514,1.605 2.368,2.507 4.135,2.539c-1.382,1.084 -2.341,1.486 -5.171,1.486h-0.964c1.788,1.146 4.065,2.001 6.347,2.001c7.43,0 11.653,-5.663 11.653,-11.001c0,-0.086 -0.002,-0.266 -0.005,-0.447c0,-0.018 0.005,-0.035 0.005,-0.053c0,-0.027 -0.008,-0.053 -0.008,-0.08c-0.003,-0.136 -0.006,-0.263 -0.009,-0.329c0.79,-0.57 1.475,-1.281 2.017,-2.091c-0.725,0.322 -1.503,0.538 -2.32,0.636c0.834,-0.5 2.019,-1.692 2.32,-2.636zM18,8.999c0,4.08 -2.957,8.399 -8.466,8.943c0.746,-0.529 1.466,-1.28 1.466,-1.28c0,0 -3,-2.662 -3.225,-6.14c1.035,0.316 2.113,0.477 3.225,0.477h2v-2.5c0,-0.001 0,-0.001 0,-0.001c0.002,-1.38 1.12,-2.498 2.5,-2.498c1.381,0 2.5,1.119 2.5,2.5c0,0 0,0.42 0,0.499z"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
        </ul>
    </aside>