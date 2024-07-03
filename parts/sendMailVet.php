<?php
header('X-Frame-Options: SAMEORIGIN');
session_start();
require "../db_config.php";
require "functions.php";

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$emailVet = $data['vet_email'] ?? '';

$error = false;
$responseMessage = "Link send successfully!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($emailVet)) {
        $error = true;
    }
    if (str_contains($emailVet, "example")) {
        $error = true;
        $responseMessage = "It's an example email";
    }

    if (empty($emailVet) || !filter_var($emailVet, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $responseMessage = "Email is not valid!!";
    }

    if ($error) {
        $responseMessage;

    } else {

        try {
            $body = "Your username is $emailVet. To register on our site as Veterinarian click on the <a href=" . SITE . "vet_signin.php>link</a>";
            sendVetEmail($pdo, $emailVet, $emailMessages['sendRegister'], $body);

        } catch (Exception $e) {
            error_log("****************************************");
            error_log($e->getMessage());
            error_log("file:" . $e->getFile() . " line:" . $e->getLine());
            $error = true;
            $responseMessage = "Problems with sending mail!!";

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

sleep(1);

echo json_encode($response);