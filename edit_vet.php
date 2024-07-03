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
    <link rel="icon" type="imcity/x-icon" href="photos/index_photos/icon.png">
    <link rel="stylesheet" href="styless/navBar.css">
    <link rel="stylesheet" href="styless/logIn.css">
    <link rel="stylesheet" href="styless/hover-min.css">
    <link rel="stylesheet" href="styless/userProfile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php getNavbar($id_user, $id_admin, $id_vet); ?>

<?php

$query = $pdo->prepare("SELECT * FROM vet WHERE id_vet = :id");
$query->execute(['id' => $id_vet]);
$vet = $query->fetch();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $old_password = $_POST['edit_vet_old_pass'] ?? null;
        $new_password = $_POST['edit_vet_new_pass'] ?? null;
        $vet_email = $_POST['vet_email'];
        $vet_phone = $_POST['vet_phone'];
        $city = $_POST['city'];
        $work_start = $_POST['work_start'];
        $work_end = $_POST['work_end'];
        $name = "";
        $parts = explode('@', $vet_email);
        if (count($parts) > 1) {
            $name = $parts[0]; // Deo pre @
        } else {
            echo 'Invalid email format';
            exit;
        }

        if ($old_password && $new_password)
        {
            $query2 = $pdo->prepare("SELECT vet_password FROM vet WHERE id_vet = :id");
            $query2->execute(['id' => $id_vet]);
            $vet2 = $query2->fetch();
            if (password_verify($old_password, $vet2['vet_password'])) {
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $updatePasswordQuery = $pdo->prepare("UPDATE vet SET vet_password = :vet_password,vet_email = :vet_email, vet_phone = :vet_phone, city = :city, work_start = :work_start, work_end = :work_end WHERE id_vet = :id");
                $updatePasswordQuery->execute([
                    'vet_password' => $new_password_hashed,
                    'vet_email' => $vet_email,
                    'vet_phone' => $vet_phone,
                    'city' => $city,
                    'work_start' => $work_start,
                    'work_end' => $work_end,
                    'id' => $id_vet
                ]);
                echo '"Password updated successfully");';
                $query->execute(['id' => $id_vet]);
                $vet = $query->fetch();
            } else {
                echo "Incorrect old password";
                $query->execute(['id' => $id_vet]);
                $vet = $query->fetch();
            }
        }


       else if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK && $old_password && $new_password)
       {
            $edit_photo = $_FILES['file'];
            $file_temp = $edit_photo['tmp_name'];
            $file_name = $edit_photo['name'];

            if (!exif_imagetype($file_temp)) {
                echo 'File is not a picture!!';
                $query->execute(['id' => $id_vet]);
                $vet = $query->fetch();
                exit;
            }

            $ext_temp = explode(".", $file_name);
            $extension = end($ext_temp);
            $new_file_name = Date("YmdHis") . "-$name.$extension";
            $upload = "photos/uploads/$new_file_name";

            if (!file_exists($upload)) {
                if (!move_uploaded_file($file_temp, $upload)) {
                    echo 'Error moving uploaded file!';
                    $query->execute(['id' => $id_vet]);
                    $vet = $query->fetch();
                    exit;
                }
            } else {
                echo 'File with this name already exists!';
                $query->execute(['id' => $id_vet]);
                $vet = $query->fetch();
                exit;
            }

           $query2 = $pdo->prepare("SELECT vet_password FROM vet WHERE id_vet = :id");
           $query2->execute(['id' => $id_vet]);
           $vet2 = $query2->fetch();
           if (password_verify($old_password, $vet2['vet_password'])) {
               $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
               $updatePasswordQuery = $pdo->prepare("UPDATE vet SET vet_password = :vet_password,vet_email = :vet_email, vet_phone = :vet_phone, city = :city, work_start = :work_start, work_end = :work_end , photo = :photoWHERE id_vet = :id");
               $updatePasswordQuery->execute([
                   'vet_password' => $new_password_hashed,
                   'vet_email' => $vet_email,
                   'vet_phone' => $vet_phone,
                   'city' => $city,
                   'work_start' => $work_start,
                   'work_end' => $work_end,
                   'photo' => $new_file_name,
                   'id' => $id_vet
               ]);

               echo 'Password and other data updated successfully';
               $query->execute(['id' => $id_vet]);
               $vet = $query->fetch();
           } else {
               echo "Incorrect old password";
               $query->execute(['id' => $id_vet]);
               $vet = $query->fetch();

           }

        }elseif(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK ){

           $edit_photo = $_FILES['file'];
           $file_temp = $edit_photo['tmp_name'];
           $file_name = $edit_photo['name'];

           if (!exif_imagetype($file_temp)) {
               echo 'File is not a picture!!';
               $query->execute(['id' => $id_vet]);
               $vet = $query->fetch();
               exit;
           }

           $ext_temp = explode(".", $file_name);
           $extension = end($ext_temp);
           $new_file_name = Date("YmdHis") . "-$name.$extension";
           $upload = "photos/uploads/$new_file_name";

           if (!file_exists($upload)) {
               if (!move_uploaded_file($file_temp, $upload)) {
                   echo 'Error moving uploaded file!';
                   $query->execute(['id' => $id_vet]);
                   $vet = $query->fetch();
                   exit;
               }
           } else {
               echo 'File with this name already exists!';
               $query->execute(['id' => $id_vet]);
               $vet = $query->fetch();
               exit;
           }

           $updateUQuery = $pdo->prepare("UPDATE vet SET vet_email = :vet_email, vet_phone = :vet_phone, city = :city, work_start = :work_start, work_end = :work_end, photo = :photo WHERE id_vet = :id");
           $updateUQuery->execute([
               'vet_email' => $vet_email,
               'vet_phone' => $vet_phone,
               'city' => $city,
               'work_start' => $work_start,
               'work_end' => $work_end,
               'photo' => $new_file_name,
               'id' => $id_vet
           ]);
           echo 'Updated successfully';
           $query->execute(['id' => $id_vet]);
           $vet = $query->fetch();
       }


       else {
            $updateUQuery = $pdo->prepare("UPDATE vet SET vet_email = :vet_email, vet_phone = :vet_phone, city = :city, work_start = :work_start, work_end = :work_end WHERE id_vet = :id");
            $updateUQuery->execute([
                'vet_email' => $vet_email,
                'vet_phone' => $vet_phone,
                'city' => $city,
                'work_start' => $work_start,
                'work_end' => $work_end,
                'id' => $id_vet
            ]);

            echo 'Updated successfully';
           $query->execute(['id' => $id_vet]);
           $vet = $query->fetch();
        }

    }
?>

<div class="container">
        <div class="profileContainer">
            <div class="profileHeader">
                <label for="vet_name" >Veterinarian: </label>
                <h2 id="vet_name"> <?php echo $vet['vet_fname'] . " " . $vet['vet_lname'];  ?></h2>
            </div>
            <div class="profileContent">
                <form id="profileForm" method="post">

                    <div class="form-group row">
                        <label for="vet_email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" readonly class="form-control" id="vet_email" name="vet_email" value="<?php echo ($vet['vet_email']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vet_phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="vet_phone" name="vet_phone" value="<?php echo ("0". $vet['vet_phone']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="city" class="col-sm-3 col-form-label">City</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="city" name="city" value="<?php echo ($vet['city']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="work_start" class="col-sm-3 col-form-label">Work Start</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="work_start" name="work_start" value="<?php echo ($vet['work_start']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="work_end" class="col-sm-3 col-form-label">Work SEnd</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="work_end" name="work_end" value="<?php echo ($vet['work_end']); ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="photo" class="col-sm-3 col-form-label">Photo</label>
                        <div class="col-sm-9">
                            <img id="photo" src="photos/uploads/<?php echo ($vet['photo']); ?>" alt="Profile Photo" width="100" height="100" style="border-radius: 50px">
                        </div>
                    </div>
                </form>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" id="edit-button">Edit Profile</button>
            </div>
        </div>
    </div>

    <!--Edit-->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel" >Edit Profile</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="edit_vet_email">Email</label>
                            <input type="email" class="form-control" id="edit_vet_email" name="vet_email" value="<?php echo ($vet['vet_email']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_vet_old_pass">Old Password</label>
                            <input type="password" class="form-control" id="edit_vet_old_pass" name="edit_vet_old_pass">
                        </div>
                        <div class="form-group" style="display: none">
                            <label for="edit_vet_new_pass">New Password</label>
                            <input type="password" class="form-control" id="edit_vet_new_pass" name="edit_vet_new_pass">
                        </div>

                        <div class="form-group">
                            <label for="edit_vet_phone">Phone</label>
                            <input type="text" class="form-control" id="edit_vet_phone" name="vet_phone" value="<?php echo ($vet['vet_phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_city">City</label>
                            <input type="text" class="form-control" id="edit_city" name="city" value="<?php echo ($vet['city']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="work_start">Work Start</label>
                            <input type="text" class="form-control" id="edit_work_start" name="work_start" value="<?php echo ($vet['work_start']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="work_end">Work End</label>
                            <input type="text" class="form-control" id="edit_work_end" name="work_end" value="<?php echo ($vet['work_end']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_photo" class="form-label">Photo</label>
                            <img src="photos/uploads/<?php echo ($vet['photo']); ?>" alt="Profile Photo" width="70" height="70" style="border-radius: 50px">
                            <input type="file" class="form-control" id="edit_photo" name="file">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-button">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

<script src="js/edit_vet.js"></script>

<?php include "parts/footer.php"; ?>

</body>
</html>