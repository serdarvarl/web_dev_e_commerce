<?php
session_start();
require_once 'db.php';

//si paneier est vide ou le client n'est pa la redirection a main page
if (!isset($_SESSION['client']) || !isset($_SESSION['panier']) || count($_SESSION['panier']) === 0) {
    header('Location: index.php');
    exit;
}

$pdo = getBD();
$id_client = $_SESSION['client']['id_client'];

try {
    //start or rest
    $pdo->beginTransaction(); # si y a coupeur ssi.   // pense utiliser webhook stripr -> website -> stripe 

    foreach ($_SESSION['panier'] as $item) {
        $id_art = $item['id_art'];
        $quantite = $item['quantite'];

        //resgiter commande
        $sqlInsert = "INSERT INTO Commandes (id_art, id_client, quantite, envoi) VALUES (:id_art, :id_client, :quantite, 0)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':id_art' => $id_art,
            ':id_client' => $id_client,
            ':quantite' => $quantite
        ]);

        // dimunier dans stock
        $sqlUpdate = "UPDATE Articles SET quantite = quantite - :quantite WHERE id_art = :id_art";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':quantite' => $quantite,
            ':id_art' => $id_art
        ]);
    }

    // apreove 
    $pdo->commit();

    //videee le panier
    unset($_SESSION['panier']);

} catch (Exception $e) {
    $pdo->rollBack();
    die("Il y a un erreur: " . $e->getMessage());
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