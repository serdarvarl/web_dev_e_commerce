<?php
session_start();
require_once 'db.php';

// Güvenlik: Sepet boşsa veya müşteri yoksa anasayfaya at
if (!isset($_SESSION['client']) || !isset($_SESSION['panier']) || count($_SESSION['panier']) === 0) {
    header('Location: index.php');
    exit;
}

$pdo = getBD();
$id_client = $_SESSION['client']['id_client'];

try {
    // Transaction başlat (Tüm işlemler ya hep yapılır ya hiç yapılmaz)
    $pdo->beginTransaction();

    foreach ($_SESSION['panier'] as $item) {
        $id_art = $item['id_art'];
        $quantite = $item['quantite'];

        // 1. Siparişi Kaydet
        $sqlInsert = "INSERT INTO Commandes (id_art, id_client, quantite, envoi) VALUES (:id_art, :id_client, :quantite, 0)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':id_art' => $id_art,
            ':id_client' => $id_client,
            ':quantite' => $quantite
        ]);

        // 2. Stoktan Düş
        $sqlUpdate = "UPDATE Articles SET quantite = quantite - :quantite WHERE id_art = :id_art";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':quantite' => $quantite,
            ':id_art' => $id_art
        ]);
    }

    // İşlemleri onayla
    $pdo->commit();

    // 3. Sepeti Boşalt (TP 6 - Egzersiz 3)
    unset($_SESSION['panier']);

} catch (Exception $e) {
    $pdo->rollBack();
    die("Bir hata oluştu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande Validée</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-box { text-align: center; padding: 50px; }
        .success-icon { font-size: 50px; color: green; }
    </style>
</head>
<body>

    <header id="header">
        <div class="container_header">
            <div class="logo"><a href="index.php"><img src="images/logo.png" alt="logo"></a></div>
            <div class="header_droite"><ul><li><a href="index.php">Accueil</a></li></ul></div>
        </div>
    </header>

    <div class="success-box">
        <div class="success-icon">✅</div>
        <h1>Merci pour votre commande !</h1>
        <p>Votre paiement a été accepté et votre commande a bien été enregistrée.</p>
        <p>Nous allons préparer votre colis au plus vite.</p>
        <br>
        <a href="index.php" class="btn">Retour à la boutique</a>
    </div>

</body>
</html>