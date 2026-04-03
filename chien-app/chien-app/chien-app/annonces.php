<?php 
require_once 'auth.php';
checkLogin();
$me = $_SESSION['user_id'];

// Logique de Like
if (isset($_POST['like_dog_id'])) {
    $dog_id = $_POST['like_dog_id'];
    
    // 1. Enregistrer le like
    $stmt = $pdo->prepare("INSERT IGNORE INTO likes (liker_id, dog_id) VALUES (?, ?)");
    $stmt->execute([$me, $dog_id]);

    // 2. Vérifier si le propriétaire du chien a aimé UN des chiens de l'utilisateur actuel
    $stmt = $pdo->prepare("SELECT user_id FROM dogs WHERE id = ?");
    $stmt->execute([$dog_id]);
    $owner_id = $stmt->fetchColumn();

    if ($owner_id && $owner_id != $me) {
        $stmt = $pdo->prepare("SELECT l.id FROM likes l JOIN dogs d ON l.dog_id = d.id WHERE l.liker_id = ? AND d.user_id = ?");
        $stmt->execute([$owner_id, $me]);
        
        if ($stmt->fetch()) {
            $u1 = min($me, $owner_id); $u2 = max($me, $owner_id);
            $pdo->prepare("INSERT IGNORE INTO matches (user1_id, user2_id) VALUES (?, ?)")->execute([$u1, $u2]);
            $match_success = "C'est un Match ! Retrouvez-le dans vos messages.";
        }
    }
}

// Récupérer les chiens (sauf les miens)
$dogs = $pdo->query("SELECT * FROM dogs WHERE user_id != $me ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Découvrir</title>
</head>
<body>
    <nav>
        <a href="index.php">ACCUEIL</a>
        <a href="annonces.php">DÉCOUVRIR</a>
        <a href="matches.php">MATCHS</a>
        <a href="profil.php">MON PROFIL</a>
    </nav>

    <h1 class="title">Chiens à proximité</h1>
    <?php if(isset($match_success)) echo "<p style='text-align:center; color:#e91e63; font-weight:bold;'>❤️ $match_success</p>"; ?>

    <div class="grid">
        <?php foreach($dogs as $dog): ?>
        <div class="card">
            <img src="uploads/<?= $dog['photo1'] ?>" onerror="this.src='https://via.placeholder.com/300x200?text=Pas+de+photo'">
            <h3><?= htmlspecialchars($dog['nom']) ?></h3>
            <p><?= htmlspecialchars($dog['race']) ?> - <?= $dog['age'] ?> ans</p>
            <a href="profil-detail.php?id=<?= $dog['id'] ?>" class="btn">Détails</a>
            <form method="POST">
                <input type="hidden" name="like_dog_id" value="<?= $dog['id'] ?>">
                <button type="submit" class="btn btn-like">❤️ Liker</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>