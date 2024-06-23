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

$query = $pdo->prepare("SELECT * FROM users WHERE id_user = :id");
$query->execute(['id' => $id_user]);
$user = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_fname = $_POST['user_fname'];
    $user_lname = $_POST['user_lname'];
    $user_email = $_POST['user_email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];

    $updateUQuery = $pdo->prepare("UPDATE users SET user_fname = :user_fname, user_lname = :user_lname, user_email = :user_email, phone = :phone, age = :age WHERE id_user = :id");
    $updateUQuery->execute([
        'user_fname' => $user_fname,
        'user_lname' => $user_lname,
        'user_email' => $user_email,
        'phone' => $phone,
        'age' => $age,
        'id' => $id_user
    ]);

    $query->execute(['id' => $id_user]);
    $user = $query->fetch();
}
?>

<div class="container">
        <div class="profileContainer">
            <div class="profileHeader">
                <h2>User Profile</h2>
            </div>
            <div class="profileContent">
                <form id="profileForm" method="post">
                    <div class="form-group row">
                        <label for="user_fname" class="col-sm-3 col-form-label">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="user_fname" name="user_fname" value="<?php echo ($user['user_fname']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_lname" class="col-sm-3 col-form-label">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="user_lname" name="user_lname" value="<?php echo ($user['user_lname']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_email" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" readonly class="form-control" id="user_email" name="user_email" value="<?php echo ($user['user_email']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="phone" name="phone" value="<?php echo ($user['phone']); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-sm-3 col-form-label">Age</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" id="age" name="age" value="<?php echo ($user['age']); ?>">
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
                            <label for="edit_user_fname">First Name</label>
                            <input type="text" class="form-control" id="edit_user_fname" name="user_fname" value="<?php echo ($user['user_fname']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_user_lname">Last Name</label>
                            <input type="text" class="form-control" id="edit_user_lname" name="user_lname" value="<?php echo ($user['user_lname']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_user_email">Email</label>
                            <input type="email" class="form-control" id="edit_user_email" name="user_email" value="<?php echo ($user['user_email']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" value="<?php echo ($user['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="edit_age">Age</label>
                            <input type="text" class="form-control" id="edit_age" name="age" value="<?php echo ($user['age']); ?>">
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