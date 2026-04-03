<?php
require_once "auth.php";
require_login();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: chat.php");
  exit;
}

$matchId = (int)($_POST["match_id"] ?? 0);
$body = trim($_POST["body"] ?? "");

if ($matchId <= 0 || $body === "") {
  header("Location: chat.php?match_id=" . $matchId);
  exit;
}

$userId = (int)$_SESSION["user_id"];

// Vérifier que user fait partie du match
$chk = $pdo->prepare("SELECT id FROM matches WHERE id = ? AND (user1_id = ? OR user2_id = ?) LIMIT 1");
$chk->execute([$matchId, $userId, $userId]);
if (!$chk->fetch()) {
  header("Location: matches.html");
  exit;
}

$ins = $pdo->prepare("INSERT INTO messages (match_id, sender_user_id, body) VALUES (?, ?, ?)");
$ins->execute([$matchId, $userId, $body]);

header("Location: chat.php?match_id=" . $matchId);
exit;