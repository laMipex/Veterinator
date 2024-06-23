<?php
session_start();

require "db_config.php";
require "parts/functions.php";

$id_admin = $_SESSION['id_admin'] ?? "";
$id_user = $_SESSION['id_user'] ?? "";
$id_vet = $_SESSION['id_vet'] ?? "";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mihajlo Baranovski, Milana Sabovljev">
    <meta name="description" content="Veterinary Practice">
    <meta name="keywords" content="animals, care, Veterinary">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="photos/index_photos/icon.png">
    <link rel="stylesheet" href="styless/navBar.css">
    <link rel="stylesheet" href="styless/logIn.css">
    <link rel="stylesheet" href="styless/hover-min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Veterinary Practice - Veterinator</title>
    <style>
        .error {
            color: red;
        }

        .error input {
            border: 1px solid #f00;
        }

        button#savePet {
            padding: 5px;
        }

        #file {
            display: none;
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php getNavbar($id_user, $id_admin, $id_vet); ?>
<?php
if($id_user){

$sql = "SELECT * FROM pet p INNER JOIN users u ON u.id_user=p.id_user  WHERE u.id_user = :id_user";
$query = $pdo->prepare($sql);
$query->bindParam(':id_user', $id_user, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ); // PDO::FETCH_ASSOC



$sql_treatments = "SELECT p.pet_name, CONCAT(v.vet_fname, ' ', v.vet_lname) AS vet_name, p.photo, r.reservation_date, r.reservation_time, r.treatment_price 
                        FROM reservations r 
                        INNER JOIN pet p ON r.id_pet = p.id_pet
                        INNER JOIN users u ON u.id_user = p.id_user 
                        INNER JOIN vet v ON r.id_vet = r.id_vet
                        WHERE u.id_user = :id_user";
$query_treatments = $pdo->prepare($sql_treatments);
$query_treatments->bindParam(':id_user', $id_user, PDO::PARAM_STR);
$query_treatments->execute();
$results_treatments = $query_treatments->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container my-5">
    <button type="button" id="addPet" class="btn btn-primary mx-5 my-5">Add Pet</button>
    <form enctype="multipart/form-data" id="insertPetForm" style="display: none;">
        <div class="row">
            <div class="col-12 col-md-6 mx-5 pt-3">
                <label for="pet_name" class="form-label">Pet name:</label>
                <input type="text" class="form-control" id="pet_name" placeholder="Pet Name" name="pet_name">
                <small></small>
            </div>
            <div class="col-12 col-md-6 mx-5 pt-3">
                <label for="age" class="form-label">Pet age:</label>
                <input type="text" class="form-control" id="age" placeholder="Age" name="age">
                <small></small>
            </div>
            <div class="col-12 col-md-6 mx-5 pt-3">
                <label for="species" class="form-label">Pet species:</label>
                <input type="text" class="form-control" id="species" placeholder="Species" name="species">
                <small></small>
            </div>
            <div class="col-12 col-md-6 mx-5 pt-3">
                <label for="breed" class="form-label">Pet breed:</label>
                <input type="text" class="form-control" id="breed" placeholder="Breed" name="breed">
                <small></small>
            </div>
            <div class="col-12 col-md-6 mx-5 pt-3">
                <label for="file" class="form-label">Pet photo:
                    <img src="photos/uploads/upload.png" alt="upload" width="50" title="Choose an image"></label>
                <input type="file" name="file" id="file" class="form-control">
                <small></small>
            </div>
        </div>
        <div class="pt-3">
            <button type="submit" class="btn btn-primary align-middle" id="savePet">Save</button>
        </div>
    </form>
</div>

<div class="container my-5">
    <h1 class="my-5">Your pets</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($results as $result) : ?>
            <div class="col">
                <div class="card">
                    <img class="card-img-top" src="photos/uploadsPet/<?= $result->photo ?>" alt="Card image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $result->pet_name ?></h5>
                        <p>Izabrani lekar: <?= $result->id_vet ?></p>
                        <a href="#" class="btn btn-primary" id="editPet">Pet Card</a><br>
<!--                        <a href="edit_profile.php?id_pet=--><?php //= $result->id_pet ?><!--" class="btn btn-primary popup">Edit profile</a><br>-->
<!--                        <a href="schedule_treatment.php?id_pet=--><?php //= $result->id_pet ?><!--" class="btn btn-primary">Book procedure</a>-->
                        <!-- Edit Profile Form -->
                        <form action="edit_profile.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_pet" value="<?= htmlspecialchars($result->id_pet) ?>">
                            <button type="submit" class="btn btn-primary popup">Edit profile</button>
                        </form>
                        <br>

                        <!-- Schedule Treatment Form -->
                        <form action="schedule_treatment.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_pet" value="<?= htmlspecialchars($result->id_pet) ?>">
                            <button type="submit" class="btn btn-primary">Book procedure</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container-fluid my-5">
    <h1>Scheduled treatments:</h1>
    <div class="row">
        <?php foreach ($results_treatments as $result) : ?>
            <div class="col-4 my-5">
                <img src="photos/uploadsPet/<?= $result->photo ?>" alt="Pet Image" width="200" height="100"><br><br>
                <p name="Petname">Pet name: <?= $result->pet_name ?></p>
                <p name="Vetname">Vet name: <?= $result->vet_name ?></p>
                <p name="date">Date: <?= $result->reservation_date ?></p>
                <p name="time">Time: <?= $result->reservation_time ?></p>
                <p name="price">Price: <?= $result->treatment_price ?></p>
                <button class="btn btn-primary" id="cancel">Cancel treatment</button>
                <hr>
            </div>
        <?php endforeach; ?>
    </div>
</div>
    <?php

} elseif ($id_vet){
    $sql = "SELECT * FROM pet";
    $query = $pdo->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);


?>

<div class="container my-5">
    <h1 class="my-5">All pets</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($results as $result) : ?>
            <div class="col">
                <div class="card">
                    <img class="card-img-top" src="photos/uploadsPet/<?= $result->photo ?>" alt="Card image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $result->pet_name ?></h5>
                        <p>Izabrani lekar: <?= $result->id_vet ?></p>
                        <a href="#" class="btn btn-primary" id="editPet">Pet Card</a><br>
                        <form action="edit_profile.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_pet" value="<?= htmlspecialchars($result->id_pet) ?>">
                            <button type="submit" class="btn btn-primary popup">View profile</button>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php


}?>

<?php include "parts/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/pets.js"></script>

</body>

</html>
