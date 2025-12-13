<?php
// register_ajax.php
require_once 'db.php';
header('Content-Type: application/json'); // jsonn convertir
try {
    $pdo = getBD();
    
    // prendre donnes
    $nom = trim($_POST['n']);
    $prenom = trim($_POST['p']);
    $mail = trim($_POST['mail']);
    $mdp = $_POST['mdp1'];
    
    // verifir manque valeur
    if (empty($mail) || empty($mdp)) {
        echo json_encode(['status' => 'error', 'message' => 'Les espaces ne peuvent pas être vides.']);
        exit;
    }

    //double verifier pour mail
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Clients WHERE mail = :mail");
    $stmt->execute([':mail' => $mail]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 'error', 'message' => "L'adresse e-mail est déjà enregistrée."]);
        exit;
    }

    // registerrr
    $hash = password_hash($mdp, PASSWORD_BCRYPT);
    $sql = "INSERT INTO Clients (nom, prenom, adresse, numero, mail, mdp) 
            VALUES (:n, :p, :adr, :num, :mail, :mdp)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':n' => $nom,
        ':p' => $prenom,
        ':adr' => $_POST['adr'] ?? '',
        ':num' => $_POST['num'] ?? '',
        ':mail' => $mail,
        ':mdp' => $hash
    ]);

    // start session suppos que cient se connecter
    session_start();
    $_SESSION['client'] = [
        'id_client' => $pdo->lastInsertId(),
        'nom' => $nom,
        'prenom' => $prenom,
        'mail' => $mail
    ];

    echo json_encode(['status' => 'success', 'message' => 'Inscription réussie ! Vous allez être redirigé(e)...']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>