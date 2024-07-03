<?php
session_start();
require "../db_config.php";
require "functions.php";

$id_user = $_SESSION['id_user'] ?? "";

// Example query to fetch pets
$sql = "SELECT p.id_pet, p.pet_name
        FROM pet p
        WHERE p.id_user = :id_user";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
    'status' => 'success',
    'pets' => $pets
];

if (!$pets) {
    $response = [
        "status" => "error",
        "message" => "No pets found for the selected user."
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
