<?php
function getBD() {
    $host = '127.0.0.1';
    $port = '8889';     // MAMP portu
    $db   = 'miel';     // veritabanı adın
    $user = 'root';
    $pass = 'root';

    // PDO kullanımı (TP3'ün önerdiği gibi)
    try {
        $bdd = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
