<?php
session_start();
require "../db_config.php";
require "functions.php";

$id_vet = $_SESSION['id_vet'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prihvatanje podataka iz forme

    $vet_email = $_POST['vet_email'] ?? "";
    $vet_phone = $_POST['vet_phone'] ?? "";
    $city = $_POST['city'] ?? "";
    $work_start = $_POST['work_start'] ?? "";
    $work_end = $_POST['work_end'] ?? "";

    // Opciono, obrada slike
    $file = $_FILES['file'] ?? null;
    $fileName = $file['name'] ?? "";
    $fileTmpName = $file['tmp_name'] ?? "";

    // Validacija i obrada slike ako je potrebno
    // Primer:
    // move_uploaded_file($fileTmpName, 'photos/uploads/' . $fileName);

    // Upit za ažuriranje podataka u bazi
    $query = $pdo->prepare("UPDATE vet SET  vet_email = ?, vet_phone = ?, city = ?, work_start = ?, work_end = ? WHERE id_vet = ?");
    $query->execute([$vet_email, $vet_phone, $city, $work_start, $work_end, $id_vet]);

    if ($query) {
        // Ažuriranje uspešno
        $response = [
            'status' => 'success',
            'message' => 'Vet profile updated successfully.'
        ];
    } else {
        // Greška pri ažuriranju
        $response = [
            'status' => 'error',
            'message' => 'Failed to update vet profile.'
        ];
    }

    // Slanje odgovora nazad JavaScript-u
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

