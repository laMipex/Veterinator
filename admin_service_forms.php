<?php
session_start();

require "parts/functions.php";
//require "db_config.php";
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
    <link rel="stylesheet" href="styless/style.css">
    <link rel="stylesheet" href="styless/hover-min.css">
    <link rel="stylesheet" href="styless/navBar.css">
    <link rel="stylesheet" href="styless/logIn.css"
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="js/admin_service_forms.js"></script>
    
    <style>
        #photo
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
<div class="container mt-5">
    <?php

    $action = $_GET['action'] ?? '';
    $id_service = $_GET['id_service'] ?? '';

    switch ($action) {
        case 'insert':


            echo '<h2>Insert Service</h2>';
            echo '<div class="row">
                <form method="post" id="insertForm" name="insertForm"  action="admin_service_forms.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insert">
                    <div class="mb-3 col-11  col-md-6">
                        <label for="name" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="name" name="name" >
                        <br><small></small>
                    </div>
                    <div class="mb-3 col-11 col-md-6">
                        <label for="description" class="form-label">Service Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                        <br><small></small>
                    </div>
                    <div class="mb-3 col-11 col-md-6">
                        <label for="duration" class="form-label">Service Duration</label>
                        <input type="text" class="form-control" id="duration" name="duration">
                        <br><small></small>
                    </div>
                     <label for="photo" class="col-md-6"> Choose photo:
                    <img src="photos/uploads/upload.png" alt="upload" width="50" title="Choose an image">
                    </label>
                
                    <input type="file" name="file" id="photo"><br>
                    <small></small>
                    </div>';

            //ovo ukljucis ako hoces da vidis i ovde sliku
            /*if ($current_image) {
            echo '<img src="uploads/' . $current_image . '" alt="Service Image" width="100"><br>';
        }*/
            echo '                                                      
                     <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                     <button type="reset" class="btn btn-primary" id="reset">Reset</button>
                     
                </form>';

            $r = 0;

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
            break;
        case 'update':

            $sql = "SELECT * FROM services WHERE id_service = :id_service";
            $query = $pdo->prepare($sql);
            $query->bindParam(':id_service', $id_service, PDO::PARAM_STR);
            $query->execute();
            $service = $query->fetch(PDO::FETCH_OBJ);

           // if ($service) {
                $current_image = $service->photo;
                // Display the update form
                echo '
                    
                    <form action="admin_service_forms.php" id="updateForm" name="updateForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_service" value="' . $service->id_service . '">
                    <input type="hidden" name="action" value="save_update">
                    <div class="container">
                        <div class="row my-4">
                        <div class="col-2">
                            <label for="service_name">Service Name:</label></div>
                        <div class="col-5">
                            <input type="text" id="service_name" name="service_name" value="' . $service->service_name . '">
                            <br><small></small></div>
                        </div>
                        <div class="row my-4">
                            <div class="col-2">
                                <label for="service_description" >Service Description:</label></div>
                            <div class="col-5">
                                <textarea  id="service_description" name="service_description" class="align-middle">' . $service->service_description . '</textarea>                                
                                <br><small></small></div> <br>
                        </div>
                        <div class="row my-4">
                            <div class="col-2">
                                <label for="service_duration">Service Duration:</label></div>
                            <div class="col-5">
                                <input type="text" id="service_duration" name="service_duration"  value="' . $service->service_duration . '">
                                <br><small></small></div><br>                        
                        </div>
                    <label for="photo"> Choose photo:
                    <img src="photos/uploads/upload.png" alt="upload" width="50" title="Choose an image">
                    </label>
                
                    <input type="file" name="file" id="photo"><br>';
                //ovo ukljucis ako hoces da vidis i ovde sliku
                    /*if ($current_image) {
                    echo '<img src="uploads/' . $current_image . '" alt="Service Image" width="100"><br>';
                }*/
                echo '<button type="submit" class="btn btn-primary" id="submit">Save</button>
                                      
                </form>';

            //}
            $r = 0;

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
            break;


        case 'delete':
            // Delete the service
            $sql = "DELETE FROM services WHERE id_service = :id_service";
            $query = $pdo->prepare($sql);
            $query->bindParam(':id_service', $id_service, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                redirection("admin_services_description.php?r=19");
            } else {
                redirection("admin_services_description.php?r=20");
            }
            break;

        default:
            $r = 0;

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
            break;

    }
    ?>
</div>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';


    switch ($action) {
        case 'insert':
            $name = $_POST['name'];
            $description = $_POST['description'];
            $duration = $_POST['duration'];

            if ($_FILES['file']["error"] > 0) {
                $r = 21;
                redirection("admin_service_forms.php" . "?action=insert&r=$r");
            } else {
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                    $file_name = $_FILES['file']["name"];
                    $file_temp = $_FILES["file"]["tmp_name"];
                    $file_size = $_FILES["file"]["size"];
                    $file_type = $_FILES["file"]["type"];
                    $file_error = $_FILES['file']["error"];

                    echo exif_imagetype($file_temp) . "<br>";  //da li je slika

                    if (!exif_imagetype($file_temp)){
                        //exit("");
                        $r = 22;
                        redirection("admin_service_forms.php" . "?action=insert&r=$r");
                    }
                    $ext_temp = explode(".", $file_name); //rasparcavamo po tackama da bi dobili koja je ekstenzija
                    $extension = end($ext_temp);  //ovde uzimamo poslednji elemenat posle tacke, dakle ekstenziju

                    $new_file_name = Date("YmdHis") . "-$name.$extension";

                    $directory = "uploads";
                    $photos = "photos";

                    $upload = "$photos/$directory/$new_file_name";

                    if (!is_dir("$photos/$directory"))
                        mkdir("$photos/$directory", 0755, true);


                    if (!file_exists($upload)) //images/back.png
                    {
                        if (!move_uploaded_file($file_temp, $upload)) {
                            $r = 23;
                            redirection("admin_service_forms.php" . "?action=insert&r=$r");
                        }
                    } else {
                        $r = 24;
                        redirection("admin_service_forms.php" . "?action=insert&r=$r");
                    }

                }
            }



            $sql = "INSERT INTO services (service_name,service_description,photo,service_duration) VALUES (:name,:desc,:photo,:duration)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':desc', $description, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $new_file_name, PDO::PARAM_STR);
            $stmt->bindParam(':duration', $duration, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() > 0) {
                $r = 27;
                redirection("admin_service_forms.php?" . "action=insert&r=$r");

            } else {
                $r = 28;
                redirection("admin_service_forms.php?" . "action=insert&r=$r");
            }
         //echo '<div id="result"></div>';

            break;
        case 'save_update':

            $serviceName = $_POST['service_name'];
            $description = $_POST['service_description'];
            $duration = $_POST['service_duration'];
            $id = $_POST['id_service'];


            /*if ($_FILES['file']["error"] > 0) {
                $r = 21;
                redirection("admin_service_forms.php" . "?id_service=" . $id. "&action=update&r=$r");
            } else {*/
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                    $file_name = $_FILES['file']["name"];
                    $file_temp = $_FILES["file"]["tmp_name"];
                    $file_size = $_FILES["file"]["size"];
                    $file_type = $_FILES["file"]["type"];
                    $file_error = $_FILES['file']["error"];

                    echo exif_imagetype($file_temp) . "<br>";  //da li je slika

                    if (!exif_imagetype($file_temp)){
                        $r = 22;
                        redirection("admin_service_forms.php" . "?id_service=" . $id. "&action=update&r=$r");
                    }

                    $ext_temp = explode(".", $file_name); //rasparcavamo po tackama da bi dobili koja je ekstenzija
                    $extension = end($ext_temp);  //ovde uzimamo poslednji elemenat posle tacke, dakle ekstenziju

                    $new_file_name = Date("YmdHis") . "-$serviceName.$extension";

                    $directory = "uploads";
                    $photos = "photos";

                    $upload = "$photos/$directory/$new_file_name";

                    if (!is_dir("$photos/$directory"))
                        mkdir("$photos/$directory", 0755, true);

                    if (!file_exists($upload))
                    {
                        if (!move_uploaded_file($file_temp, $upload)){
                            $r = 23;
                            redirection("admin_service_forms.php" . "?id_service=" . $id. "&action=update&r=$r");
                        }

                    } else{
                       $r = 24;
                       redirection("admin_service_forms.php" . "?id_service=" . $id. "&action=update&r=$r");
                    }
                }
            //}


            $sql = "UPDATE services SET service_name = :service_name, service_description = :service_description, photo = :photo, service_duration = :service_duration WHERE id_service = :id_service";
            $query = $pdo->prepare($sql);

            $query->bindParam(':id_service', $id, PDO::PARAM_STR);
            $query->bindParam(':service_name', $serviceName, PDO::PARAM_STR);
            $query->bindParam(':service_description', $description, PDO::PARAM_STR);
            $query->bindParam(':photo', $new_file_name, PDO::PARAM_STR);
            $query->bindParam(':service_duration', $duration, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                $r = 25;
                redirection("admin_service_forms.php" . "?id_service=" . $id. "&action=update&r=$r");

            } else {
                $r = 26;
                redirection("admin_service_forms.php" . "?id_service=" . $id. "&action=update&r=$r");
            }
            break;


    }




}
?>
</body>
</html>