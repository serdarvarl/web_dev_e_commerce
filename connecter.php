<?php
session_start();
require_once __DIR__ . '/db.php';

$mail = trim($_POST['mail'] ?? '');
$mdp  = $_POST['mdp'] ?? '';

if ($mail === '' || $mdp === '') {
    header('Location: connexion.php?err=Champs manquants');
    exit;
}

try {
    $pdo = getBD();
    $sql = "SELECT * FROM Clients WHERE mail = :mail";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':mail' => $mail]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client && password_verify($mdp, $client['mdp'])) {
        //si coorect, creer session
        $_SESSION['client'] = [
            'id_client' => $client['id_client'],
            'nom'       => $client['nom'],
            'prenom'    => $client['prenom'],
            'mail'      => $client['mail']
        ];
        header('Location: index.php');
    } else {
        header('Location: connexion.php?err=E-mail ou mot de passe incorrect');
    }
} catch (PDOException $e) {
    header('Location: connexion.php?err=Erreur serveur');
}
exit;
