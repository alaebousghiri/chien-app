<?php
require_once "auth.php";
require_login();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: discover.html");
  exit;
}

$liker = (int)$_SESSION["user_id"];
$dogId = (int)($_POST["dog_id"] ?? 0);
if ($dogId <= 0) { header("Location: discover.html"); exit; }

$stmt = $pdo->prepare("SELECT user_id FROM dogs WHERE id = ?");
$stmt->execute([$dogId]);
$dog = $stmt->fetch();
if (!$dog) { header("Location: discover.html"); exit; }

$ownerOfDog = (int)$dog["owner_user_id"];
if ($ownerOfDog === $liker) {
  // On évite de matcher avec soi-même.
  header("Location: discover.html");
  exit;
}

// insérer le like
$ins = $pdo->prepare("INSERT INTO likes (liker_user_id, dog_id) VALUES (?, ?)
  ON DUPLICATE KEY UPDATE liker_user_id=liker_user_id");
$ins->execute([$liker, $dogId]);

/**
 * Match mutuel (défaut “mutuel seulement”)
 * Condition proposée (simple et cohérente) :
 * - user B possède ce dog
 * - user B a aimé au moins un dog appartenant à user A
 */
$check = $pdo->prepare("
  SELECT 1
  FROM likes lB
  JOIN dogs dA ON dA.id = lB.dog_id
  WHERE lB.liker_user_id = :ownerOfDog
    AND dA.owner_user_id = :liker
  LIMIT 1
");
$check->execute([
  ":ownerOfDog" => $ownerOfDog,
  ":liker" => $liker
]);

if ($check->fetch()) {
  // Créer le match si pas encore existant (user1_id < user2_id)
  $u1 = min($liker, $ownerOfDog);
  $u2 = max($liker, $ownerOfDog);

  $m = $pdo->prepare("INSERT IGNORE INTO matches (user1_id, user2_id) VALUES (?, ?)");
  $m->execute([$u1, $u2]);
}

header("Location: matches.html");
exit;