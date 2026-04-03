<?php
require_once 'auth.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO support (user_id, sujet, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['sujet'], $_POST['message']]);
    $success = "Message envoyé au support !";
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Support</title>
</head>
<body>
    <div class="title">Support Technique</div>
    <div class="center">
        <div class="card">
            <?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>
            <form method="POST">
                <input type="text" name="sujet" placeholder="Sujet" required>
                <textarea name="message" placeholder="Votre problème..."></textarea>
                <button type="submit" class="btn">Envoyer au support</button>
            </form>
            <a href="profil.php" style="color:white;">Retour</a>
        </div>
    </div>
</body>
</html>