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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButton = document.getElementById('edit-button');
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        const saveButton = document.getElementById('save-button');
        const editForm = document.getElementById('editForm');

        editButton.addEventListener('click', function () {
            editModal.show();
        });

        saveButton.addEventListener('click', function () {
            editForm.submit();
        });
    });
    </script>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php getNavbar($id_user, $id_admin, $id_vet); ?>

<?php

$query = $pdo->prepare("SELECT * FROM vet WHERE id_vet = :id");
$query->execute(['id' => $id_vet]);
$vet = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vet_fname = $_POST['vet_fname'];
    $vet_lname = $_POST['vet_lname'];
    $vet_email = $_POST['vet_email'];
    $vet_phone = $_POST['vet_phone'];
    $city = $_POST['city'];

    $updateUQuery = $pdo->prepare("UPDATE vet SET vet_fname = :vet_fname, vet_lname = :vet_lname, vet_email = :vet_email, vet_phone = :vet_phone, city = :city WHERE id_vet = :id");
    $updateUQuery->execute([
        'vet_fname' => $vet_fname,
        'vet_lname' => $vet_lname,
        'vet_email' => $vet_email,
        'vet_phone' => $vet_phone,
        'city' => $city,
        'id' => $id_vet
    ]);

    $query->execute(['id' => $id_vet]);
    $vet = $query->fetch();
}
?>

<div class="container">
        <div class="profileContainer">
            <div class="profileHeader">
                <h2>Veterinarian Profile</h2>
            </div>
            <div class="profileContent">
                <form id="profileForm" method="post">
                    <div class="form-group row">
                        <label for="vet_fname" class="col-sm-3 col-form-label">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="vet_fname" name="vet_fname" value="<?php echo ($vet['vet_fname']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vet_lname" class="col-sm-3 col-form-label">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="vet_lname" name="vet_lname" value="<?php echo ($vet['vet_lname']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vet_email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" readonly class="form-control" id="vet_email" name="vet_email" value="<?php echo ($vet['vet_email']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="vet_phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="vet_phone" name="vet_phone" value="<?php echo ($vet['vet_phone']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="city" class="col-sm-3 col-form-label">City</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="city" name="city" value="<?php echo ($vet['city']); ?>">
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
                    <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post">
                        <div class="form-group">
                            <label for="edit_vet_fname">First Name</label>
                            <input type="text" class="form-control" id="edit_vet_fname" name="vet_fname" value="<?php echo ($vet['vet_fname']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_vet_lname">Last Name</label>
                            <input type="text" class="form-control" id="edit_vet_lname" name="vet_lname" value="<?php echo ($vet['vet_lname']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_vet_email">Email</label>
                            <input type="email" class="form-control" id="edit_vet_email" name="vet_email" value="<?php echo ($vet['vet_email']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_vet_phone">Phone</label>
                            <input type="text" class="form-control" id="edit_vet_phone" name="vet_phone" value="<?php echo ($vet['vet_phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_city">City</label>
                            <input type="text" class="form-control" id="edit_city" name="city" value="<?php echo ($vet['city']); ?>">
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

<?php include "parts/footer.php"; ?>

</body>
</html>