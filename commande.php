<?php
session_start();
require_once __DIR__ . '/db.php';

// check client se connecter
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php?err=Veuillez vous connecter pour passer commande');
    exit;
}

// check panier est vide ???
if (!isset($_SESSION['panier']) || count($_SESSION['panier']) === 0) {
    header('Location: index.php?err=Votre panier est vide');
    exit;
}

$pdo = getBD();

// prender les data de client adress etc
// utiliser id dans la sssion 
$id_client = $_SESSION['client']['id_client'];
$sqlClient = "SELECT nom, prenom, adresse, numero, mail FROM Clients WHERE id_client = :id";
$stmtClient = $pdo->prepare($sqlClient);
$stmtClient->execute([':id' => $id_client]);
$clientInfo = $stmtClient->fetch(PDO::FETCH_ASSOC);

if (!$clientInfo) {
    die("Erreur : Impossible de récupérer les informations du client.");
}

// calcule panier encore ID article
$idList = array_column($_SESSION['panier'], 'id_art');
$placeholders = implode(',', array_fill(0, count($idList), '?'));

$sqlPanier = "SELECT id_art, nom, prix FROM Articles WHERE id_art IN ($placeholders)";
$stmtPanier = $pdo->prepare($sqlPanier);
$stmtPanier->execute($idList);
$articlesDB = $stmtPanier->fetchAll(PDO::FETCH_ASSOC);

// trie le list selon id creee new list
$produits = [];
foreach ($articlesDB as $p) {
    $produits[$p['id_art']] = $p;
}

$totalCommande = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif de la commande</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .commande-container { max-width: 800px; margin: 30px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .address-box { background: #f9f9f9; padding: 15px; border-left: 5px solid #2196F3; margin-bottom: 20px; }
        .total-box { text-align: right; font-size: 1.2em; font-weight: bold; margin-top: 20px; }
        .stripe-btn { background-color: #6772e5; color: white; padding: 12px 24px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .stripe-btn:hover { background-color: #5469d4; }
    </style>
</head>
<body>

<header id="header">
    <div class="container_header">
        <div class="logo">
            <a href="index.php"><img src="images/logo.png" alt="logo" height="50"></a>
        </div>
        <div class="header_droite">
            <ul>
                <li>Bonjour <?= htmlspecialchars($_SESSION['client']['prenom']) ?></li>
                <li><a href="panier.php">Panier</a></li>
                <li><a href="deconnexion.php">Se déconnecter</a></li>
            </ul>
        </div>
    </div>
</header>

<div class="commande-container">
    <h1>Récapitulatif de votre commande</h1>

    <div class="address-box">
        <h3>Adresse de livraison :</h3>
        <p><strong><?= htmlspecialchars($clientInfo['prenom'] . ' ' . $clientInfo['nom']) ?></strong></p>
        <p><?= htmlspecialchars($clientInfo['adresse']) ?> </p>
        <p>Tél : <?= htmlspecialchars($clientInfo['numero']) ?></p>
        <p>Email : <?= htmlspecialchars($clientInfo['mail']) ?></p>
    </div>

    <h3>Articles :</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tr style="background:#eee;">
            <th>Produit</th>
            <th>Prix Unit.</th>
            <th>Quantité</th>
            <th>Total</th>
        </tr>
        <?php foreach ($_SESSION['panier'] as $item): 
            $id = $item['id_art'];
            if (!isset($produits[$id])) continue; // si  le prodct suprimee

            $nom = $produits[$id]['nom'];
            $prix = $produits[$id]['prix'];
            $qty = $item['quantite'];
            $subtotal = $prix * $qty;
            $totalCommande += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($nom) ?></td>
            <td><?= number_format($prix, 2) ?> €</td>
            <td><?= $qty ?></td>
            <td><?= number_format($subtotal, 2) ?> €</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total-box">
        Montant Total à Payer : <?= number_format($totalCommande, 2) ?> € 
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <form action="paiement.php" method="POST">
            <button type="submit" class="stripe-btn">
                💳 Payer avec Stripe (<?= number_format($totalCommande, 2) ?> €)
            </button>
        </form>
        <br>
        <a href="panier.php" style="color: #666;">← Retour au panier</a>
    </div>

</div>

<footer class="footer">
    <p>&copy; 2024 Miel. All rights reserved.</p>
</footer>

</body>
</html>