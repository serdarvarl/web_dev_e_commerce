<?php
session_start();

// verifier si client est connecte
if (!isset($_SESSION['client'])) {
  // sinon directione page conexion
  header('Location: connexion.php?err=Veuillez vous connecter pour ajouter au panier');
  exit;
}

// Post post verfierrrr
$id_art  = filter_input(INPUT_POST, 'id_art', FILTER_VALIDATE_INT, [
  'options' => ['default' => 0, 'min_range' => 1]
]);
$quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT, [
  'options' => ['default' => 0, 'min_range' => 1]
]);

if ($id_art <= 0 || $quantite <= 0) {
  // si y a errerr revient indexx
  header('Location: index.php?err=Requête invalide');
  exit;
}

// panier sessionnn
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
  $_SESSION['panier'] = []; // idart => intt, quatite => int
}

// 4)si les meme deja aguenterrrr
$found = false;
foreach ($_SESSION['panier'] as &$item) {
  if ((int)$item['id_art'] === $id_art) {
    $item['quantite'] = (int)$item['quantite'] + $quantite;
    $found = true;
    break;
  }
}
unset($item); // less item

if (!$found) {
  $_SESSION['panier'][] = [
    'id_art'   => $id_art,
    'quantite' => $quantite
  ];
}

// 5)revient a page depart
$back = 'article.php?id_art=' . urlencode($id_art);
header('Location: ' . $back . '&ok=ajoute');
exit;
