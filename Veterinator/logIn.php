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
    <link rel="icon" type="image/x-icon" href="index_photos/icon.png">
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
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php

$id_user = $_SESSION['id_user'] ?? "";
getNavbar($id_user);

?>
<br><br>
<h1 class="text-center my-5">Log In</h1>
<div class="container">
    <form action="logIn.php" method="POST" id="loginForm" class="mx-auto mt-6">
        <div class="row justify-content-center ">
            <div class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mb-3">
                <label for="loginUsername" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="loginUsername" name="username" aria-describedby="emailHelp" placeholder="example@gmail.com">
                <small></small>
            </div>
        </div>
        <div class="row justify-content-center ">
            <div class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mb-3">
                <label for="loginPassword" class="form-label">Password <i class="bi bi-eye-slash-fill"
                                                                          id="passwordEye"></i></label></label>
                <input type="password" class="form-control passwordVisibiliy" id="loginPassword" name="password">
                <small></small>
            </div>
        </div>
        <div class="row justify-content-center ">
            <div class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 ">
                <p>Have you <a class="decoration" href="forgotten_password.php" id="fl"> forgotten your password?</a></p>
            </div>
        </div>
        <div class="row justify-content-center ">
            <div class="mb-3 text-center ">
                <button type="submit" class="btn btnSend btn-primary ">Log in</button>
            </div>
        </div>
        <div class="row justify-content-center ">
            <div class="mb-3 text-center mt-2">
                <p class="fs-5 mt-4">Do not have your account yet?
                <a href="signIn.php" class="fs-4 decoration"> &nbsp;  Create account</a></p>
            </div>
        </div>
    </form>
</div>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST["username"] ?? '');
    $password= trim($_POST["password"] ?? '');

    if (!empty($username) and !empty($password)) {
        $data = checkUserLogin($username, $password);

        if ($data and is_int($data['id_user'])) {
            $_SESSION['username'] = $username;
            $_SESSION['id_user'] = $data['id_user'];
            $r=17;
            redirection("index.php");
        } else {
            $r = 1;
            redirection("logIn.php?r=1");
        }

    } else {
        $r=1;
        redirection("logIn.php?r=1");

    }
}

ob_end_flush();
?>

<?php

$r;

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
