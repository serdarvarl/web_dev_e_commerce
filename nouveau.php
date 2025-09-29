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



$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nouveau client</title>
  <link rel="stylesheet" href="styles.css">
  <style>
   

  </style>
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





<div class="client_page">
<h1>Formulaire Nouveau Client</h1>
  <p>Veuillez remplir le formulaire ci-dessous pour créer un nouveau compte client.</p>

  <!-- Formulaire d'inscription -->

<form action="enregistrement.php" method="post"> /* utiliser post pour exo 2 de TD4 */
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



</div>



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
