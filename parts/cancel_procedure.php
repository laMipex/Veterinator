<?php
header('X-Frame-Options: SAMEORIGIN');
session_start();
require "../db_config.php";
require "functions.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$idReservation = $data['id_reservation'] ?? '';

$error = false;
$responseMessage = "Data canceled successfully!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($idReservation)) {
        $error = true;
        $responseMessage = "Reservation ID is missing.";
    }

    if (!$error) {
        try {
            // Fetch the reservation details
            $selectSql = "SELECT reservation_date, reservation_time FROM reservations WHERE id_reservation = :id_reservation";
            $stmt = $pdo->prepare($selectSql);
            $stmt->bindParam(':id_reservation', $idReservation, PDO::PARAM_STR);
            $stmt->execute();
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reservation) {
                $reservationDateTime = new DateTime($reservation['reservation_date'] . ' ' . $reservation['reservation_time']);
                $currentDateTime = new DateTime();
                $interval = $currentDateTime->diff($reservationDateTime);
                $hoursDifference = $interval->h + ($interval->days * 24);

                if ($hoursDifference < 4) {
                    $error = true;
                    $responseMessage = "It's too late now to cancel a procedure";
                } else {
                    // Update the reservation to cancel it
                    $cancelSql = "UPDATE reservations SET cancel = 1, code_verified = 1 WHERE id_reservation = :id_reservation";
                    $stmt = $pdo->prepare($cancelSql);
                    $stmt->bindParam(':id_reservation', $idReservation, PDO::PARAM_STR);
                    $stmt->execute();
                }
            } else {
                $error = true;
                $responseMessage = "Reservation not found.";
            }
        } catch (PDOException $e) {
            $error = true;
            $responseMessage = "Database error: " . $e->getMessage();
        }
    }
} else {
    $error = true;
    $responseMessage = "Data are not sent using method POST";
}

$response = [
    "success" => !$error,
    "message" => $responseMessage,
    "status" => $error ? 'ERROR' : 'OK'
];

echo json_encode($response);
?>
