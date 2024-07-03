<?php
session_start();
require "../db_config.php";
require "functions.php";

// Provjera postojanja podatka vet_id u POST zahtjevu
if (!isset($_POST['vet_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Vet ID is missing']);
    exit;
}

// Dobivanje vet_id iz POST zahtjeva
$vet_id = $_POST['vet_id'];

// Upit za dobivanje radnog vremena veterinara iz baze podataka
$sql = "SELECT work_start, work_end FROM vet WHERE id_vet = :vet_id";
$query = $pdo->prepare($sql);
$query->bindParam(':vet_id', $vet_id, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode(['status' => 'success', 'work_start' => $result['work_start'], 'work_end' => $result['work_end']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Veterinarian not found']);
}
?>
