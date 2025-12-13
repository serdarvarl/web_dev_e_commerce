<?php
function getBD() {
    $host = '127.0.0.1';
    $port = '8889';     // MAMP port
    $db   = 'miel';     // nom DV
    $user = 'root';
    $pass = 'root';

    // PDO TP3
    try {
        $bdd = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
