<?php
require_once 'auth.php';
checkLogin();

$id = (int)($_POST['id'] ?? 0);
$me = (int)$_SESSION['user_id'];

if ($id <= 0) { header("Location: profil.php"); exit; }

// Récupérer l’annonce actuelle pour garder les photos si non upload
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $me]);
$dog = $stmt->fetch();

if (!$dog) { header("Location: profil.php"); exit; }

// Valeurs texte
$nom = $_POST['nom'] ?? '';
$race = $_POST['race'] ?? '';
$age = (int)($_POST['age'] ?? 0);
$sexe = $_POST['sexe'] ?? '';
$poids = $_POST['poids'] ?? '';
$categorie = $_POST['categorie'] ?? '';
$description = $_POST['description'] ?? '';

// Gestion des photos : si un fichier est uploadé -> remplacer, sinon garder l’ancien
$photos = [
  1 => $dog['photo1'],
  2 => $dog['photo2'],
  3 => $dog['photo3']
];

for ($i = 1; $i <= 3; $i++) {
  $key = "photo$i";
  if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK && !empty($_FILES[$key]['name'])) {
    $ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
    $name = time() . "_".$i."_".$me."_".$id.".".$ext;
    move_uploaded_file($_FILES[$key]['tmp_name'], "uploads/".$name);
    $photos[$i] = $name;
  }
}

$upd = $pdo->prepare("
  UPDATE dogs
  SET nom = ?, race = ?, age = ?, sexe = ?, poids = ?, categorie = ?, description = ?,
      photo1 = ?, photo2 = ?, photo3 = ?
  WHERE id = ? AND user_id = ?
");
$upd->execute([
  $nom, $race, $age, $sexe, $poids, $categorie, $description,
  $photos[1], $photos[2], $photos[3],
  $id, $me
]);

header("Location: profil.php");
exit;