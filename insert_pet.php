<?php

session_start();
require "db_config.php";
require "parts/functions.php";

header('Content-Type: application/json');

$id_user = $_SESSION['id_user'] ?? "";
header('X-Frame-Options: SAMEORIGIN');

$petName = $_POST['pet_name'] ?? '';
$age = $_POST['age'] ?? '';
$species = $_POST['species'] ?? '';
$breed = $_POST['breed'] ?? '';
$file = $_FILES['file'] ?? null;

$error = false;
$responseMessage = "Data added successfully!";

if ($file && $file["error"] > 0) {
    $responseMessage = "Error uploading file.";
    $error = true;
} else if ($file) {
    if (is_uploaded_file($file['tmp_name'])) {
        $file_name = $file["name"];
        $file_temp = $file["tmp_name"];
        $file_size = $file["size"];
        $file_type = $file["type"];
        $file_error = $file["error"];

        if (!exif_imagetype($file['tmp_name'])) {
            $responseMessage = "File is not a picture.";
            $error = true;
        } else {
            $ext_temp = explode(".", $file_name);
            $extension = end($ext_temp);

            $new_file_name = Date("YmdHis") . "-$petName.$extension";
            $directory = "uploadsPet";
            $photos = "photos";

            $upload = "$photos/$directory/$new_file_name";

            if (!is_dir("$photos/$directory"))
                mkdir("$photos/$directory", 0755, true);


            if (!file_exists($upload)) {
                if (!move_uploaded_file($file_temp, $upload)) {
                    $responseMessage = "Failed to upload file.";
                    $error = true;
                }
            } else {
                $responseMessage = "File already exists.";
                $error = true;
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$error) {
    if (empty($petName) || empty($species)) {
        $responseMessage = "Pet name and species are required.";
        $error = true;
    } else {
        $sql = "INSERT INTO pet(id_user, id_vet, pet_name, age, species, breed, photo) VALUES (:id_user, NULL, :name, :age, :species, :breed, :photo)";
        $query = $pdo->prepare($sql);
        $query->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $query->bindParam(':name', $petName, PDO::PARAM_STR);
        $query->bindParam(':age', $age, PDO::PARAM_STR);
        $query->bindParam(':species', $species, PDO::PARAM_STR);
        $query->bindParam(':breed', $breed, PDO::PARAM_STR);
        $query->bindParam(':photo', $new_file_name, PDO::PARAM_STR);

        if (!$query->execute()) {
            $responseMessage = "Failed to insert data into the database.";
            $error = true;
        }
    }
}

$response = [
    "message" => $responseMessage,
    "status" => $error ? 'ERROR' : 'OK'
];

echo json_encode($response);


