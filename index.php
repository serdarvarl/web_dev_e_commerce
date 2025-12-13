<?php
session_start();
require_once __DIR__ . '/db.php'; // connection db

try {
    $pdo = getBD();
    //une seul fois prendre les artice
    $sql = "SELECT * FROM Articles";
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur Database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Miel - Accueil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

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
                <a href="index.php"><img src="images/logo.png" alt="logo"></a>
            </div>
            <div class="header_droite">
                <ul>
                    <li><input type="text" placeholder="Rechercher"></li>
                    <?php if (!isset($_SESSION['client'])): ?>
                        <li><a href="nouveau.php">Nouveau client ?</a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                    <?php else: ?>
                        <li>Bonjour, <?= htmlspecialchars($_SESSION['client']['prenom']) ?></li>
                        <li><a href="panier.php">Panier</a></li>
                        <li><a href="deconnexion.php">Se déconnecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <div id="product-list-container">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $row): ?>
                <div class="product-item">
                    <a href="article.php?id_art=<?= htmlspecialchars($row['id_art']) ?>">
                        <h2 class="product_name"><?= htmlspecialchars($row['nom']) ?></h2>
                        <img src="<?= htmlspecialchars($row['url_photo']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
                    </a>
                    <p class="product_description"><?= htmlspecialchars($row['description']) ?></p>
                    <p class="product_price">Prix: <?= htmlspecialchars($row['prix']) ?> €</p>

                    <?php if (isset($_SESSION['client'])): ?>
                        <form action="ajouter.php" method="post" style="margin-top:8px;">
                            <input type="hidden" name="id_art" value="<?= $row['id_art'] ?>">
                            <label>Quantité</label>
                            <input type="number" name="quantite" min="1" value="1" required style="width:50px;">
                            <button type="submit">Ajouter</button>
                        </form>
                    <?php else: ?>
                        <p><a href="connexion.php" style="color:red;">Connectez-vous pour acheter</a></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Miel. All rights reserved.</p>
    </footer>

</body>
</html>