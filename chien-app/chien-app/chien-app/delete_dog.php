<?php
require_once 'auth.php';
checkLogin();

$id = (int)($_GET['id'] ?? 0);
$me = (int)$_SESSION['user_id'];

if ($id <= 0) { header("Location: profil.php"); exit; }

// Récupérer l'annonce pour supprimer les fichiers
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $me]);
$dog = $stmt->fetch();

if (!$dog) { header("Location: profil.php"); exit; }

// Supprimer les fichiers photo (si existants)
$files = ['photo1','photo2','photo3'];
foreach ($files as $f) {
  $path = "uploads/" . $dog[$f];
  if (!empty($dog[$f]) && file_exists($path)) {
    @unlink($path);
  }
}

// Supprimer la ligne en base
$del = $pdo->prepare("DELETE FROM dogs WHERE id = ? AND user_id = ?");
$del->execute([$id, $me]);

header("Location: profil.php");
exit;