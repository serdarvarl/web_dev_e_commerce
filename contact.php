<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title></title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>

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
  <!-- Contact Form -->


  <section id="contact_form">
    <div class="container">
      <h2>Contactez-nous</h2>
      <form action="process_contact.php" method="post">
        <div class="form_group">
          <label for="name">Nom:</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form_group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form_group">
          <label for="message">Message:</label>
          <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <div class="form_group">
          <button type="submit">Envoyer</button>
        </div>
      </form>
    </div>
  </section>








  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2024 Miel. All rights reserved.</p>
    <nav class="bottom_menu">
      <ul>
        <li><a href="index.html">Miel</a></li>
        <li><a href="propolis.html">Propolis</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="blog.html">Blog</a></li>
      </ul>
    </nav>
  </footer>

</body>

</html>