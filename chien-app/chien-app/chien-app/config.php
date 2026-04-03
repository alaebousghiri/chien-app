<?php
session_start();
try {
    $pdo = new PDO("mysql:host=localhost;dbname=annonceur;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>