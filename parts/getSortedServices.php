<?php
session_start();
require "../db_config.php";
require "functions.php";


$id_user = $_SESSION['id_user'] ?? "";
$sortType = isset($_GET['sort']) ? $_GET['sort'] : 'asc';

// Izvrši SQL upit za sortiranje po ceni
$sql = "SELECT * FROM services ORDER BY price " . ($sortType === 'desc' ? 'DESC' : 'ASC');
$query = $pdo->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Generiši HTML za prikaz sortiranih rezultata
if ($query->rowCount() > 0) {
    foreach ($results as $result) {
        $id_service = $result->id_service;
        $serviceName = $result->service_name;
        $description = $result->service_description;
        $duration = $result->service_duration;
        $price = $result->price;
        $discount = $result->discount ?? "";
        $image_path = $result->photo;
        $available_date = $result->service_date ?? "";

        // Generišite HTML kartice za svaki rezultat
        echo '<div class="col-md-6 col-lg-4 my-3">';
        echo '<div class="card service-card">';
        echo '<img src="photos/uploads/' . $image_path . '" class="card-img-top service-img" alt="Service Image">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title service_name">' . $serviceName . '</h5>';
        echo '<p class="card-text">' . $description . '</p>';

        list($hours, $minutes, $seconds) = explode(':', $duration);
        $totalMinutes = $hours * 60 + $minutes;

        if ($totalMinutes == 59) {
            $totalMinutes += 1;
        }
        echo '<p class="card-text"><small class="text-muted">Duration: ' . $totalMinutes . " min" .  '</small></p>';
        echo '<p class="card-text price">' . $price . " $" . '</p>';

        if ($id_user) {
            echo '<input type="hidden" class="service-id" value="' . $id_service . '">';
            echo '<button type="button" class="btn btn-primary book-procedure" data-bs-toggle="modal" data-bs-target="#bookProcedureModal">Book procedure</button>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No services found.</p>';
}
?>
