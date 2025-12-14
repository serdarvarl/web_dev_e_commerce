<?php
session_start();

// Müşteri giriş yapmış mı?
if (!isset($_SESSION['client'])) {
  header('Location: connexion.php?err=Veuillez vous connecter pour ajouter au panier');
  exit;
}

// Verileri al ve temizle
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
// YENİ EKLENEN KISIM: STOK KONTROLÜ (Maksimum Stok Senaryosu)
// ============================================================
require_once 'db.php'; // Veritabanı bağlantısı şart
$pdo = getBD();

// 1. Bu ürünün veritabanındaki güncel stoğunu çek
$stmt = $pdo->prepare("SELECT quantite FROM Articles WHERE id_art = ?");
$stmt->execute([$id_art]);
$stockDispo = (int)$stmt->fetchColumn(); // Örneğin: 10

// 2. Kullanıcının sepetinde bu üründen zaten var mı?
$sepetMiktari = 0;
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        if ((int)$item['id_art'] === $id_art) {
            $sepetMiktari = (int)$item['quantite'];
            break;
        }
    }
}

// 3. Kritik Karar: İstenen + Sepetteki > Stok mu?
if (($quantite + $sepetMiktari) > $stockDispo) {
    // Stok yetersiz! Kullanıcıyı geri gönder ve uyar.
    $back = 'article.php?id_art=' . $id_art;
    // Hata mesajı: Stok yetersiz (Maks: 10)
    header('Location: ' . $back . '&err=Stock insuffisant (Max: ' . $stockDispo . ')');
    exit;
}
// ============================================================
// STOK KONTROLÜ BİTİŞ
// ============================================================


// Sepet Session İşlemleri (Senin Orijinal Kodun)
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

// Başarılı ise geri dön
$back = 'article.php?id_art=' . urlencode($id_art);
header('Location: ' . $back . '&ok=ajoute');
exit;
?>