<?php
require_once 'auth.php';
checkLogin();
$match_id = $_GET['id'];
$me = $_SESSION['user_id'];

// Envoyer un message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['msg'])) {
    $stmt = $pdo->prepare("INSERT INTO messages (match_id, sender_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$match_id, $me, $_POST['msg']]);
}

// Récupérer les messages
$stmt = $pdo->prepare("SELECT * FROM messages WHERE match_id = ? ORDER BY date_envoi ASC");
$stmt->execute([$match_id]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Discussion</title>
</head>
<body>
    <nav><a href="matches.php">← Retour aux matchs</a></nav>

    <h1 class="title">Discussion</h1>
    
    <div class="profile" style="max-width: 600px;">
        <div class="chat-box">
            <?php foreach($messages as $msg): ?>
                <div class="msg <?= ($msg['sender_id'] == $me) ? 'me' : 'other' ?>">
                    <strong><?= ($msg['sender_id'] == $me) ? "Moi" : "Lui" ?></strong><br>
                    <?= htmlspecialchars($msg['message']) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="msg" placeholder="Votre message..." required style="margin:0;">
            <button type="submit" class="btn" style="margin:0; width: 100px;">Envoyer</button>
        </form>
    </div>
</body>
</html>