<?php
require_once 'auth.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ?");
$stmt->execute([$id]);
$dog = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Détails</title>
</head>
<body>
    <div class="title"><a href="annonces.php" style="color:white; text-decoration:none;">← Retour aux annonces</a></div>
    <div class="profile">
        <h2>Profil de <?= $dog['nom'] ?></h2>
        <div style="display:flex; gap:10px; overflow-x:auto;">
            <img src="uploads/<?= $dog['photo1'] ?>" style="width:200px;">
            <img src="uploads/<?= $dog['photo2'] ?>" style="width:200px;">
            <img src="uploads/<?= $dog['photo3'] ?>" style="width:200px;">
        </div>
        <p><strong>Race :</strong> <?= $dog['race'] ?></p>
        <p><strong>Âge :</strong> <?= $dog['age'] ?> ans</p>
        <p><strong>Sexe :</strong> <?= $dog['sexe'] ?></p>
        <p><strong>Poids :</strong> <?= $dog['poids'] ?></p>
        <p><?= $dog['description'] ?></p>
    </div>
</body>
</html>