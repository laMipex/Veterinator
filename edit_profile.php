<?php
session_start();


require "db_config.php";
require"parts/functions.php";


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

    <style>

        .error {
            color: red;

        }

        .error #name {
            border: 1px solid #f00;
        }

        .error #age {
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

    <script src="js/edit_profile.js"></script>


</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php
$id_admin = $_SESSION['id_admin'] ?? "";
$id_user = $_SESSION['id_user'] ?? "";
$id_vet = $_SESSION['id_vet'] ?? "";
getNavbar($id_user,$id_admin,$id_vet);

?>

<?php
    if($id_user){

        if($id_pet){

            $sql = "SELECT * FROM pet WHERE id_pet = :id_pet";
            $query = $pdo->prepare($sql);
            $query->bindParam(':id_pet', $id_pet, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                foreach ($results as $result) {
                    $id_selected_pet = $result->id_pet;
                    $pet_name = $result->pet_name;
                    $age = $result->age;
                    $species = $result->species;
                    $breed = $result->breed;
                    $photo = $result->photo;

                    echo '
                        <div class="row justify-content-center align-items-center text-center my-5 ">
                            <div class="col-4">
                                <p name="pet_name">Pet name: ' . $pet_name . '</p>
                                <p name="age">Pet age: ' . $age . '</p>
                                <p name="species">Pet species: ' . $species . '</p>
                                <p name="breed">Pet breed: ' . $breed . '</p>                                                                                  
                            </div>
                            <div class="col-4">
                                <img src="photos/uploadsPet/' . $photo . '" alt="Pet Image" width="600" height="300"><br><br>
                            </div>
                                
                       </div>';
                }
            }

            $sql = "SELECT * FROM vet";
            $query = $pdo->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ); // PDO::FETCH_ASSOC
            //var_dump($results);

            if ($query->rowCount() > 0) {

                echo '<div class="row justify-content-center align-items-center text-center my-5">';
                echo '<div class="col-4">';
                echo '<select name="vet" id="vet"  class="form-select my-3 mx-auto" style="width: auto;">';
                echo '<option value="-1">- Choose a vet -</option>';

                foreach ($results as $result) {
                    echo '<option value="' . $result->id_vet . '">' . $result->vet_fname ." " .$result->vet_lname .'</option>';
                }
                echo '</select>';
                echo '<button type="submit" class="btn btn-primary my-3 mx-auto " style="width: 100px" id="chooseVet">Send</button>';
                echo '<input type="hidden" id="id_pet" value="' . $id_pet . '">';

                echo '</div></div>';

            }
        }




    }
    elseif ($id_vet) {
        $sql = "SELECT * FROM pet WHERE id_pet = :id_pet";
        $query = $pdo->prepare($sql);
        $query->bindParam(':id_pet', $id_pet, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $id_selected_pet = $result->id_pet;
                $pet_name = $result->pet_name;
                $age = $result->age;
                $species = $result->species;
                $breed = $result->breed;
                $photo = $result->photo;

                echo '
                        <div class="row justify-content-center align-items-center text-center my-5 ">
                            <div class="col-4">
                                <p name="pet_name">Pet name: ' . $pet_name . '</p>
                                <p name="age">Pet age: ' . $age . '</p>
                                <p name="species">Pet species: ' . $species . '</p>
                                <p name="breed">Pet breed: ' . $breed . '</p>                                                                                  
                            </div>
                            <div class="col-4">
                                <img src="photos/uploadsPet/' . $photo . '" alt="Pet Image" width="600" height="300"><br><br>
                            </div>
                                
                       </div>';
            }
        }


    }else{

    }

?>



<?php
include "parts/footer.php";
?>

</body>
</html>
