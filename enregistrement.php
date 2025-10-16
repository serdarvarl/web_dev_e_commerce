<?php
// Hataları gör (geçici, geliştirme için)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php'; // TP3'teki getBD() fonksiyonun.  

// 1) POST verilerini al
$nom    = trim($_POST['n']   ?? '');
$prenom = trim($_POST['p']   ?? '');
$adresse= trim($_POST['adr'] ?? '');
$numero = trim($_POST['num'] ?? '');
$mail   = trim($_POST['mail']?? '');
$mdp1   = $_POST['mdp1'] ?? '';
$mdp2   = $_POST['mdp2'] ?? '';

// 2) Doğrulama
$invalid = ($nom==='' || $prenom==='' || $adresse==='' || $numero==='' || $mail==='' || $mdp1==='' || $mdp2==='' || $mdp1!==$mdp2);

if ($invalid) {
  // TP4 Ex.3–4: Hata → nouveau.php’ye geri dön (mdp’ler hariç alanları geri doldur)
  $qs = http_build_query([
    'n'=>$nom, 'p'=>$prenom, 'adr'=>$adresse, 'num'=>$numero, 'mail'=>$mail,
    'err' => ($mdp1!==$mdp2 ? 'Les mots de passe ne correspondent pas' : 'Formulaire incomplet')
  ]);
  meta_redirect("nouveau.php?".$qs);
  exit;
}

try {
  $pdo = getBD();

  // (İsteğe bağlı) aynı e-mail var mı kontrolü
  $check = $pdo->prepare("SELECT COUNT(*) FROM Clients WHERE mail = :mail");
  $check->execute([':mail'=>$mail]);
  if ($check->fetchColumn() > 0) {
    $qs = http_build_query([
      'n'=>$nom, 'p'=>$prenom, 'adr'=>$adresse, 'num'=>$numero, 'mail'=>$mail,
      'err'=>'Cette adresse e-mail est déjà utilisée'
    ]);
    meta_redirect("nouveau.php?".$qs);
    exit;
  }

  // 3) INSERT (TP4 Ex.6: getBD() ile DB yaz)  
  $hash = password_hash($mdp1, PASSWORD_BCRYPT);

  $sql = "INSERT INTO Clients (nom, prenom, adresse, numero, mail, mdp)
          VALUES (:nom, :prenom, :adresse, :numero, :mail, :mdp)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':nom' => $nom,
    ':prenom' => $prenom,
    ':adresse' => $adresse,
    ':numero' => $numero,
    ':mail' => $mail,
    ':mdp' => $hash
  ]);

  // Başarılı → index.php
  meta_redirect("index.php");

} catch (PDOException $e) {
  // UNIQUE ihlali vb. durumlar
  $msg = ($e->getCode()==='23000')
    ? 'Cette adresse e-mail est déjà utilisée'
    : 'Erreur DB: '.$e->getMessage();

  $qs = http_build_query([
    'n'=>$nom, 'p'=>$prenom, 'adr'=>$adresse, 'num'=>$numero, 'mail'=>$mail, 'err'=>$msg
  ]);
  meta_redirect("nouveau.php?".$qs);
  exit;
}

// TP4 Ex.3: head içinde META ile yönlendirme helper'ı  
function meta_redirect(string $url) {
  echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
  echo '<meta http-equiv="refresh" content="0;url='.htmlspecialchars($url,ENT_QUOTES).'">';
  echo '</head><body>Redirection…</body></html>';
}
