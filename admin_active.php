<?php
require "db_config.php";
require "parts/functions.php";

if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
}

if (!empty($token) and strlen($token) === 40) {

    $sql = "UPDATE admin SET active = 1, registration_token = '', registration_expires = ''
            WHERE binary registration_token = :token AND registration_expires > now()";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();


    if ($stmt->rowCount() > 0) {
        redirection('logIn.php');
    } else {
        redirection('admin_signin.php?r=12');
    }
} else {
    redirection('admin_signin.php?r=0');
}