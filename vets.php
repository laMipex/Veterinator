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
    <link rel="stylesheet" href="styless/userProfile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        th,td,tr{
            padding: 5px;
            margin:5px;
            border: 2px solid black;
        }
        td{
            padding: 25px;
        }
        th{
            text-align: center;
        }


        .error {
            color: red;

        }

        .error #mailVet {
            border: 1px solid #f00;
        }





    </style>

</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php getNavbar($id_user, $id_admin, $id_vet); ?>



<div class="container mt-5">

    <div class="row">
        <div class="col-6 ">
            <div id="result"></div>
            <button class="btn btn-primary" id="insert">Insert Vet</button>
            <div class="formField" style="display: none">
                <h2 class="my-5">Send link for vets registration</h2>
                <label for="mailVet" >Email:</label><br>
                <input type="text" id="mailVet" class="mb-3" style="width: 300px;height: 60px" ><br>
                <small></small>
                <button class="btn btn-primary mb-5" id="sendMailButton">Send</button>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-6 mt-5 d-flex ">
            <h1 class="dH mt-5">Our Doctors</h1>
        </div>
        <div class="col-6 my-5 d-flex justify-content-end">
            
          
        </div>
    </div>
</div>




<!--Doctors-->

        <?php

        $query = $pdo->prepare("SELECT * FROM vet WHERE active = 1 AND is_banned = 0 ORDER BY vet_fname ASC,vet_lname ASC  ");
        $query->execute();
        $vets = $query->fetchAll();

echo '<div class="container mb-5">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-4">';

if ($query->rowCount() > 0) {
    foreach ($vets as $vet) {

        $query2 = $pdo->prepare("SELECT s.service_name, vs.id_service 
                                      FROM vet v 
                                      INNER JOIN vet_services vs
                                      ON v.id_vet = vs.id_vet
                                      INNER JOIN services s 
                                      ON s.id_service = vs.id_service
                                      WHERE v.id_vet = :id_vet
                                      ORDER BY s.service_name ASC");

        $query2->bindParam(':id_vet', $vet['id_vet'], PDO::PARAM_STR);
        $query2->execute();
        $services = $query2->fetchAll();

        $query3 = $pdo->prepare( "SELECT id_service, service_name
                                        FROM services ORDER BY service_name ASC");
        $query3->execute();
        $notChosenServices = $query3->fetchAll();

        // Formatiranje vremena
        $work_start = date('H:i', strtotime($vet['work_start']));
        $work_end = date('H:i', strtotime($vet['work_end']));


        // Provera da li postoji slika
        $photo = !empty($vet['photo']) ? 'photos/uploads/'.$vet['photo'] : 'photos/uploads/default_vet.png';


        echo '
        <div class="col">
            <div class="card" data-id="'. $vet['id_vet'] .'">
               <img class="card-img-top" src="' . $photo . '" alt="Card Vet" height="200" style="padding: 20px;border-radius: 50px">
                <div class="card-body">
                    <h4 class="card-title">' . $vet['vet_fname'] . " ". $vet['vet_lname'] .'</h4>
                    <div class="card-info">
                        <label for="vet_email">Email:</label>
                        <p class="card-text vet_email">' . $vet['vet_email'] . '</p>
                    </div>
                    <div class="card-info">
                        <label for="vet_phone">Phone:</label>
                        <p class="card-text vet_phone">0' . $vet['vet_phone'] . '</p>
                    </div>
                    <div class="card-info">
                        <label for="city">City:</label>
                        <p class="card-text city">' . $vet['city'] . '</p>
                    </div>
                    <div class="card-info">
                        <label for="vet_specialization">Specialization:</label>
                        <p class="card-text vet_specialization">' . $vet['specialization'] . '</p>
                    </div>
                     <div class="card-info">
                        <label for="work_time">Work time:</label>
                        <p class="card-text work_time">' . $work_start . " - " . $work_end .'</p>
                        
                    </div>
                     <div class="card-info mb-3">
                        <label for="vet_services" class="form-label">Services</label>
                        <select name="vet_services" class="vet_services" class="form-select">';
                            if ($query2->rowCount() > 0) {

                                foreach ($services as $service) {
                                    echo '<option value="' . $service['id_service'] . '">' . $service['service_name'] . '</option>';
                                }
                            }
                            else{
                                echo '<option value="-1">- no services yet -</option>';
                            }

                                echo '          </select>
                    </div>
                    <input type="hidden" class="id_vet" value="'. $vet['id_vet'] .'">
                    <button type="button" class="edit_vet_btn btn btn-primary" data-bs-toggle="modal" data-bs-target=".editContainer" >Edit</button>
                    <button type="button" class="bann_vet_btn btn btn-primary ms-3">Bann</button       
                    <small></small>
                    
                    
                    
                     <!--Edit-->
                     <div class="modal fade editContainer" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editForm" method="post" enctype="multipart/form-data">
                    
                                        <div class="form-group">
                                            <label for="vet_fname">Name:</label>
                                            <input type="text" class="form-control" id="vet_fname" name="vet_fname" value="'.$vet['vet_fname'] .'">
                                        </div>
                                        <div class="form-group">
                                            <label for="vet_lname">Last name:</label>
                                            <input type="text" class="form-control" id="vet_lname" name="vet_lname" value="'.$vet['vet_lname'] .'">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="edit_vet_email">Email</label>
                                            <input type="email" class="form-control" id="edit_vet_email" name="vet_email" value="'.$vet['vet_email'] .'">
                                        </div>
                                      
                
                                        <div class="form-group">
                                            <label for="edit_vet_phone">Phone</label>
                                            <input type="text" class="form-control" id="edit_vet_phone" name="vet_phone" value="0'.$vet['vet_phone'] .'">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_city">City</label>
                                            <input type="text" class="form-control" id="edit_city" name="city" value="'.$vet['city'] .'">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_specialization">Specialization:</label>
                                            <input type="text" class="form-control" id="edit_specialization" name="specialization" value="'.$vet['specialization'] .'">
                                        </div>
                                        <div class="form-group">
                                            <label for="work_start">Work Start</label>
                                            <input type="text" class="form-control" id="edit_work_start" name="work_start" value="' .$work_start .'">
                                        </div>
                                        <div class="form-group">
                                            <label for="work_end">Work End</label>
                                            <input type="text" class="form-control" id="edit_work_end" name="work_end" value="'. $work_end .'">
                                        </div>
                                         
                                        <div class="form-group">
                                           <label for="current_photo_edit">Current Photo</label>
                                            <img id="current_photo_edit" src="" alt="Profile Photo" width="70" height="70" style="border-radius: 50px">
                                            <input type="file" class="form-control" id="edit_photo" name="file">
                
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="edit_vet_services" class="form-label">Services</label>
                                            <select name="edit_vet_services" id="edit_vet_services" class="form-select"></select>
                                        </div>

                                     
                                       <div class="form-group">
                                            <label for="vet_services" class="form-label">Available Services</label>
                                            <select name="vet_services[]" id="vet_services" class="form-select" multiple>';

                                                if ($query3->rowCount() > 0) {
                                                    echo '<option value="-1">- Choose vet service -</option>';
                                                    foreach ($notChosenServices as $notservice) {
                                                        echo '<option value="' . $notservice['id_service'] . '">' . $notservice['service_name'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="-1">- No services available -</option>';
                                                }

                                           echo ' </select>
                                        </div>
                                                                              
                                 
                                      <input type="hidden" class="id_vet" value="'. $vet['id_vet'] .'">
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="save-button">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                                                                                                                              
                </div>
            </div>
        </div>';
    }
} else {
    echo '<h1>Sorry, no vets yet!</h1>';
}

echo '</div>
    </div>';

echo '<style>

    card-title{
        font-size: 17pt;
        margin-bottom:25px;
    }
    .card-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .card-info label {
        margin-right: 10px;
        font-style: italic;
        font-size: 9pt;
    }
    .card-info p {
        margin: 0;
    }
    
  
</style>';
    ?>

        <?php include "parts/footer.php"; ?>

        <script src="js/vets.js"></script>
</body>
</html>
