<?php
session_start();
require "db_config.php";
require "parts/functions.php";

$action = $_POST['action'] ?? '';

if ($action == 'insert') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $photo = $_FILES['file'];


    // Check if any field is empty
    if (empty($name) || empty($description) || empty($duration) || empty($price)) {
        echo 'Please fill in all fields.';
        exit;
    }

    if ($_FILES['file']["error"] > 0) {

        echo 'Something went wrong during file upload!';
        exit;

    } else {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {

            $file_name = $_FILES['file']["name"];
            $file_temp = $_FILES["file"]["tmp_name"];
            $file_size = $_FILES["file"]["size"];
            $file_type = $_FILES["file"]["type"];
            $file_error = $_FILES['file']["error"];


            if (!exif_imagetype($file_temp)){

                echo 'File is not a picture!!';
                exit;
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

                    echo 'Error';
                    exit;
                }
            } else {

                echo 'File with this name already exists!';
                exit;
            }

        }
    }


    // Insert into database
    $sql = "INSERT INTO services (service_name, service_description, service_duration, price, discount, photo) VALUES (:name, :description, :duration, :price, :discount, :photo)";
    $query = $pdo->prepare($sql);
    $query->execute([
        ':name' => $name,
        ':description' => $description,
        ':duration' => $duration,
        ':price' => $price,
        ':discount' => $discount,
        ':photo' => $photo['name']
    ]);
    echo "Service inserted successfully.";
}

if ($action == 'update') {
    $id_service = $_POST['id_service'];
    $name = $_POST['service_name'];
    $description = $_POST['service_description'];
    $duration = $_POST['service_duration'];
    $price = $_POST['service_price'];
    $discount = $_POST['service_discount'];
    $photo = $_FILES['file'];

    // Check if any field is empty
    if (empty($id_service) || empty($name) || empty($description) || empty($duration) || empty($price)) {
        echo 'Please fill in all fields.';
        exit;
    }

    // Process file upload if a new file is uploaded
    if ($_FILES['file']["error"] > 0) {

        echo 'Something went wrong during file upload!';
        exit;

    } else {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {

            $file_name = $_FILES['file']["name"];
            $file_temp = $_FILES["file"]["tmp_name"];
            $file_size = $_FILES["file"]["size"];
            $file_type = $_FILES["file"]["type"];
            $file_error = $_FILES['file']["error"];


            if (!exif_imagetype($file_temp)){

                echo 'File is not a picture!!';
                exit;
            }
            $ext_temp = explode(".", $file_name); //rasparcavamo po tackama da bi dobili koja je ekstenzija
            $extension = end($ext_temp);  //ovde uzimamo poslednji elemenat posle tacke, dakle ekstenziju

            $new_file_name = Date("YmdHis") . "-$name.$extension";

            $directory = "uploads";
            $photos = "photos";

            $upload = "$photos/$directory/$new_file_name";

            if (!file_exists($upload)) //images/back.png
            {
                if (!move_uploaded_file($file_temp, $upload)) {

                    echo 'Error';
                    exit;
                }
            } else {

                echo 'File with this name already exists!';
                exit;
            }

        }
    }


    // Update database
    $sql = "UPDATE services SET service_name = :name, service_description = :description, service_duration = :duration, price = :price, discount = :discount, photo = :photo WHERE id_service = :id_service";
    $query = $pdo->prepare($sql);
    $params = [
        ':name' => $name,
        ':description' => $description,
        ':duration' => $duration,
        ':price' => $price,
        ':discount' => $discount,
        ':id_service' => $id_service,
        ':photo' => $photo['name']
    ];

    $query->execute($params);
    echo "Service updated successfully.";
}

if ($action == 'delete') {
    $id_service = $_POST['id_service'];

    // Delete from database
    $sql = "DELETE FROM services WHERE id_service = :id_service";
    $query = $pdo->prepare($sql);
    $query->execute([':id_service' => $id_service]);
    echo "Service deleted successfully.";
}
?>
