<?php
require_once 'auth.php';
checkLogin();
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$myDogs = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photos = [];
    for($i=1; $i<=3; $i++) {
        $name = time()."_".$_FILES["photo$i"]["name"];
        move_uploaded_file($_FILES["photo$i"]["tmp_name"], "uploads/".$name);
        $photos[] = $name;
    }

    $stmt = $pdo->prepare("INSERT INTO dogs (user_id, nom, race, age, sexe, poids, categorie, description, photo1, photo2, photo3) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $_SESSION['user_id'], $_POST['nom'], $_POST['race'], $_POST['age'], 
        $_POST['sexe'], $_POST['poids'], $_POST['categorie'], $_POST['description'],
        $photos[0], $photos[1], $photos[2]
    ]);
    header("Location: annonces.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Mon Profil</title>
</head>
<body>
    <div class="title">Mon Profil</div>
    <div class="center">
        <div class="card" style="width:400px;">
            <h2>Créer mon annonce</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="nom" placeholder="Nom du chien" required>
                <input type="text" name="race" placeholder="Race">
                <input type="number" name="age" placeholder="Âge">
                <input type="text" name="sexe" placeholder="Sexe">
                <input type="text" name="poids" placeholder="Poids (ex: 25kg)">
                <select name="categorie" style="width:100%; padding:10px; margin:10px 0; border-radius:8px;">
                    <option>Chien de salon</option>
                    <option>Molosse</option>
                    <option>Catégorisé (Type A)</option>
                    <option>Catégorisé (Type B)</option>
                </select>
                <textarea name="description" placeholder="Description..."></textarea>
                <p>Photos (3 obligatoires) :</p>
                <input type="file" name="photo1" required>
                <input type="file" name="photo2" required>
                <input type="file" name="photo3" required>
                <button type="submit" class="btn">Publier l'annonce</button>
            </form>
            <h2 style="margin-top:30px; margin-bottom:10px;">Mes annonces</h2>

<div class="grid">
  <?php if (empty($myDogs)): ?>
    <p style="grid-column: 1/-1; text-align:center;">Aucune annonce pour le moment.</p>
  <?php endif; ?>

  <?php foreach ($myDogs as $dog): ?>
    <div class="card">
      <img src="uploads/<?= htmlspecialchars($dog['photo1']) ?>"
           onerror="this.src='https://via.placeholder.com/300x200?text=Pas+de+photo'">

      <h3><?= htmlspecialchars($dog['nom']) ?></h3>
      <p><?= htmlspecialchars($dog['race']) ?> - <?= (int)$dog['age'] ?> ans</p>
      <p style="font-size:12px; color:#bbb; margin-top:8px;">
        <?= htmlspecialchars($dog['categorie']) ?>
      </p>

      <a href="profil-detail.php?id=<?= (int)$dog['id'] ?>" class="btn">Voir</a>
      <a href="edit_dog.php?id=<?= (int)$dog['id'] ?>" class="btn" style="background:#555;">Modifier</a>
      <a href="delete_dog.php?id=<?= (int)$dog['id'] ?>"
   class="btn"
   style="background:#ff5252;"
   onclick="return confirm('Supprimer cette annonce ?');">
  Supprimer
</a>
    </div>
  <?php endforeach; ?>
</div>
            <a href="support.php" class="btn" style="background:#555;">Besoin d'aide ? Support</a>
        </div>
    </div>
</body>
</html>