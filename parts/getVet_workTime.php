<?php
session_start();
require "../db_config.php";
require "functions.php";

header('Content-Type: application/json');
$data = json_decode(stripslashes(file_get_contents("php://input")), true);
if (isset($_POST['vet_id'])) {
    $vet_id = $_POST['vet_id'];

    $sql = "SELECT work_start, work_end FROM vet WHERE id_vet = :vet_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':vet_id', $vet_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['status' => 'success', 'work_start' => $result['work_start'], 'work_end' => $result['work_end']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Veterinarian not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
