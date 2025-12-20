<?php
session_start();

// check se connecter
if (!isset($_SESSION['client'])) {
  header('Location: connexion.php?err=Veuillez vous connecter pour ajouter au panier');
  exit;
}

// nettoyage
$id_art  = filter_input(INPUT_POST, 'id_art', FILTER_VALIDATE_INT, [
  'options' => ['default' => 0, 'min_range' => 1]
]);
$quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT, [
  'options' => ['default' => 0, 'min_range' => 1]
]);

if ($id_art <= 0 || $quantite <= 0) {
  header('Location: index.php?err=Requête invalide');
  exit;
}

// ============================================================
// stock contorl
// ============================================================
require_once 'db.php'; //concnetion db
$pdo = getBD();

//dernier stock
$stmt = $pdo->prepare("SELECT quantite FROM Articles WHERE id_art = ?");
$stmt->execute([$id_art]);
$stockDispo = (int)$stmt->fetchColumn(); //num

// si le client deja ajouterr
$panierTotal = 0; # changemen nom de variable sepetmiktari -> panierTotal
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        if ((int)$item['id_art'] === $id_art) {
            $panierTotal = (int)$item['quantite'];
            break;
        }
    }
}

// panier + demander > stock
if (($quantite + $panierTotal) > $stockDispo) {
    // insuffisant stock
    $back = 'article.php?id_art=' . $id_art;
    // ererur msg qntt stock
    header('Location: ' . $back . '&err=Stock insuffisant (Max: ' . $stockDispo . ')');
    exit;
}
// ============================================================


//panier session
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
  $_SESSION['panier'] = []; 
}

$found = false;
foreach ($_SESSION['panier'] as &$item) {
  if ((int)$item['id_art'] === $id_art) {
    $item['quantite'] = (int)$item['quantite'] + $quantite;
    $found = true;
    break;
  }
}
unset($item); 

if (!$found) {
  $_SESSION['panier'][] = [
    'id_art'   => $id_art,
    'quantite' => $quantite
  ];
}

//reusir comme back
$back = 'article.php?id_art=' . urlencode($id_art);
header('Location: ' . $back . '&ok=ajoute');
exit;
?>q