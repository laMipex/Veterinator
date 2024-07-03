<?php
session_start();
require "../db_config.php";
require "functions.php";

$id_vet = $_SESSION['id_vet'] ?? "";

if (empty($id_vet)) {
    echo json_encode(['error' => 'No vet ID found in session']);
    exit;
}

$query = $GLOBALS['pdo']->prepare("SELECT * FROM vet WHERE id_vet = :id");
$query->execute(['id' => $id_vet]);
$vet = $query->fetch();

echo json_encode([
    'photo' => $vet['photo']
]);
?>
