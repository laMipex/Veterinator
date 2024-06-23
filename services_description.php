<?php
session_start();
ob_start();

require "db_config.php";
require "parts/functions.php";

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Mihajlo Baranovski, Milana Sabovljev">
        <meta name="description" content="Veterinary Practice">
        <meta name="keyword" content="animals, care, Veterinary">
        <meta name="robots" content="index, follow">
        <link rel="icon" type="image/x-icon" href="photos/index_photos/icon.png">
        <link rel="stylesheet" href="styless/navBar.css">
        <link rel="stylesheet" href="styless/logIn.css">
        <link rel="stylesheet" href="styless/hover-min.css">
        <link rel="stylesheet" href="styless/descStyle.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <title>Veterinary Practice - Veterinator</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>


        <script src="js/schedule_treatment.js"></script>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Include Bootstrap library -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Include Bootstrap Datepicker library -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

        <!-- Include Timepicker library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>


        <style>

            .error {
                color: red;

            }

            .error #service {
                border: 1px solid #f00;
            }

            .error #vet {
                border: 1px solid #f00;
            }
            .error #date {
                border: 1px solid #f00;
            }

            button #savePet{
                padding: 5px;
            }
            #file
            {
                display: none;
            }

        </style>

    </head>
    <body>
<div data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

    <?php
    $id_admin = $_SESSION['id_admin'] ?? "";
    $id_user = $_SESSION['id_user'] ?? "";
    $id_vet = $_SESSION['id_vet'] ?? "";
    getNavbarBlack($id_user, $id_admin, $id_vet);

    $sql = "SELECT * FROM services ORDER BY service_name ASC";
    $query = $pdo->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        echo '<div class="bd container">';
        echo '<div class="row">';
        foreach ($results as $result) {
            $id_service = $result->id_service;
            $serviceName = $result->service_name;
            $description = $result->service_description;
            $duration = $result->service_duration;
            $price = $result->price;
            $discount = $result->discount ?? "";
            $image_path = $result->photo;
            $available_date = $result->service_date ?? "";


            echo '<div class="col-md-6 col-lg-4 my-3">';
            echo '<div class="card service-card">';
            echo '<img src="photos/uploads/' . $image_path . '" class="card-img-top service-img" alt="Service Image">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $serviceName . '</h5>';
            echo '<p class="card-text">' . $description . '</p>';
            echo '<p class="card-text"><small class="text-muted">Duration: ' . $duration . '</small></p>';
            echo '<p class="card-text"><small class="text-muted">Price: $' . $price . '</small></p>';

            if($id_user) {
                $sql = "SELECT * FROM vet v INNER JOIN vet_services vs ON v.id_vet = vs.id_vet WHERE vs.id_service = :id_service";
                $query = $pdo->prepare($sql);
                $query->bindParam(':id_service', $id_service, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ); // PDO::FETCH_ASSOC
                //var_dump($results);

                if ($query->rowCount() > 0) {

                   // echo '<div class="row justify-content-center align-items-center text-center my-5">';
                    //echo '<div class="col-4">';
                    echo '<select name="vet" id="vet"  class="form-select my-3 mx-auto" style="width: auto;">';
                    echo '<option value="-1">- Choose a vet -</option>';

                    foreach ($results as $result) {
                        echo '<option value="' . $result->id_vet . '">' . $result->vet_fname . " " . $result->vet_lname . '</option>';
                    }
                    echo '</select>';
                }
                echo '<p class="card-text"><small class="text-muted">Discount: $' . $discount . '</small></p>';
//                echo '<p class="card-text"><small class="text-muted">Available Date: ' . $available_date . '</small></p>';
//                echo '<div class="date-time-input">';
//                echo '<input type="date" class="form-control my-2" min="2024-06-10" max="2024-07-10">';
//              echo '<input type="time" class="form-control" min="08:00" max="21:00">';
                echo '     <label for="date" class="card-text">Avaliable date:</label>
                                <div class="formField" style="display: flex">
                                    <input type="text" name="date" id="date" class="datepicker " autocomplete="off"  style="width: 100px">
                                    <small class="text-muted"></small>
                                </div>
                            <label for="time" class="card-text">Avaliable time:</label>
                                <div class="formField" style="display: flex">
                                    <input type="text" id="time" class="timepicker " style="width: 100px"><br><br>
                                    <small></small><br>
                                </div>';
              //  echo '</div>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>
<div class="space"></div>

<?php
include "parts/footer.php";
?>
</body>
</html>

