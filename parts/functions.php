<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


$pdo = connectDatabase($dsn, $pdoOptions);
$GLOBALS['pdo'] = $pdo;


function getNavbar(string $id_user,string $id_admin,string $id_vet) {
    if(isset($id_user) && !empty($id_user)){
        include_once "nav_user.php";
    }
    elseif (isset($id_admin) && !empty($id_admin)){
        include_once "nav_admin.php";
    }
    elseif (isset($id_vet) && !empty($id_vet)) {

        include_once "nav_vet.php";
    }else {
        include_once "nav_guest.php";
    }
}

function getNavbarBlack(string $id_user,string $id_admin,string $id_vet) {
    if(isset($id_user) && !empty($id_user)){
        include_once "nav_user.php";
    }
    elseif (isset($id_admin) && !empty($id_admin)){
        include_once "nav_admin.php";
    }
    elseif (isset($id_vet) && !empty($id_vet)){
        include_once "nav_vet.php";
    }
    else {
        include_once "nav_guestBlack.php";
    }
}


function connectDatabase(string $dsn, array $pdoOptions): PDO
{

    try {
        $pdo = new PDO($dsn, PARAMS['USER'], PARAMS['PASS'], $pdoOptions);
    } catch (\PDOException $e) {
        var_dump($e->getCode());
        throw new \PDOException($e->getMessage());
    }

    return $pdo;
}



function redirection(string $url) {
    header("Location:$url");
    exit();
}


function checkUserLogin(string $email, string $enteredPassword): array
{
    $sql = "SELECT id_user, user_password FROM users WHERE user_email=:email AND active=1 AND is_banned = 0 LIMIT 0,1";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    $data = [];
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        $registeredPassword = $result['user_password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_user'] = $result['id_user'];
        }
    }

    return $data;
}

function existsUser(PDO $pdo, string $email): bool
{

    $sql = "SELECT id_user FROM users WHERE user_email=:email AND (registration_expires>now() OR active ='1') LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function registerUser(PDO $pdo, string $password, string $firstname, string $lastname,string $phone, string $email, string $token): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(user_password,user_fname,user_lname,user_phone,user_email,registration_token, registration_expires,active)
            VALUES (:passwordHashed,:firstname,:lastname,:phone,:email,:token,DATE_ADD(now(),INTERVAL 1 DAY),0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();

}

function createToken(int $length): ?string
{
    try {
        return bin2hex(random_bytes($length));
    } catch (\Exception $e) {
        // c:xampp/apache/logs/
        error_log("****************************************");
        error_log($e->getMessage());
        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
        return null;
    }
}

function createCode($length): string
{
    $down = 97;
    $up = 122;
    $i = 0;
    $code = "";

    /*
      48-57  = 0 - 9
      65-90  = A - Z
      97-122 = a - z
    */

    $div = mt_rand(3, 9); // 3

    while ($i < $length) {
        if ($i % $div == 0)
            $character = strtoupper(chr(mt_rand($down, $up)));
        else
            $character = chr(mt_rand($down, $up)); // mt_rand(97,122) chr(98)
        $code .= $character; // $code = $code.$character; //
        $i++;
    }
    return $code;
}



function sendEmail(PDO $pdo, string $email, array $emailData, string $body, int $id_user): void
{

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'mail.3883.stud.vts.su.ac.rs';
        $mail->SMTPAuth = true;
        $mail->Username = 'three883';
        $mail->Password = 'k7PworyyEAyR9Vf';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('three883@3883.stud.vts.su.ac.rs', 'Mailer');
        $mail->addAddress("$email", 'User');
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        //Content
        $mail->isHTML(true);
        $mail->Subject = $emailData['subject'];
        $mail->Body =  $body;
        $mail->AltBody =  $emailData['altBody'];

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        addEmailFailure($pdo, $id_user, $message);
    }

}



function sendVetEmail(PDO $pdo, string $email, array $emailData, string $body): void
{

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'mail.3883.stud.vts.su.ac.rs';
        $mail->SMTPAuth = true;
        $mail->Username = 'three883';
        $mail->Password = 'k7PworyyEAyR9Vf';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('three883@3883.stud.vts.su.ac.rs', 'Mailer');
        $mail->addAddress("$email", 'User');
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        //Content
        $mail->isHTML(true);
        $mail->Subject = $emailData['subject'];
        $mail->Body =  $body;
        $mail->AltBody =  $emailData['altBody'];

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

    }


}





function sendForgetPasswordToken(PDO $pdo, string $email): bool
{
    $token = createToken(20);

    return true;
}


function addEmailFailure(PDO $pdo, int $id_user, string $message): void
{
    $sql = "INSERT INTO user_email_failures (id_user, message, date_time_added)
            VALUES (:id_user,:message, now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();

}

function getUserData(PDO $pdo, string $data, string $field, string $value): string
{
    $sql = "SELECT $data as data FROM users WHERE $field=:value LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $data = '';

    if ($stmt->rowCount() > 0) {
        $data = $result['data'];
    }

    return $data;
}

function setForgottenToken(PDO $pdo, string $email, string $token): void
{
    $sql = "UPDATE users SET forgotten_password_token = :token, forgotten_password_expires = DATE_ADD(now(),INTERVAL 6 HOUR) WHERE user_email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
}

function existsAdmin(PDO $pdo, string $email): bool
{

    $sql = "SELECT id_admin FROM admin WHERE email=:email AND (registration_expires>now() OR active ='1') LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function registerAdmin(PDO $pdo, string $password, string $firstname, string $lastname, string $email, string $token): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin(password,firstname,lastname,email,registration_token, registration_expires,active)
            VALUES (:passwordHashed,:firstname,:lastname,:email,:token,DATE_ADD(now(),INTERVAL 1 DAY),0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();

}

function checkAdminLogin(string $email, string $enteredPassword): array
{
    $sql = "SELECT id_admin, password FROM admin WHERE email=:email AND active=1 LIMIT 0,1";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    $data = [];
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        $registeredPassword = $result['password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_admin'] = $result['id_admin'];
        }
    }

    return $data;
}

function existsVet(PDO $pdo, string $email): bool
{

    $sql = "SELECT id_vet FROM vet WHERE vet_email=:email AND (registration_expires>now() OR active ='1') LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function registerVet(PDO $pdo, string $password, string $firstname, string $lastname,string $phone, string $email, string $token): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO vet(vet_password,vet_fname,vet_lname,vet_phone,vet_email,registration_token, registration_expires,active)
            VALUES (:passwordHashed,:firstname,:lastname,:phone,:email,:token,DATE_ADD(now(),INTERVAL 1 DAY),0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();

}

function checkVetLogin(string $email, string $enteredPassword): array
{
    $sql = "SELECT id_vet, vet_password FROM vet WHERE vet_email=:email AND active=1 AND is_banned = 0 LIMIT 0,1";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    $data = [];
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        $registeredPassword = $result['vet_password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_vet'] = $result['id_vet'];
        }
    }

    return $data;
}





