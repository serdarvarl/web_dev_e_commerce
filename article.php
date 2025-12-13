<?php
//appel de bd
require_once 'db.php';

// url Id plus secu
$id = filter_input(INPUT_GET, 'id_art', FILTER_VALIDATE_INT, ['options' => ['default' => 0, 'min_range' => 1]]);

//  connection 
$pdo = getBD();

//prendre de produit avec statemet
$sql = "SELECT * FROM Articles WHERE id_art = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
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
      <!--update button ajouter -->
      <form action="ajouter.php" method="post"> 
    <input type="hidden" name="id_art" value="<?php echo $product['id_art']; ?>">

    <label for="qte">Quantité :</label>
    <input type="number" id="qte" name="quantite" value="1" min="1" style="width: 50px;">

    <button type="submit" class="add-to-cart">Ajouter au panier</button>
</form>
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

