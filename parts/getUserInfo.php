<?php
session_start();
require "../db_config.php";
require "functions.php";

$id_user = $_SESSION['id_user'] ?? "";

if (empty($id_user)) {
    echo json_encode(['error' => 'No user ID found in session']);
    exit;
}

$query = $GLOBALS['pdo']->prepare("SELECT * FROM users WHERE id_user = :id");
$query->execute(['id' => $id_user]);
$user = $query->fetch();

echo json_encode([
    'photo' => $user['photo']
]);
?>
