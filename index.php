<?php
session_start();
require_once __DIR__ . '/db.php'; // yol güvenli
$conn = getBD();

$sql = "SELECT id_art, nom, quantite, prix, url_photo, description FROM Articles";
$stmt = $conn->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $conn = null;  // BUNU en sona taşıyacağız
?>

<!DOCTYPE html>
<html lang="fr">



<head>
    <meta charset="UTF-8">
    <title>Miel</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>


    <!-- Header -->

    <header id="header">
        <div class="container_header">
            <div id="top_menu">
                <ul class="menu">
                    <li><a href="index.php">Miel</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>

            </div>
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="logo">

                </a>
            </div>
            <div class="header_droite">
                <ul>
                    <li><input type="text" placeholder="Rechercher"></li>

                    <?php if (!isset($_SESSION['client'])): ?>
                        <li><a href="nouveau.php">Nouveau client ?</a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                    <?php else: ?>
                        <li>
                            Bonjour
                            <?= htmlspecialchars($_SESSION['client']['prenom']) ?>
                            <?= htmlspecialchars($_SESSION['client']['nom']) ?>
                        </li>
                        <li><a href="panier.php">Panier</a></li>
                        <li><a href="deconnexion.php">Se déconnecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>
    </header>



    <!--Dynamic Product List from Database -->
    <!-- Dynamic Product List from Database -->
    <div id="product-list-container">
        <?php
        // get depuis  data from database
        $conn = getBD();
        $sql = "SELECT id_art, nom, quantite, prix, url_photo, description FROM Articles";
        $result = $conn->query($sql);

        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                echo '<div class="product-item">';
                echo '<a href="article.php?id_art=' . htmlspecialchars($row['id_art']) . '">';
                echo '<h2 class="product_name">' . htmlspecialchars($row['nom']) . '</h2>';
                echo '<img src="' . htmlspecialchars($row['url_photo']) . '" alt="' . htmlspecialchars($row['nom']) . '">';
                echo '</a>';
                echo '<p class="product_description">' . htmlspecialchars($row['description']) . '</p>';
                echo '<p class="product_price">Prix: ' . htmlspecialchars($row['prix']) . ' €</p>';

                //si client connecte, afficher form ajouter au panier
                if (isset($_SESSION['client'])) {
                    echo '<form action="ajouter.php" method="post" style="margin-top:8px;">';
                    echo '<input type="hidden" name="id_art" value="' . htmlspecialchars($row['id_art']) . '">';
                    echo '<label>Quantité</label>';
                    echo '<input type="number" name="quantite" min="1" value="1" required>';
                    echo '<input type="submit" value="Ajouter à votre panier">';
                    echo '</form>';
                } else {
                    echo '<p style="margin-top:8px;"><a href="connexion.php" class="add-to-cart">Se connecter pour ajouter au panier</a></p>';
                }

                echo '</div>';
            }
        } else {
            echo "<p>Aucun produit trouvé.</p>";
        }

        $conn = null;
        ?>
    </div>





    <!-- Footer  pas encore fini -->
    <div class="footer">
        <p>&copy; 2024 Miel. All rights reserved.</p>
        <div class="bottom_menu">
            <ul>
                <li><a href="index.html">Miel</a></li>
                <li>Propolis</li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="propolis.html">Propolis</a></li>
                <li><a href="blog.php">Blog</a></li>
            </ul>
        </div>
    </div>

</body>

</html>