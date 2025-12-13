<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$pdo = getBD();

// envoyer msg
if (isset($_POST['action']) && $_POST['action'] == 'send') {
    // chehck se connecter
    if (!isset($_SESSION['client'])) { 
        echo json_encode(['status'=>'error', 'msg'=>'Veuillez vous connecter.']); 
        exit; 
    }
    
    $msg = trim($_POST['message'] ?? '');
    $user = $_SESSION['client']['prenom'];

    // taille carancter
    if (strlen($msg) > 256 || empty($msg)) {
        echo json_encode(['status'=>'error', 'msg'=>'Message invalide (Max 256 caractères)']); 
        exit;
    }

    // ====================================================
    // Filteraige AI
    // ====================================================
    $jsonFile = __DIR__ . '/score_map.json'; #on a creere avvec scrore.py
    
    if (file_exists($jsonFile)) {
        // lire json
        $scoreMap = json_decode(file_get_contents($jsonFile), true);
        
        // nettoyahge 
        $cleanMsg = strtolower(preg_replace('/[^\w\s]/u', '', $msg));
        $words = explode(' ', $cleanMsg);
        
        $totalScore = 0;
        
        // calcule point trosss primitiffff
        foreach ($words as $word) {
            if (isset($scoreMap[$word])) {
                $totalScore += $scoreMap[$word];
            }
        }
        
        // si moin de -0.01 Hate speeche
        if ($totalScore < -0.01) {
            echo json_encode([
                'status' => 'error', 
                'msg' => '⚠️ Message bloqué : Contenu inapproprié détecté ! (AI Score: ' . number_format($totalScore, 4) . ')'
            ]);
            exit;
        }
    }
    // ====================================================

    // si passe enregistre 
    $stmt = $pdo->prepare("INSERT INTO Chat (nom_user, message) VALUES (?, ?)");
    $stmt->execute([$user, $msg]);
    
    echo json_encode(['status'=>'success']);
    exit;
}

// 10 min 
if (isset($_GET['action']) && $_GET['action'] == 'load') {
   
    $pdo->query("DELETE FROM Chat WHERE date_envoi < (NOW() - INTERVAL 10 MINUTE)");

    
    $stmt = $pdo->query("SELECT * FROM Chat ORDER BY date_envoi ASC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
?>