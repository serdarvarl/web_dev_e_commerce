<?php
$host = '127.0.0.1';
$port = '8889';   // MAMP MySQL port
$db   = 'miel';   // 
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("connection errure: " . $conn->connect_error);
}

$sql = "SELECT id_art, nom, quantite, prix, url_photo, description FROM Articles";

$result = $conn->query($sql);


$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Miel</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="test.css"> <!--unutma !!!! -->
</head>

<body>
<!--php -->


<!--php -->
    <header id="header">
        <div class="container_header">
            <div id="top_menu">
                <ul>
                    <li><a href="index.php">Miel</a></li>
                    <li><a href="blog.html">Blog</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="nouveau.php">Nouveau client ?</a></li>
                </ul>

            </div>
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="logo">

                </a>
            </div>
            <div class="header_droite">
                <input type="text" placeholder="Rechercher...">
                <a href="panier.html">Panier</a>
            </div>

        </div>
    </header>




<!--Dynamic Product List from Database -->
    <div id="product-list-container">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="product-item">';
            echo '<a href="article.php?id='.$row['id_art'].'">';
            echo '<h2 class="product_name">'.htmlspecialchars($row['nom']).'</h2>';
            echo '<img src="'.htmlspecialchars($row['url_photo']).'" alt="'.htmlspecialchars($row['nom']).'">';
            echo '</a>';
            echo '<p class="product_description">'.htmlspecialchars($row['description']).'</p>';
            echo '<p class="product_price">Prix: '.htmlspecialchars($row['prix']).' €</p>';
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














<!--  Static Product List Example 
    <div id="product-list-container">


        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_2.html">
                <h2 class="product_name">Miel 2</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_3.html">
                <h2 class="product_name">Miel 3</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>
        <div class="product-item">
            <a href="article_1.html">
                <h2 class="product_name">Miel 1</h2>
                <img src="images/img1.jpg" alt="Miel 1">
            </a>
            <p class="product_description">Description du miel 1</p>
            <p class="product_price">Prix: 10€</p>
            <button class="add-to-cart">Ajouter au panier</button>
        </div>

    </div>
 -->





</body>
</html>