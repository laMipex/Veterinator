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
    <link rel="stylesheet" href="styless/logIn.css">
    <link rel="stylesheet" href="styless/hover-min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>



</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
}

if (isset($_POST['token'])) {
    $token = trim($_POST['token']);
}

$method = strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_ENCODED));

switch ($method) {
    case "get":
        if (!empty($token) and strlen($token) === 40) {

            $sql = "SELECT id_user FROM users 
        WHERE binary forgotten_password_token = :token AND forgotten_password_expires>now() AND active= 1";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                require "parts/reset_user_form.php";
            } else {
                $rf = 15;
                redirection('reset_form.php?rf=15');
            }
        }
        break;

    case "post":
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = trim($_POST['token']);
            if (!empty($token) and strlen($token) === 40) {

                if (isset($_POST['resetEmail'])) {
                    $resetEmail = trim($_POST["resetEmail"]);
                }

                if (isset($_POST['resetPassword'])) {
                    $resetPassword = trim($_POST["resetPassword"]);
                }

                if (isset($_POST['resetPasswordConfirm'])) {
                    $resetPasswordConfirm = trim($_POST["resetPasswordConfirm"]);
                }

                if (empty($resetEmail)) {
                    $rf = 8;
                    redirection('reset_form.php?rf=8');
                }

                if (empty($resetPassword)) {
                    $rf = 9;
                    redirection('reset_form.php?rf=9');
                }

                if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $resetPassword)) {
                    redirection('reset_form.php?rf=10');
                    $rf = 10;
                }

                if (empty($resetPasswordConfirm)) {
                    $rf = 9;
                    redirection('reset_form.php?rf=9');
                }

                if ($resetPassword !== $resetPasswordConfirm) {
                    $rf = 7;
                    redirection('reset_form.php?rf=7');
                }

                $passwordHashed = password_hash($resetPassword, PASSWORD_DEFAULT);

                $sql = "UPDATE users SET forgotten_password_token = '', forgotten_password_expires = '', user_password = :resetPassword
                WHERE binary forgotten_password_token = :token AND forgotten_password_expires>now() AND active = 1 AND user_email = :email";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':resetPassword', $passwordHashed, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':email', $resetEmail, PDO::PARAM_STR);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $rf = 16;
                    redirection('reset_form.php?rf=16');

                } else {
                    $rf = 15;
                    redirection('reset_form.php?rf=15');
                }
            }
        }
        break;
    default:
        $rf = 0;
        redirection('reset_form.php?rf=0');
}


?>
<?php


if (isset($_GET["rf"]) and is_numeric($_GET['rf'])) {
    $rf = (int)$_GET["rf"];

    if (array_key_exists($rf, $messages)) {
        echo '
        <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
            ' . $messages[$rf] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
        ';
    }
}


?>
</body>
</html>



