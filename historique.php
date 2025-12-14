<?php
session_start();
require_once 'db.php';

// check se connecter
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php');
    exit;
}

$pdo = getBD();
$id_client = $_SESSION['client']['id_client'];

// verifier commandé (TP 6 - Exercice 4)
$sql = "SELECT c.id_commande, c.date_commande, c.quantite, a.nom, a.prix, a.url_photo 
        FROM Commandes c
        JOIN Articles a ON c.id_art = a.id_art
        WHERE c.id_client = :id
        ORDER BY c.date_commande DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_client]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des commandes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .history-table { width: 80%; margin: 30px auto; border-collapse: collapse; }
        .history-table th, .history-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .history-table th { background-color: #f2f2f2; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; }
    </style>
</head>
<body>

    <header id="header">
        <div class="container_header">
            <div class="logo"><a href="index.php"><img src="images/logo.png" alt="logo"></a></div>
            <div class="header_droite">
                <ul>
                    <li>Bonjour, <?= htmlspecialchars($_SESSION['client']['prenom']) ?></li>
                    <li><a href="panier.php">Panier</a></li>
                    <li><a href="index.php">Retour Accueil</a></li>
                    <li><a href="deconnexion.php">Se déconnecter</a></li>
                </ul>
            </div>
        </div>
    </header>

    <h1 style="text-align:center;">Mes Commandes</h1>

    <?php if (count($commandes) > 0): ?>
        <table class="history-table">
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Image</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
            <?php foreach ($commandes as $cmd): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($cmd['date_commande'])) ?></td>
                    <td><?= htmlspecialchars($cmd['nom']) ?></td>
                    <td><img src="<?= htmlspecialchars($cmd['url_photo']) ?>" class="product-thumb"></td>
                    <td><?= number_format($cmd['prix'], 2) ?> €</td>
                    <td><?= $cmd['quantite'] ?></td>
                    <td><?= number_format($cmd['prix'] * $cmd['quantite'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Vous n'avez pas encore passé de commande.</p>
    <?php endif; ?>

</body>
</html>