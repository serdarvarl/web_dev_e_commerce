<?php
    require_once 'db.php'; // db.php, $conn oluşturur
    $conn= getBD();

$sql = "SELECT id_art, nom, quantite, prix, url_photo, description FROM Articles";
$result = $conn->query($sql);


// Sorguları kullandıktan sonra kapatabilirsin:
$conn = null;
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
                    <li><a href="panier.php">Panier</a></li>
                    <li><a href="nouveau.php">Nouveau client ?</a></li>
                </ul>
            </div>
        </div>
    </header>



    <!--Dynamic Product List from Database -->
    <div id="product-list-container">
        <?php
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="product-item">';
                echo '<a href="article.php?id=' . $row['id_art'] . '">';
                echo '<h2 class="product_name">' . htmlspecialchars($row['nom']) . '</h2>';
                echo '<img src="' . htmlspecialchars($row['url_photo']) . '" alt="' . htmlspecialchars($row['nom']) . '">';
                echo '</a>';
                echo '<p class="product_description">' . htmlspecialchars($row['description']) . '</p>';
                echo '<p class="product_price">Prix: ' . htmlspecialchars($row['prix']) . ' €</p>';
                echo '<button class="add-to-cart">Ajouter au panier</button>';
                echo '</div>';
            }
        } else {
            echo "<p>Aucun produit trouvé.</p>";
        }
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