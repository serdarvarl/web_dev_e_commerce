<?php
$host = '127.0.0.1';
$port = '8889';   // MAMP port
$db   = 'miel';
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("connection errorr: " . $conn->connect_error);
}

// URL de id
$id = isset($_GET['id_art']) ? (int)$_GET['id_art'] : 0;   // filter_input()



// 
$id = filter_input(INPUT_GET, 'id_art', FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 1]]);

//

// de base de données
$sql = "SELECT * FROM Articles WHERE id_art = $id LIMIT 1";//prepared statement 
$result = $conn->query($sql);
$product = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?php echo $product ? htmlspecialchars($product['nom']) : 'Produit introuvable'; ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <!-- Header -->
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

<!--arctilce page -->




  <?php if ($product): ?>
  <div class="product_page">
    <div class="product_unique">
      <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
      <img src="<?php echo htmlspecialchars($product['url_photo']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
      <p class="product_description"><?php echo htmlspecialchars($product['description']); ?></p>
      <p class="product_price">Prix: <?php echo htmlspecialchars($product['prix']); ?> €</p>
      <p><strong>Stock:</strong> <?php echo htmlspecialchars($product['quantite']); ?></p>
      <button class="add-to-cart">Ajouter au panier</button>
    </div>
    <div class="description">
      <p class="product_description"><?php echo htmlspecialchars($product['description']); ?></p>
    </div>
  </div>
  <?php else: ?>
  <div class="product_page">
    <p>Produit introuvable.</p>
  </div>
  <?php endif; ?>





  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2024 Miel. All rights reserved.</p>
    <nav class="bottom_menu">
      <ul>
        <li><a href="index.php">Miel</a></li>
        <li><a href="propolis.html">Propolis</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="blog.html">Blog</a></li>
      </ul>
    </nav>
  </footer>

</body>
</html>
