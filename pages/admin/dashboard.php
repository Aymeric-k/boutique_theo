<?php
session_start();
$css = "dashboard";
$title = "Dashboard";
if (!isset($_SESSION['iwannaenterplsletmeinimreiz'])) {
    header('Location: /index.php');
    exit;
}
include $_SERVER['DOCUMENT_ROOT'].'/pages/header/header-admin.php';




?>

    <main>
        <section>
            <ul>
                <li>
                    <a href="/pages/admin/crud/produits.php"><button>Produits</button></a>
                </li>
                <li>
                    <a href="/pages/admin/crud/commandes.php"><button>Commandes</button></a>
                </li>
                <li>
                    <a href="/pages/admin/crud/categories.php"><button>Catégories</button></a>
                </li>
                <li>
                    <a href="/pages/admin/crud/pays.php"><button>Pays/Livraison</button></a>
                </li>
                <li>
                    <a href="/pages/admin/crud/statistique.php"><button>Statistiques</button></a>
                </li>
                <li>
                    <a href="/pages/admin/crud/code_promos.php"><button>Code Promos</button></a>
                </li>
            </ul>
        </section>
    </main>



