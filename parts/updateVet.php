<?php
session_start();
require "../db_config.php";
require "functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_vet'])) {
    $id_vet = $_POST['id_vet'];
    $updateFields = [];
    $updateValues = ['id' => $id_vet];

    // Ažuriranje osnovnih informacija o veterinaru
    if (isset($_POST['vet_fname'])) {
        $vet_fname = $_POST['vet_fname'];
        $updateFields[] = 'vet_fname = :vet_fname';
        $updateValues['vet_fname'] = $vet_fname;
    }
    if (isset($_POST['vet_lname'])) {
        $vet_lname = $_POST['vet_lname'];
        $updateFields[] = 'vet_lname = :vet_lname';
        $updateValues['vet_lname'] = $vet_lname;
    }
    if (isset($_POST['vet_email'])) {
        $vet_email = $_POST['vet_email'];
        $updateFields[] = 'vet_email = :vet_email';
        $updateValues['vet_email'] = $vet_email;
    }
    if (isset($_POST['vet_phone'])) {
        $vet_phone = $_POST['vet_phone'];
        $updateFields[] = 'vet_phone = :vet_phone';
        $updateValues['vet_phone'] = $vet_phone;
    }

    // Ažuriranje fotografije veterinaru, ako je uploadovana nova slika
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $edit_photo = $_FILES['file'];
        $file_temp = $edit_photo['tmp_name'];
        $file_name = $edit_photo['name'];

        if (exif_imagetype($file_temp)) {
            $ext_temp = explode(".", $file_name);
            $extension = end($ext_temp);
            $new_file_name = Date("YmdHis") . "-$vet_fname.$extension";
            $upload = "../photos/uploads/$new_file_name";

            if (!file_exists($upload)) {
                if (move_uploaded_file($file_temp, $upload)) {
                    $updateFields[] = 'photo = :photo';
                    $updateValues['photo'] = $new_file_name;
                } else {
                    echo 'Error moving uploaded file!';
                    exit;
                }
            } else {
                echo 'File with this name already exists!';
                exit;
            }
        } else {
            echo 'File is not a picture!';
            exit;
        }
    }

    if (isset($_POST['specialization'])) {
        $vet_specialization = $_POST['specialization'];
        $updateFields[] = 'specialization = :specialization';
        $updateValues['specialization'] = $vet_specialization;
    }
    if (isset($_POST['city'])) {
        $city = $_POST['city'];
        $updateFields[] = 'city = :city';
        $updateValues['city'] = $city;
    }

    if (isset($_POST['work_start'])) {
        $work_start = $_POST['work_start'];
        $updateFields[] = 'work_start = :work_start';
        $updateValues['work_start'] = $work_start;
    }
    if (isset($_POST['work_end'])) {
        $work_end = $_POST['work_end'];
        $updateFields[] = 'work_end = :work_end';
        $updateValues['work_end'] = $work_end;
    }


    // Izabrane usluge za brisanje
    $selected_services_delete = $_POST['edit_vet_services'] ?? null;

    if ($selected_services_delete !== null && $selected_services_delete != -1) {
        // Provera da li je $selected_services_delete niz
        if (!is_array($selected_services_delete)) {
            $selected_services_delete = [$selected_services_delete];
        }

        // Ako nije prazan niz, izvršite brisanje
        if (!empty($selected_services_delete)) {
            $deleteQuery = "DELETE FROM vet_services WHERE id_vet = :id_vet AND id_service = :id_service";
            $deleteStmt = $pdo->prepare($deleteQuery);

            foreach ($selected_services_delete as $service_id) {
                $deleteStmt->execute(['id_vet' => $id_vet, 'id_service' => $service_id]);
            }
        }
    }

// Izabrane nove usluge za dodavanje
    $selected_services = $_POST['vet_services'] ?? [];
    if (!empty($selected_services)) {
        // Provera da li postoji vrednost -1 i ako postoji, uklonite je
        $selected_services = array_filter($selected_services, function($service_id) {
            return $service_id != -1;
        });

        if (!empty($selected_services)) {
            $insertQuery = "INSERT INTO vet_services (id_vet, id_service) VALUES (:id_vet, :id_service)";
            $insertStmt = $pdo->prepare($insertQuery);

            foreach ($selected_services as $service_id) {
                $insertStmt->execute(['id_vet' => $id_vet, 'id_service' => $service_id]);
            }
        }
    }



    // Izvršavanje ažuriranja informacija o veterinaru
    if (!empty($updateFields)) {
        $query = "UPDATE vet SET " . implode(', ', $updateFields) . " WHERE id_vet = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute($updateValues);
        echo 'Updated successfully';
    } else {
        echo 'No fields to update';
    }
} else {
    echo 'Invalid request method or missing id_vet';
}
