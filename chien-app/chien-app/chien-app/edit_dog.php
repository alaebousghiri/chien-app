<?php
require_once 'auth.php';
checkLogin();

$id = (int)($_GET['id'] ?? 0);
$me = (int)$_SESSION['user_id'];
if ($id <= 0) { header("Location: profil.php"); exit; }

// Vérifier que c'est bien ton annonce
$stmt = $pdo->prepare("SELECT * FROM dogs WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $me]);
$dog = $stmt->fetch();

if (!$dog) { header("Location: profil.php"); exit; }
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="style.css">
  <title>Modifier une annonce</title>
</head>
<body>
  <div class="title">
    <a href="profil.php" style="color:white; text-decoration:none;">← Retour</a>
  </div>

  <div class="center">
    <div class="card" style="width: 420px;">
      <h2 class="title">Modifier l’annonce</h2>

      <form method="POST" action="update_dog.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)$dog['id'] ?>">

        <input type="text" name="nom" value="<?= htmlspecialchars($dog['nom']) ?>" placeholder="Nom du chien" required>
        <input type="text" name="race" value="<?= htmlspecialchars($dog['race']) ?>" placeholder="Race">
        <input type="number" name="age" value="<?= (int)$dog['age'] ?>" placeholder="Âge">
        <input type="text" name="sexe" value="<?= htmlspecialchars($dog['sexe']) ?>" placeholder="Sexe">
        <input type="text" name="poids" value="<?= htmlspecialchars($dog['poids']) ?>" placeholder="Poids (ex: 25kg)">

        <select name="categorie" style="width:100%; padding:10px; margin:10px 0; border-radius:8px;" required>
          <?php
            $cats = ["Chien de salon","Molosse","Catégorisé (Type A)","Catégorisé (Type B)"];
            foreach ($cats as $c) {
              $sel = ($dog['categorie'] === $c) ? "selected" : "";
              echo "<option $sel>" . htmlspecialchars($c) . "</option>";
            }
          ?>
        </select>

        <textarea name="description" placeholder="Description..."><?= htmlspecialchars($dog['description']) ?></textarea>

        <p style="margin-top:10px;">Photos (laisser vide pour garder celles actuelles)</p>

        <label style="display:block; text-align:left; font-size:12px; color:#bbb; margin-top:8px;">Photo 1</label>
        <input type="file" name="photo1" accept="image/*">

        <label style="display:block; text-align:left; font-size:12px; color:#bbb; margin-top:8px;">Photo 2</label>
        <input type="file" name="photo2" accept="image/*">

        <label style="display:block; text-align:left; font-size:12px; color:#bbb; margin-top:8px;">Photo 3</label>
        <input type="file" name="photo3" accept="image/*">

        <button type="submit" class="btn">Enregistrer</button>
      </form>

      <a href="profil.php" class="btn" style="background:#555; margin-top:10px;">Annuler</a>
    </div>
  </div>
</body>
</html>