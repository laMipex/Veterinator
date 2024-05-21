<?php
require "db_config.php";
require "parts/functions.php";
ob_start();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

    <h1>Enter your email</h1>
    <form action="forgotten_password.php" method="post" name="forget" id="forgetForm">
        <div class="pt-3">
            <label for="forgetEmail" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="forgetEmail" placeholder="Enter your e-mail address"
                   name="email">
            <small></small>
        </div>
        <div class="pt-3">

            <button type="submit" class="btn btn-primary">Reset password</button>
        </div>
    </form>

<?php

$f = 0;

if (isset($_GET["f"]) and is_numeric($_GET['f'])) {
    $f = (int)$_GET["f"];

    if (array_key_exists($f, $messages)) {
        echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$f] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST["email"]);
    if (!empty($email) and getUserData($pdo, 'id_user', 'user_email', $email)) {
        $token = createToken(20);
        if ($token) {
            setForgottenToken($pdo, $email, $token);
            $id_user = getUserData($pdo, 'id_user', 'user_email', $email);
            try {
                $body = "To start the process of changing password, visit <a href=" . SITE . "reset_form.php?token=$token>link</a>.";
                sendEmail($pdo, $email, $emailMessages['forgotten_password'], $body, $id_user);
                $f = 13;
                redirection('forgotten_password.php?f=13');
            } catch (Exception $e) {
                error_log("****************************************");
                error_log($e->getMessage());
                error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                $f = 11;
                redirection("forgotten_password.php?f=11");
            }
        } else {
            $f = 14;
            redirection('forgotten_password.php?f=14');
        }
    } else {
        $f = 15;
        redirection('forgotten_password.php?f=15');
    }
}

ob_end_flush();

?>
</body>
</html>







