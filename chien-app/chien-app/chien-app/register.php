<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nom'], $_POST['email'], $hash]);
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<body>
    <div class="center">
        <div class="card">
            <h2 class="title">Créer un compte</h2>
            <form method="POST">
                <input type="text" name="nom" placeholder="Nom complet" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit" class="btn">S'inscrire</button>
            </form>
            <a href="login.php" style="color:white; font-size:12px;">Déjà inscrit ? Connexion</a>
        </div>
    </div>
</body>
</html>