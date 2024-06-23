<?php
session_start();
require "../db_config.php";
require "functions.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id_service = isset($data['service']) ? $data['service'] : '';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($id_service)) {
    $sql = "SELECT v.id_vet, v.vet_fname, v.vet_lname FROM vet v 
            INNER JOIN vet_services vs ON v.id_vet = vs.id_vet 
            WHERE vs.id_service = :id_service";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $query->execute();

    $vets = $query->fetchAll(PDO::FETCH_ASSOC);

    $sql2 = "SELECT price FROM services WHERE id_service = :id_service";
    $query2 = $pdo->prepare($sql2);
    $query2->bindParam(':id_service', $id_service, PDO::PARAM_INT);
    $query2->execute();

    $price = $query2->fetchColumn();

    if ($vets) {
        $response = [
            "status" => "success",
            "vets" => $vets,
            "price" => $price
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "No vets found for the selected service."
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Invalid request."
    ];
}





echo json_encode($response);
?>
