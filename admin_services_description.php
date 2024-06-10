<?php
session_start();
ob_start();
require_once "parts/functions.php";
require_once "db_config.php";


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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="js/script.js"></script>

</head>
<body>
<div data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

    <?php
    $id_admin = $_SESSION['id_admin'] ?? "";
    $id_user = $_SESSION['id_user'] ?? "";
    $id_vet = $_SESSION['id_vet'] ?? "";
    getNavbar($id_user,$id_admin,$id_vet);
    ?>

    <form action="admin_service_forms.php" class="d-flex mx-5 my-5">
        <input type="hidden" name="action" value="insert">
        <button id="btnS" class="btn btn-primary" type="submit" id="insert">Insert</button>
    </form>

    <?php $r = 0;

    if (isset($_GET["r"]) and is_numeric($_GET['r'])) {
    $r = (int)$_GET["r"];

    if (array_key_exists($r, $messages)) {
    echo '
    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
        ' . $messages[$r] . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
    ';
    }
    }
    ?>
    <?php


    $sql = "SELECT * FROM services ORDER BY service_name ASC";
    $query = $pdo->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ); // PDO::FETCH_ASSOC

    echo '<div class="container">';

    if ($query->rowCount() > 0) {

        foreach ($results as $result) {

            $id_service = $result ->id_service;
            $serviceName = $result->service_name;
            $description = $result->service_description;
            $duration = $result->service_duration;
            $image_path = $result->photo;

            echo '
                <div class="row mx-5 my-5">
                <div class="col-12 col-md-4">
                    <p name="service_name">Service name:' . $serviceName .'</p>
                    <p name="service_description">Service description:' . $description .'</p>
                    <p name="service_duration">Service duration:' . $duration .'</p>                                                  
                 </div>
                 <div class="col-10 col-md-4">
                    <img src="photos/uploads/' . $image_path . '" alt="Service Image" width="250" height="200"><br><br>                
                 </div>                 
                  <div class="col-2  col-md-4">                                  
                    <form action="admin_service_forms.php" class="d-flex justify-content-end">
                         <input type="hidden" name="id_service" value="' . $id_service . '">
                        <input type="hidden" name="action" value="update">
                        <button id="update" class="btn btn-primary" type="submit">Update</button><br><br>                   
                    </form>
                    <form action="admin_service_forms.php" class="d-flex justify-content-end">
                         <input type="hidden" name="id_service" value="' . $id_service . '">
                        <input type="hidden" name="action" value="delete">
                        <button id="delete" class="btn btn-primary" type="submit" >Delete</button>;
                             
                    </form>    
                                
                  </div>
                  
                  <hr> 
        
                </div>
                ';

        }
    }
    echo '</div>'
    ?>
</div>



<?php
include "parts/footer.php";
?>
</body>
</html>

