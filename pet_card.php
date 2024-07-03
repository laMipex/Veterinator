<?php
session_start();

require "db_config.php";
require "parts/functions.php";

$id_admin = $_SESSION['id_admin'] ?? "";
$id_user = $_SESSION['id_user'] ?? "";
$id_vet = $_SESSION['id_vet'] ?? "";

$pet_id = isset($_GET['pet_id']) ? $_GET['pet_id'] : null;

if ($pet_id) {
    $sql = "SELECT * FROM pet WHERE id_pet = :pet_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':pet_id', $pet_id, PDO::PARAM_INT);
    $query->execute();
    $pet = $query->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        echo "Pet not found.";
        exit;
    }

    $sql_procedures = "
        SELECT s.service_name, CONCAT(v.vet_fname, ' ', v.vet_lname) AS vet_name,
            dp.diagnosis,dp.prescribed_medication,dp.pet_condition,dp.procedure_duration ,dp.procedure_date
        FROM done_procedures dp
        INNER JOIN reservations r 
        ON dp.id_reservation = r.id_reservation
        INNER JOIN  services s 
        ON s.id_service = r.id_service
        INNER JOIN vet v 
        ON v.id_vet = r.id_vet
        WHERE r.id_pet = :pet_id
    ";
    $query_procedures = $pdo->prepare($sql_procedures);
    $query_procedures->bindParam(':pet_id', $pet_id, PDO::PARAM_INT);
    $query_procedures->execute();
    $procedures = $query_procedures->fetchAll(PDO::FETCH_ASSOC);
}
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
    <title>Pet Card - <?php echo $pet['pet_name']; ?></title>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php getNavbar($id_user, $id_admin, $id_vet); ?>

<center><h1 class="mt-5">Pet Card</h1></center>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-9"> 
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="photos/uploadsPet/<?php echo $pet['photo']; ?>" alt="<?php echo $pet['pet_name']; ?>" class="img-fluid rounded-start">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title h1 text-decoration-underline"><?php echo $pet['pet_name']; ?></h5>
                            <p class="card-text h3"><strong>Species:</strong> <?php echo $pet['species']; ?></p>
                            <p class="card-text h3"><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
                            <p class="card-text h3"><strong>Age:</strong> <?php echo $pet['age']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($procedures) : ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Procedure Details:</h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($procedures as $procedure) :
                            list($hours, $minutes, $seconds) = explode(':', $procedure['procedure_duration']);
                            // Konverzija u numeričke vrednosti
                            $hours = (int) $hours;
                            $minutes = (int) $minutes;

                            // Izračun ukupnog broja minuta
                            $totalMinutes = $hours * 60 + $minutes;


                            if ($totalMinutes == 59) {
                                $totalMinutes += 1;
                            }?>

                            <div class="row my-5">
                                <div class="col-md-6 mb-3">
                                    <p class="fs-5"><strong>Procedure:</strong> <?php echo $procedure['service_name']; ?></p>
                                    <p class="fs-5"><strong>Veterinarian:</strong> <?php echo $procedure['vet_name']; ?></p>
                                    <p><strong>Procedure Duration:</strong> <?php echo $totalMinutes ?>min</p>
                                    <p><strong>Procedure Date:</strong> <?php echo $procedure['procedure_date']; ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p><strong>Diagnosis:</strong> <?php echo $procedure['diagnosis']; ?></p>
                                    <p><strong>Prescribed Medication:</strong> <?php echo $procedure['prescribed_medication']; ?></p>
                                    <p><strong>Pet Condition:</strong> <?php echo $procedure['pet_condition']; ?></p>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<br><br>
<?php include "parts/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/pets.js"></script>

</body>
</html>
