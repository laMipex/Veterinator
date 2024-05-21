<?php
session_start();
ob_start();

require "db_config.php";
require "parts/functions.php";

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
    <link rel="icon" type="image/x-icon" href="index_photos/icon.png">
    <link rel="stylesheet" href="styless/navBar.css">
    <link rel="stylesheet" href="styless/logIn.css"
    <link rel="stylesheet" href="styless/hover-min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">

    </script>
    <script src="js/script.js"></script>

</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php

$id_user = $_SESSION['id_user'] ?? "";
getNavbar($id_user);
?>

<div class="container">
    <br><br>
    <h1 class="text-center my-5">Sign Up</h1>
    <form method="post" action="signIn.php" id="registerForm" class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto mt-4" >
        <div class="mb-3">
            <label for="registerFirstname" class="form-label">First name</label>
            <input type="text" name="firstname" class="form-control" id="registerFirstname" placeholder="John">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="registerLastname" class="form-label">Last name</label>
            <input type="text" name="lastname" class="form-control" id="registerLastname" placeholder="Dow">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="registerEmail" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" placeholder="example@mail.com">
            <small></small>
        </div>
       <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone">
           <small></small>
        </div>
        <div class="mb-3 field">
            <label for="registerPassword" class="form-label">Password <i class="bi bi-eye-slash-fill"
                                                                         id="passwordEye"></i></label>
            <input type="password" class="form-control passwordVisibiliy" name="password" id="registerPassword"
                   placeholder="Password (min 8 characters)">
            <small></small>
            <span id="strengthDisp" class="badge displayBadge mt-3">Weak</span>
        </div>
        <div class="mb-3">
            <label for="registerPasswordConfirm" class="form-label">Cofirm Password:</label>
            <input type="password" name="passwordConfirm" class="form-control" id="registerPasswordConfirm">
            <small></small>
           <!-- <input type="checkbox"  class="mt-3">Show Password-->
        </div>


        <div class="mb-5 text-center mt-5">
           <!-- <input type="hidden" name="action" value="register">-->
            <button type="submit"  class="btn btnSend btn-primary text-center">Sign Up</button>
            <button type="reset" class="btn btn-primary btnSend ms-5 resetButton" >Cancel</button>
            <small></small>
        </div>
    </form>
</div>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST["firstname"] ?? '');
    $lastname = trim($_POST["lastname"] ?? '');
    $phone = trim($_POST["phone"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $passwordConfirm = trim($_POST["passwordConfirm"] ?? '');
    $email = trim($_POST["email"] ?? '');

    if (empty($firstname)) {
        $r = 4;
    } elseif (empty($lastname)) {
        $r = 4;
    } elseif (empty($phone)){
        $r=4;
    } elseif (str_contains($email, "example")){
        $r = 18;
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $r = 8;
    } elseif (empty($password)  || $password <= 8) {
        $r = 9;
    } elseif (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
        $r = 10;
    } elseif (empty($passwordConfirm)) {
        $r = 9;
    } elseif ($password !== $passwordConfirm) {
        $r = 7;

    } elseif (!existsUser($pdo, $email)) {
        $token = createToken(20);
        if ($token) {
            $id_user = registerUser($pdo, $password, $firstname, $lastname, $phone,$email, $token);
            try {
                $body = "Your username is $email. To activate your account click on the <a href=" . SITE . "active.php?token=$token>link</a>";
                sendEmail($pdo, $email, $emailMessages['signIn'], $body, $id_user);
                redirection("signIn.php?r=3");
            } catch (Exception $e) {
                error_log("****************************************");
                error_log($e->getMessage());
                error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                redirection("signIn.php?r=11");
            }
        }
    } else {
        $r = 2;
    }

    if ($r != 0) {
        redirection("signIn.php?r=$r");
    }
}

ob_end_flush();


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

?>

<?php
include "parts/footer.php";
?>

</body>
</html>