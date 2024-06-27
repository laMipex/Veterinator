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
    'vet_name' => $vet['vet_fname'] . " " .$vet['vet_lname'],
    'vet_email' => $vet['vet_email'],
    'vet_phone' => $vet['vet_phone'],
    'city' => $vet['city'],
    'work_start' => $vet['work_start'],
    'work_end' => $vet['work_end'],
    'photo' => $vet['photo']
]);
?>
