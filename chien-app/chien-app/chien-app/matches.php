<?php
require_once 'auth.php';
checkLogin();
$me = $_SESSION['user_id'];

// On cherche les utilisateurs avec qui on a un match
$stmt = $pdo->prepare("
    SELECT m.id as match_id, u.nom, u.id as other_id 
    FROM matches m 
    JOIN users u ON (u.id = m.user1_id OR u.id = m.user2_id) 
    WHERE (m.user1_id = ? OR m.user2_id = ?) AND u.id != ?
");
$stmt->execute([$me, $me, $me]);
$matches = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Mes Matchs</title>
</head>
<body>
    <nav>
        <a href="index.php">ACCUEIL</a>
        <a href="annonces.php">DÉCOUVRIR</a>
        <a href="matches.php">MATCHS</a>
        <a href="profil.php">MON PROFIL</a>
    </nav>

    <h1 class="title">Vos Matchs ❤️</h1>
    <div class="grid">
        <?php if(empty($matches)): ?>
            <p style="text-align:center; grid-column: 1/-1;">Aucun match pour le moment. Continuez à liker !</p>
        <?php endif; ?>
        
        <?php foreach($matches as $m): ?>
        <div class="card">
            <div style="font-size: 50px;"><img src="c:\xampp\htdocs\chien-app\chien-app\chien-mignon-7_1586331681.jpeg" alt="xhine"></div>
            <h3><?= htmlspecialchars($m['nom']) ?></h3>
            <a href="chat.php?id=<?= $m['match_id'] ?>" class="btn">Discuter</a>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>