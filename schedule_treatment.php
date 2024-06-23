<?php
session_start();

require "db_config.php";
require "parts/functions.php";


$id_pet = $_POST['id_pet'] ?? null;
$_SESSION['id_pet'] = $id_pet;

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
    <link rel="stylesheet" href="styless/logIn.css"
    <link rel="stylesheet" href="styless/hover-min.css">
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
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php
$id_admin = $_SESSION['id_admin'] ?? "";
$id_user = $_SESSION['id_user'] ?? "";
$id_vet = $_SESSION['id_vet'] ?? "";
getNavbar($id_user,$id_admin,$id_vet);


?>


<?php
$sql = "SELECT * FROM pet WHERE id_pet = :id_pet";
$query = $pdo->prepare($sql);
$query->bindParam(':id_pet', $id_pet, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $id_selected_pet = $result->id_pet;
            $pet_name = $result->pet_name;
            $photo = $result->photo;

            echo '                     
                    <div class="row justify-content-center align-items-center text-center my-5 ">
                        <h1 class="mt-5">Book treatment for your pet:</h1>
                        <div class="col-4 my-5">
                            
                            <img src="photos/uploadsPet/' . $photo . '" alt="Pet Image" width="600" height="300"><br><br>
                            <p name="pet_name">Pet name: ' . $pet_name . '</p>
                        </div>';

        }
    }

        //select za services
        $sql = "SELECT * FROM services ORDER BY service_name ASC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ); // PDO::FETCH_ASSOC
        //var_dump($results);

        if ($query->rowCount() > 0) {
            echo '<div class="col-4 formField">';
            echo ' <small></small><br>';
            echo '<select name="service" id="service"  class="form-select my-3 mx-auto" style="width: auto;">';
            echo '<option value="-1">- Choose a service -</option>';
            foreach ($results as $result) {
                echo '<option value="' . $result->id_service . '">' . $result->service_name .'</option>';
            }

            echo '</select>';

        }


            //select za veterinara ovo je ajax schedule_treatment.js i u parts/vet_dropdown.php
            echo '<div class="formField">';
            echo ' <small></small><br>';
            echo '<select name="vet" id="vet" class="form-select my-3 mx-auto" style="width: auto;">';
            echo '<option value="-1">- Choose a vet -</option>';
            echo '</select></div>';




?>

<!--Datum i vreme isto rade AJAX schedule.js i parts/getVet_workTime-->
    <br><br>
    <label for="date">Choose date:</label>
        <div class="formField">
            <input type="text" name="date" id="date" class="form-control datepicker mx-auto" autocomplete="off"  style="width: 170px">
            <small></small><br>
        </div>
    <label for="time">Choose time:</label>
        <div class="formField">
            <input type="text" id="time" class="form-control timepicker mx-auto" style="width: 150px"><br><br>
            <small></small><br>
        </div>
        <div class="formField">
            <p>Price: <span id="service_price"></span>$</p>
        </div>

            <div class="formField">
            <button type="submit" class="btn btn-primary my-3 mx-auto " style="width: 100px" id="schedule">Schedule</button>
                <div id="result"></div>
        </div>


<?php
            echo '</div></div>';
?>



<?php
include "parts/footer.php";
?>

</body>
</html>
