<?php
header('X-Frame-Options: SAMEORIGIN');
session_start();
require "../db_config.php";
require "functions.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$idRes = $data['idRes'] ?? '';
$negativePoints = $data['negative_points'] ?? '';




    $getCodeSql = "SELECT r.code
                 FROM reservations r 
                 INNER JOIN pet p ON p.id_pet = r.id_pet
                 INNER JOIN users u ON p.id_user = u.id_user                   
                 WHERE r.id_reservation = :id_reservation";
    $getCodeSql = $pdo->prepare($getCodeSql);
    $getCodeSql->bindParam(':id_reservation', $idRes, PDO::PARAM_STR);
    $getCodeSql->execute();
    $result = $getCodeSql->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $code = $result['code'];
    } else {
        $code= null; // Or handle the case where no result is found as you prefer
    }



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($negativePoints == 1) {
        // Pretraga rezervacije i korisnika na osnovu koda
        $getIdSql = "SELECT u.id_user
                 FROM reservations r 
                 INNER JOIN pet p ON p.id_pet = r.id_pet
                 INNER JOIN users u ON p.id_user = u.id_user                   
                 WHERE r.id_reservation = :id_reservation";
        $getIdQuery = $pdo->prepare($getIdSql);
        $getIdQuery->bindParam(':id_reservation', $idRes, PDO::PARAM_STR);
        $getIdQuery->execute();
        $userData = $getIdQuery->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            echo json_encode(['status' => 'error', 'message' => 'Reservation not found.']);
            exit;
        }

        // Ažuriranje negativnih poena korisnika
        $id_user = $userData['id_user'];

        // Update user's negative points
        $updateUserSql = "UPDATE users SET negative_points = negative_points + 1 WHERE id_user = :id_user";
        $updateUserQuery = $pdo->prepare($updateUserSql);
        $updateUserQuery->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $updateUserQuery->execute();

        $updateReservationSQL = "UPDATE reservations SET code_verified= 1 WHERE code = :code";
        $updateReservationQuery = $pdo->prepare($updateReservationSQL);
        $updateReservationQuery->bindParam(':code', $code, PDO::PARAM_STR);
        $updateReservationQuery->execute();

        echo json_encode(['status' => 'success', 'message' => 'Negative points updated successfully.']);

    } else  {

        $codeInput = $data['code_input'] ?? '';

        if (empty($codeInput) ) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter all required fields.']);
            exit;
        }

        $sql2 = "SELECT r.id_reservation, r.id_pet, p.pet_name, r.id_service, s.service_name, r.id_vet, r.reservation_date,
             r.reservation_time, r.treatment_price, r.code, r.service_duration, u.id_user, u.user_fname, u.user_lname
             FROM reservations r 
             INNER JOIN pet p ON p.id_pet = r.id_pet
             INNER JOIN users u ON p.id_user = u.id_user
             INNER JOIN vet v ON v.id_vet = r.id_vet
             INNER JOIN services s ON r.id_service = s.id_service
             WHERE r.code = :codeInput AND r.id_reservation = :id_reservation";

        $query = $pdo->prepare($sql2);
        $query->bindParam(':codeInput', $codeInput, PDO::PARAM_STR);
        $query->bindParam(':id_reservation', $idRes, PDO::PARAM_STR);
        //$query->bindParam(':idRes', $idRes, PDO::PARAM_INT);

        try {
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                if ($_SESSION['id_vet'] !== $result['id_vet']) {
                    echo json_encode(['status' => 'error', 'message' => 'This reservation is with another vet.']);
                    exit;
                }

                echo json_encode(['status' => 'success', 'message' => 'Reservation found.', 'reservations' => [$result]]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Code is not valid.']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to execute query.']);
        }
    }

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}


