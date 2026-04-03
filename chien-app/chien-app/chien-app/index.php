<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>DogLove - Accueil</title>
</head>
<body>
    
    <nav>
        <a href="index.php">ACCUEIL</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="annonces.php">DÉCOUVRIR</a>
            <a href="matches.php">MATCHS</a>
            <a href="profil.php">MON PROFIL</a>
            <a href="logout.php" style="color: #ff5252;">DÉCONNEXION</a>
        <?php else: ?>
            <a href="login.php">CONNEXION</a>
            <a href="register.php">INSCRIPTION</a>
        <?php endif; ?>
    </nav>
<section class="hero">
    <video autoplay muted loop playsinline id="bgVideo">
      <source src="uploads/Background.mp4" type="video/mp4">
      Votre navigateur ne supporte pas la vidéo HTML5.
    </video>
    <div class="center">
        <div style="text-align:center;">
            <h1 style="font-size: 3rem;">Bienvenue sur DogLove</h1>
            <p style="color: #888; max-width: 600px; margin: 20px auto;">
                La plateforme n°1 pour faire rencontrer vos compagnons à quatre pattes. 
                Trouvez des partenaires de jeu ou des rencontres sérieuses pour vos chiens.
            </p>
            <div style="margin-top: 30px;">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn" style="display:inline-block; width: 200px;">Commencer</a>
                <?php else: ?>
                    <a href="annonces.php" class="btn" style="display:inline-block; width: 200px;">Voir les chiens</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>