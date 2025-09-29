
<?php
// Récupération des données envoyées par GET
$nom  = $_POST['n'] ?? '';
$prenom = $_POST['p'] ?? '';
$adresse = $_POST['adr'] ?? '';
$numero  = $_POST['num'] ?? '';
$mail  = $_POST['mail'] ?? '';
$mdp1  = $_POST['mdp1'] ?? '';
$mdp2  = $_POST['mdp2'] ?? '';

$erreurMdp = ($mdp1 !== $mdp2);


  if (empty($nom) || empty($prenom) || empty($mail) || empty($mdp1) || empty($mdp2)) {
        echo '<meta http-equiv="refresh" content="0;url=nouveau.php">';
    } else {
        echo '<meta http-equiv="refresh" content="0;url=index.php">';
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Enregistrement</title>
    <link rel="stylesheet" href="styles.css">
  
</head>
<body>

 

</body>
</html>




 
