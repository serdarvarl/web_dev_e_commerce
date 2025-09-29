<?php
$host = '127.0.0.1';
$port = '8889';
$db   = 'miel';
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM Articles WHERE id_art = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($product['nom']); ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
  <h1><?php echo htmlspecialchars($product['nom']); ?></h1>
</header>

<div class="product_page">
  <div class="product_unique">
    <img src="<?php echo htmlspecialchars($product['url_photo']); ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>">
    <p><strong>Prix:</strong> <?php echo $product['prix']; ?> €</p>
    <p><strong>Stock:</strong> <?php echo $product['quantite']; ?></p>
    <button>Ajouter au panier</button>
  </div>
  <div class="description">
    <p><?php echo htmlspecialchars($product['description']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($product['details'] ?? '')); ?></p>
  </div>
</div>

</body>
</html>
