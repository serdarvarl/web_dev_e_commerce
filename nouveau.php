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
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// de base de données
$sql = "SELECT * FROM Articles WHERE id_art = $id LIMIT 1";
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
  <header id="header">
    <div class="container_header">
      <div id="top_menu">
        <ul>
          <li><a href="index.php">Miel</a></li>  
          <li><a href="propolis.html">Propolis</a></li>
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

<!--enregister client page -->

<h1 id="client_page">Formulaire Nouveau Client</h1>



<form action="enregistrement.php" method="get">
    <label>Nom :</label>
    <input type="text" name="n" required><br><br>

    <label>Prénom :</label>
    <input type="text" name="p" required><br><br>

    <label>Adresse :</label>
    <input type="text" name="adr"><br><br>

    <label>Numéro de téléphone :</label>
    <input type="text" name="num"><br><br>

    <label>Adresse e-mail :</label>
    <input type="email" name="mail" required><br><br>

    <label>Mot de passe :</label>
    <input type="password" name="mdp1" required><br><br>

    <label>Confirmer votre mot de passe :</label>
    <input type="password" name="mdp2" required><br><br>

    <button type="submit">S’inscrire</button>
  </form>






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
