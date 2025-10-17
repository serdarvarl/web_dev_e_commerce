<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>


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




  <h1>Connexion</h1>





  <?php if (!empty($_GET['err'])): ?>
    <p style="color:#b00;"><?= htmlspecialchars($_GET['err']) ?></p>
  <?php endif; ?>

  <form action="connecter.php" method="post" class="client_page">
    <label>Adresse e-mail</label>
    <input type="email" name="mail" required>

    <label>Mot de passe</label>
    <input type="password" name="mdp" required>

    <button type="submit">Se connecter</button>
  </form>

  <p><a href="nouveau.php">Nouveau client ?</a></p>
</body>
</html>
