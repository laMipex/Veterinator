<?php
header('X-Frame-Options: SAMEORIGIN');
session_start();
require "../db_config.php";
require "functions.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$idVet = $data['id_vet'] ?? '';

$error = false;
$responseMessage = "Vet banned successfully!";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($idVet)) {
        $error = true;
    }


    if ($error) {
        $responseMessage = "Something went wrong, please try later!";

    } else {

        try {


            $banSql = "UPDATE vet SET is_banned = 1 WHERE id_vet = :id_vet";
            $stmt = $pdo->prepare($banSql);
            $stmt->bindParam(':id_vet', $idVet, PDO::PARAM_INT);
            $stmt->execute();

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
    "message" => $responseMessage,
    "status" => 'OK'
];

echo json_encode($response);
?>

