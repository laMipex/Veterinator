<?php
session_start();
require "../db_config.php";
require "functions.php";

$id_user = $_SESSION['id_user'];

header('X-Frame-Options: SAMEORIGIN');
header("Content-Type: application/json");
$data = json_decode(stripslashes(file_get_contents("php://input")), true);

$id_service = $data['id_service'] ?? '';
$id_pet = $data['id_pet'] ?? '';
$id_vet = $data['id_vet'] ?? '';
$date = $data['reservation_date'] ?? '';
$time = $data['reservation_time'] ?? '';
$price = $data['price'] ?? '';



$error = false;
$responseMessage = "Data added successfully!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /*if (empty($id_service) || empty($id_vet) || empty($date) || empty($time) || empty($price) || empty($id_pet)) {
        $error = true;
    }*/

    if ($error) {
        $responseMessage = "You must fill all the fields!!";
    } else {
        // Dobijanje trajanja usluge
        $sql2 = "SELECT s.service_duration FROM reservations r INNER JOIN services s ON r.id_service = s.id_service WHERE r.id_service = :id_service";
        $query2 = $pdo->prepare($sql2);
        $query2->bindParam(':id_service', $id_service, PDO::PARAM_STR);
        $query2->execute();
        $duration = $query2->fetch(PDO::FETCH_OBJ)->service_duration;

        // Pretvaranje vremena rezervacije i trajanja u DateTime objekte
        $reservation_start = new DateTime("$date $time");
        $duration_parts = explode(':', $duration);
        $reservation_end = clone $reservation_start;
        $reservation_end->add(new DateInterval('PT' . $duration_parts[0] . 'H' . $duration_parts[1] . 'M'));

        // Provera da li postoji preklapanje sa postojećim rezervacijama
        $sql_check = "SELECT reservation_time, service_duration FROM reservations WHERE id_vet = :id_vet AND reservation_date = :reservation_date";
        $query_check = $pdo->prepare($sql_check);
        $query_check->bindParam(':id_vet', $id_vet, PDO::PARAM_STR);
        $query_check->bindParam(':reservation_date', $date, PDO::PARAM_STR);
        $query_check->execute();
        $reservations = $query_check->fetchAll(PDO::FETCH_ASSOC);

        $can_schedule = true;
        foreach ($reservations as $reservation) {
            $existing_start = new DateTime("$date " . $reservation['reservation_time']);
            $existing_duration_parts = explode(':', $reservation['service_duration']);
            $existing_end = clone $existing_start;
            $existing_end->add(new DateInterval('PT' . $existing_duration_parts[0] . 'H' . $existing_duration_parts[1] . 'M'));

            if (($reservation_start < $existing_end) && ($reservation_end > $existing_start)) {
                $can_schedule = false;
                break;
            }
        }

        if ($can_schedule) {



            $sql_mail = "SELECT u.user_email FROM users u INNER JOIN pet p ON u.id_user = p.id_user  WHERE u.id_user = :id_user";
            $query3 = $pdo->prepare($sql_mail);
            $query3->bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $query3->execute();
            $user_email = $query3 ->fetch(PDO::FETCH_OBJ)->user_email;
            //if (existsUser($pdo, $user_email)){
            $code = createCode(6);
            if ($code) {

                try {
                    $body = "Hello $user_email. Your reservation is submited. Bring this code when you bring your pet for treatment. <b>$code</b>";
                    sendEmail($pdo, $user_email, $emailMessages['inesrtReservation'], $body, $id_user);
                    //$error = true;
                    $responseMessage = "Mail with code has been sent";
                } catch (Exception $e) {
                    error_log("****************************************");
                    error_log($e->getMessage());
                    error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                    $error = true;
                    $responseMessage = "We could not send email";
                }


                // Umetanje nove rezervacije
                $sql = "INSERT INTO reservations(id_pet, id_service, id_vet, reservation_date, reservation_time, treatment_price,code, reservation_added, service_duration) VALUES (:id_pet, :id_service, :id_vet, :reservation_date, :reservation_time, :price, :code, now(), :service_duration)";
                $query = $pdo->prepare($sql);
                $query->bindParam(':id_pet', $id_pet, PDO::PARAM_STR);
                $query->bindParam(':id_service', $id_service, PDO::PARAM_STR);
                $query->bindParam(':id_vet', $id_vet, PDO::PARAM_STR);
                $query->bindParam(':reservation_date', $date, PDO::PARAM_STR);
                $query->bindParam(':reservation_time', $time, PDO::PARAM_STR);
                $query->bindParam(':price', $price, PDO::PARAM_INT);
                $query->bindParam(':service_duration', $duration, PDO::PARAM_STR);
                $query->bindParam(':code', $code, PDO::PARAM_STR);

                $query->execute();
                // Set a session variable to indicate form submission
            }
            //}


        } else {
            $error = true;
            $responseMessage = "Cannot schedule the treatment because there is not enough available time.";
        }
    }
} else {
    $error = true;
    $responseMessage = "Data are not sent using method POST";
}

$response = [
    "message" => $responseMessage,
    "status" => $error ? 'ERROR' : 'OK',
    "id_vet" => $id_vet,
    "date" => $date
];

sleep(1);

echo json_encode($response);



