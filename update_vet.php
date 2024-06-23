<?php
session_start();
require "db_config.php";
require "parts/functions.php";

$response = ["status" => "error", "message" => "Unknown error occurred"];

try {

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id_pet']) && isset($data['id_vet'])) {
            $id_pet = $data['id_pet'];
            $id_vet = $data['id_vet'];

            $sql = "UPDATE pet SET id_vet = :id_vet WHERE id_pet = :id_pet";
            $query = $pdo->prepare($sql);
            $query->bindParam(':id_vet', $id_vet, PDO::PARAM_INT);
            $query->bindParam(':id_pet', $id_pet, PDO::PARAM_INT);

            if ($query->execute()) {
                $response = ["status" => "success", "message" => "Vet updated successfully"];
            } else {
                $response["message"] = "Failed to update vet";
            }
        } else {
            $response["message"] = "Invalid input";
        }
    } else {
        $response["message"] = "Invalid request method";
    }
} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
}


header('Content-Type: application/json');
echo json_encode($response);

?>
