<?php
//connection bdonee
require_once 'db.php';

if (isset($_POST['mail'])) { 
    $mail = trim($_POST['mail']);
    $pdo = getBD();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Clients WHERE mail = :mail"); // sql pour mail compter
    $stmt->execute([':mail' => $mail]);
    
    // si >0  donc exist 
    if ($stmt->fetchColumn() > 0) {
        echo "exists";
    } else {
        echo "ok";
    }
}
?>