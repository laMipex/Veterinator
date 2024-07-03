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
    <style>
        th,td,tr{
            padding: 5px;
            margin:5px;
            border: 2px solid black;
        }
        td{
            padding: 25px;
        }
        th{
            text-align: center;
        }
    </style>

    </head>
    <body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

    <?php getNavbar($id_user, $id_admin, $id_vet); ?>



    <div class="container my-5">

        <div class="row">
            <div class="col-6 my-5 d-flex ">
                <h2>List of users</h2>
            </div>
            <div class="col-6 my-5 d-flex">
                <div class="dropdown ms-3">
                    <img src="photos/index_photos/filter.png" id="sortIcon" alt="filter" width="40" height="40" data-bs-toggle="dropdown" aria-expanded="false" class="mt-1" style="cursor:pointer;">
                    <ul class="dropdown-menu" aria-labelledby="sortIcon">
                        <li><a class="dropdown-item sort-option" href="#" data-sort="asc">Name Ascending</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="desc">Name Descending</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="asc">Points Ascending</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="desc">Points Descending</a></li>
                    </ul>
                </div>
                <label for="search" class="ms-5 mt-2" ></label><br><br>
                <input type="text" id="search" class="ms-2 mb-5" style="width: 300px;height: 60px" >

            </div>
        </div>
        <table id="tableUsers">

        <?php
        $query = $pdo->prepare("SELECT * FROM users WHERE active = 1 AND is_banned = 0 ORDER BY user_fname ASC,user_lname ASC  ");
        $query->execute();
        $users = $query->fetchAll();


        if ($query->rowCount() > 0) {
        echo '
        <tr>
            <th>Photo:</th>
            <th>User name:</th>
            <th>Usr email:</th>
            <th>User phone:</th>
            <th>User age:</th>
            <th>Negative points:</th>
            <th>Bann:</th>
        </tr>';

        foreach ($users as $user) {
        echo '<tr  style="border-bottom: 2px solid black;">
            <td><img src="photos/uploads/'.$user['photo'] .'" alt="User" width="60" height="60" style="border-radius: 50px"></td>
            <td>' . $user['user_fname'] . " ". $user['user_lname'] .'</td>
             <td>0' . $user['user_phone'] . '</td>
            <td>' . $user['user_email']. '</td>
           
            <td>' . $user['age'] . '</td>
            <td class="negative_points">' . $user['negative_points'] . '</td>
        
            <td>
            <input type="hidden" class="id_user" value="'. $user['id_user'] .'">
            <button type="button" class="submit">Submit</button></td>
        </tr>';
        }
            echo '</table>
    </div>';

    } else {
    echo '<h1>Sorry, no users yet!</h1>';
    }



    ?>

    <?php include "parts/footer.php"; ?>

            <script src="js/users.js"></script>
    </body>
</html>
