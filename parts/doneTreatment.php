<?php
header('X-Frame-Options: SAMEORIGIN');
session_start();
require "../db_config.php";
require "functions.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$condition = $data['condition'] ?? '';
$diagnosis = $data['diagnosis'] ?? '';
$medication = $data['medication'] ?? '';
$duration = $data['duration'] ?? '';
$reservationCode = $data['reservationCode'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($condition) || empty($diagnosis) || empty($medication) || empty($reservationCode)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter all required fields.']);
        exit;
    }

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Update the reservation
        $updateReservationSQL = "UPDATE reservations SET code_verified= 1 WHERE code = :reservationCode";
        $updateReservationQuery = $pdo->prepare($updateReservationSQL);
        $updateReservationQuery->bindParam(':reservationCode', $reservationCode, PDO::PARAM_STR);
        $updateReservationQuery->execute();

        // Get the id_reservation
        $getIdSql = "SELECT r.id_reservation
                     FROM reservations r 
                     INNER JOIN pet p ON p.id_pet = r.id_pet
                     INNER JOIN users u ON p.id_user = u.id_user
                     INNER JOIN vet v ON v.id_vet = r.id_vet
                     INNER JOIN services s ON r.id_service = s.id_service
                     WHERE r.code = :reservationCode";
        $getIdQuery = $pdo->prepare($getIdSql);
        $getIdQuery->bindParam(':reservationCode', $reservationCode, PDO::PARAM_STR);
        $getIdQuery->execute();
        $id_reservation = $getIdQuery->fetchColumn(); // Fetching only the id_reservation value

        if (!$id_reservation) {
            throw new PDOException('Reservation not found.');
        }

        // Insert into done_procedures
        $insertDoneTreatmentSQL = "INSERT INTO done_procedures (id_reservation, prescribed_medication, pet_condition, diagnosis, procedure_duration, procedure_date) 
                                   VALUES (:id_reservation, :medication, :condition, :diagnosis, :duration, NOW())";
        $insertDoneTreatmentQuery = $pdo->prepare($insertDoneTreatmentSQL);
        $insertDoneTreatmentQuery->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
        $insertDoneTreatmentQuery->bindParam(':medication', $medication, PDO::PARAM_STR);
        $insertDoneTreatmentQuery->bindParam(':condition', $condition, PDO::PARAM_STR);
        $insertDoneTreatmentQuery->bindParam(':diagnosis', $diagnosis, PDO::PARAM_STR);
        $insertDoneTreatmentQuery->bindParam(':duration', $duration, PDO::PARAM_STR);
        $insertDoneTreatmentQuery->execute();

        // Commit the transaction
        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Treatment saved successfully.']);
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Failed to save treatment: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
