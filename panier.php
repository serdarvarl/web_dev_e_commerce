<?php
session_start();
require_once __DIR__ . '/db.php';

// verifierr se connetteee
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php?err=Veuillez vous connecter pour voir votre panier');
    exit;
}

// 2️⃣ verifierr si panier vide
if (!isset($_SESSION['panier']) || count($_SESSION['panier']) === 0) {
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Panier vide</title>';
    echo '<link rel="stylesheet" href="styles.css"></head><body>';
    echo '<h2>Votre panier ne contient aucun article.</h2>';
    echo '<p><a href="index.php">← Retour à la boutique</a></p>';
    echo '</body></html>';
    exit;
}

// 3️⃣ predns detail de produits
$pdo = getBD();

// somme des id_art prix
$idList = array_column($_SESSION['panier'], 'id_art');
$placeholders = implode(',', array_fill(0, count($idList), '?'));

$sql = "SELECT id_art, nom, prix FROM Articles WHERE id_art IN ($placeholders)";
$stmt = $pdo->prepare($sql);
$stmt->execute($idList);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// avec id art 
$produits = [];
foreach ($articles as $a) {
    $produits[$a['id_art']] = $a;
}

// totall
$total = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Votre Panier</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<h1>Votre panier</h1>

<table border="1" cellpadding="10" cellspacing="0" style="margin:auto; border-collapse:collapse;">
  <tr style="background:#f2f2f2;">
    <th>ID</th>
    <th>Nom</th>
    <th>Prix unitaire (€)</th>
    <th>Quantité</th>
    <th>Total (€)</th>
  </tr>

  <?php foreach ($_SESSION['panier'] as $item): 
    $id = $item['id_art'];
    $qte = (int)$item['quantite'];
    $nom = htmlspecialchars($produits[$id]['nom'] ?? 'Inconnu');
    $prix = (float)($produits[$id]['prix'] ?? 0);
    $sousTotal = $prix * $qte;
    $total += $sousTotal;
  ?>
  <tr>
    <td><?= $id ?></td>
    <td><?= $nom ?></td>
    <td><?= number_format($prix, 2, ',', ' ') ?></td>
    <td><?= $qte ?></td>
    <td><?= number_format($sousTotal, 2, ',', ' ') ?></td>
  </tr>
  <?php endforeach; ?>

  <tr style="font-weight:bold;">
    <td colspan="4" align="right">Montant total :</td>
    <td><?= number_format($total, 2, ',', ' ') ?> €</td>
  </tr>
</table>

<p style="text-align:center; margin-top:20px;">
  <a href="index.php">← Retour à la boutique</a>
</p>

</body>
</html>
