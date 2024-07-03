<?php
session_start();
require "../db_config.php";
require "functions.php";

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['vet_id']) && isset($data['reservation_date'])) {
    $vet_id = $data['vet_id'];
    $reservation_date = $data['reservation_date'];

    $sql = "SELECT reservation_time,service_duration FROM reservations WHERE id_vet = :vet_id AND reservation_date = :reservation_date";
    $query = $pdo->prepare($sql);
    $query->bindParam(':vet_id', $vet_id, PDO::PARAM_INT);
    $query->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);

    try {
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['status' => 'success', 'reservations' => $result]);
        } else {
            echo json_encode(['status' => 'success', 'reservations' => []]);
        }
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute query.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
