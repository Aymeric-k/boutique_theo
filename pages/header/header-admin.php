<!DOCTYPE html>
<html lang="fr">

<head>

    <title><?= $title ?></title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/<?= $css ?>.css">

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
                            <a href="/index.php">home</a>
                        </li>
                        <li>
                            <a href="/pages/about.php">about</a>
                        </li>
                        <li>
                            <a href="/pages/contact.php">contact</a>
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
                <div class="logo-container">

                </div>
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
                    <li class="animated-item"><a href="/pages/about.php">about</a></li>
                    <li class="animated-item"><a href="/pages/contact.php">contact</a></li>
                </ul>
                <ul class="socials bold">
                    <li class="animated-item"><a href="#">Instagram</a></li>
                    <li class="animated-item"><a href="#">Dribbble</a></li>
                    <li class="animated-item"><a href="#">Behance</a></li>
                    <li class="animated-item"><a href="#">Twitter</a></li>
                </ul>
            </div>
        </div>
        <div class="cart-list">
            <ul class="shopping-list">
                <?php
                if (isset($_SESSION['panier'])) {
                    $total = 0;
                    foreach ($_SESSION['panier'] as $article) {
                    }

                    foreach ($_SESSION['panier'] as $article) {
                        echo '<li>';

                        echo '<img src="' . $article['photoUrl'] . '" alt="' . $article['photoLegende'] . '">';
                        echo '<div>';
                        echo '              <p>' . $article['libelle'] . ' #' .  $article['produitId'] . '</p>';
                        echo '<p>' . $article['format'] . ' <span>' . $article['prix'] . ' €</span> <a href="/pages/article.php?name=' . $article['libelle'] . '&id=' . $article['produitId'] . '">article page</a></p>';
                        echo '<p> x ' . $article['quantite'] . '</p>';
                        echo '</div>';
                        echo '</li>';


                        $total += $article['prix'] * $article['quantite'];
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
                <a href="/pages/cartDetails.php" class="checkout"><button>checkout</button></a>
            </div>
        </div>
    </header>
    <aside class="socials">
        <ul>
            <li>
                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#235af0" class="svg-logo">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="#235af0" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="instagram"></path>
                            <path d="M12 15.5C13.933 15.5 15.5 13.933 15.5 12C15.5 10.067 13.933 8.5 12 8.5C10.067 8.5 8.5 10.067 8.5 12C8.5 13.933 10.067 15.5 12 15.5Z" stroke="#235af0" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="instagram"></path>
                            <path d="M17.6361 7H17.6477" stroke="#235af0" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="instagram"></path>
                        </g>
                    </svg>
                </a>
            </li>
            <li>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="dribbble" class="svg-logo">
                        <g data-name="<Group>">
                            <circle cx="12" cy="12" r="11.5" fill="none" stroke="#235af0" stroke-linecap="round" stroke-linejoin="round" data-name="<Path>" class="behance"></circle>
                            <g data-name="<Group>">
                                <path fill="none" stroke="#235af0" stroke-linecap="round" stroke-linejoin="round" d="M7.63 1.36c5.5 6.64 8.37 13 9.37 21M.55 10.88C10.39 10.66 16 9.1 20.22 4M4.11 20.37c5.29-8.05 11.78-9.28 19.31-7.05" data-name="<Path>" class="dribbble"></path>
                            </g>
                        </g>
                    </svg>
                </a>
            </li>
            <li>
                <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" id="behance" class="svg-logo">
                        <path d="M28 1c1.654 0 3 1.346 3 3v24c0 1.654-1.346 3-3 3H4c-1.654 0-3-1.346-3-3V4c0-1.654 1.346-3 3-3h24m0-1H4C1.8 0 0 1.8 0 4v24c0 2.2 1.8 4 4 4h24c2.2 0 4-1.8 4-4V4c0-2.2-1.8-4-4-4z" stroke="#235af0" stroke-width="0.8" fill="#235af0" class="behance"></path>
                        <path d="M14.192 15.573s1.773-.135 1.773-2.256c0-2.12-1.203-3.165-3.041-3.165l-6.297-.01v11.715h6.443s3.222.102 3.222-3.514c.001 0 .238-2.77-2.1-2.77zM8.971 11.9h3.222s1.102.018 1.102 1.447c0 1.481-1.102 1.481-1.102 1.481H8.971V11.9zm3.515 8.2H8.971v-3.514h3.514s1.757.018 1.757 1.757-1.503 1.757-1.756 1.757zM21.272 13.071c-4.401 0-4.393 4.393-4.393 4.393s-.293 4.393 4.393 4.393c0 0 4.1 0 4.1-3.514h-2.343s0 1.757-1.757 1.757c0 0-1.757 0-1.757-2.343h5.857c0-1.171 0-4.686-4.1-4.686zm-1.758 3.515s-.039-1.757 1.757-1.757c1.795 0 1.757 1.757 1.757 1.757h-3.514zM18.929 11.314h4.686v1.171h-4.686z" fill="none" stroke-width="0.8" stroke="#235af0" class="behance"></path>
                    </svg>
                </a>
            </li>
            <li>
                <a href="">
                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="twitter" class="svg-logo">
                        <path d="M22.5,3.5888672c0.0002441-0.2761841-0.2234497-0.5002441-0.4996338-0.5004883c-0.0898438-0.000061-0.1779785,0.0240479-0.255249,0.0698242c-0.7038574,0.4194946-1.4684448,0.7275391-2.2666016,0.9130859c-0.8716431-0.8326416-2.0318604-1.2953491-3.2373047-1.2910156c-2.6089478,0.0032349-4.7229004,2.1176758-4.7255859,4.7265625c0,0.1347656,0.0058594,0.2714844,0.0185547,0.4091797C8.5112915,7.5945435,5.7377319,6.09198,3.8173828,3.7353516c-0.175354-0.2139282-0.4909668-0.2451782-0.704895-0.0698242c-0.0467529,0.0383301-0.0861816,0.0848389-0.116394,0.137207C2.581604,4.5263672,2.3637695,5.3458252,2.3642578,6.1797485C2.362915,7.0950928,2.6275635,7.9910889,3.1259766,8.7587891c-0.0195312-0.0107422-0.039978-0.0214844-0.0595703-0.0332031c-0.1596069-0.0778809-0.3484497-0.065979-0.4970703,0.03125c-0.1546631,0.1004028-0.2477417,0.272583-0.2470703,0.4570312C2.3181152,9.3320923,2.3253174,9.4505005,2.34375,9.5673218c0.0957642,1.3353882,0.7573853,2.5662842,1.8183594,3.3828735c-0.0925903,0.0269165-0.1751709,0.0805664-0.2373047,0.1542969c-0.1132812,0.1314087-0.1502686,0.3121948-0.0976562,0.4775391c0.468811,1.461731,1.62146,2.6026611,3.0878296,3.0566406c-1.4753418,0.8259277-3.1745605,1.1630249-4.8535156,0.9628906c-0.274231-0.0336304-0.5238037,0.161438-0.5574341,0.4356689c-0.0233154,0.1904297,0.06427,0.3773804,0.2254639,0.4813232C3.7386475,19.812439,6.0780029,20.5003662,8.4677734,20.5c5.5748291,0.0617676,10.4938965-3.6341553,11.9863281-9.0058594c0.3389282-1.1375122,0.5119019-2.3179321,0.5136719-3.5048828c0-0.1201172,0-0.2451172-0.0029297-0.3720703C22.0166626,6.5477295,22.5733032,5.0870972,22.5,3.5888672z M20.0761719,7.1220703c-0.0820923,0.0969849-0.1240234,0.2217407-0.1171875,0.3486328c0.0087891,0.1767578,0.0087891,0.3525391,0.0087891,0.5185547c-0.0020142,1.0913086-0.1611938,2.1766968-0.4726562,3.2226562C18.1668701,16.1845703,13.6137085,19.6067505,8.4677734,19.5c-1.5258789,0.0005493-3.036377-0.3045654-4.4423828-0.8974609c1.6526489-0.1833496,3.220459-0.8276367,4.5244141-1.859375c0.2172241-0.1707764,0.2548828-0.4852905,0.0841064-0.7025146C8.5411377,15.9225464,8.4001465,15.852417,8.25,15.8496094c-1.3014526-0.0209351-2.4966431-0.7225342-3.1494141-1.8486328c0.4240723,0.0012817,0.8461914-0.057251,1.2539062-0.1738281c0.2652588-0.0761719,0.4185181-0.3529053,0.3423462-0.6181641C6.6450806,13.0286255,6.4966431,12.8924561,6.3125,12.8564453c-1.463562-0.2926636-2.6086426-1.4346924-2.9052734-2.8974609c0.4245605,0.1375122,0.8664551,0.2141113,1.3125,0.2275391c0.2264404,0.0168457,0.4329224-0.1294556,0.4921875-0.3486328C5.2793579,9.625,5.1976929,9.3931885,5.0117188,9.2695312C3.9785156,8.581604,3.3596802,7.4209595,3.3642578,6.1796875C3.3639526,5.7672119,3.4312134,5.3574829,3.5634766,4.9667969C5.7807617,7.361084,8.84552,8.7946777,12.1044922,8.9619141c0.1580811,0.0167236,0.3132935-0.0512085,0.4082031-0.1787109c0.1005859-0.1207275,0.138855-0.2814941,0.1035156-0.4345703c-0.0661621-0.2757568-0.0999756-0.5582275-0.1005859-0.8417969c0.0019531-2.0569458,1.6686401-3.7240601,3.7255859-3.7265625c1.0283203-0.0029297,2.0109863,0.4244995,2.710022,1.1787109c0.1178589,0.1260986,0.2926025,0.182251,0.4619141,0.1484375c0.7096558-0.1395874,1.3995972-0.3652344,2.0546265-0.671875C21.2976074,5.4550171,20.81073,6.3949585,20.0761719,7.1220703z" stroke="#235af0" fill="#235af0" stroke-width="0" class="twitter"></path>
                    </svg>
                </a>
            </li>
        </ul>
    </aside>