<?php
require "db_config.php";
require "parts/functions.php";

session_start();
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
    <link rel="icon" type="image/x-icon" href="photos/index_photos/icon.png">
    <link rel="stylesheet" href="styless/navBar.css">
    <link rel="stylesheet" href="styless/contStyle.css">
    <link rel="stylesheet" href="styless/hover-min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">

    </script>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php
$id_admin = $_SESSION['id_admin'] ?? "";
$id_user = $_SESSION['id_user'] ?? "";
$id_vet = $_SESSION['id_vet'] ?? "";
getNavbar($id_user,$id_admin,$id_vet);
?>

    <div class="parallax">
        <div></div>
        <svg viewBox="0 0 1440 200" class="wave" preserveAspectRatio="none">
            <path fill="#ffffff" fill-opacity="1" d="M0,128L40,117.3C80,107,160,85,240,90.7C320,96,400,128,480,154.7C560,181,640,203,720,192C800,181,880,139,960,106.7C1040,75,1120,53,1200,58.7C1280,64,1360,96,1400,112L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
        </svg>
    </div>
    <div class="bd">
        <!--Cards-->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6 col-sm-12 box">
                    <h2>Call Us</h2>
                    <p class="boxP"><i class="bi bi-telephone-fill"></i>Phone Number</p>
                    <p class="boxP"><i class="bi bi-clock-fill"></i>Working Time</p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 box">
                    <h2>Visit Us</h2>
                    <p class="boxP"><i class="bi bi-geo-alt-fill"></i>Street Name</p>
                    <p class="boxP"><i class="bi bi-house-heart-fill"></i>City</p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 box">
                    <h2>Message Us</h2>
                    <p class="boxP"><i class="bi bi-envelope-fill"></i>Email</p>
                    <p class="boxP"><i class="bi bi-instagram"></i>Instagram</p>
                </div>
            </div>
        </div>

        <!--Contact-->
        <div class="container">
            <br><br>
            <h1 class="mt-5 mb-2 text-center">Contact Us</h1>
            <p class="mb-5 text-center">For any questions, uncertainties, complains or comments please contact us.</p>
            <form class=" my-auto mx-auto text-center" action="#" method="POST">
                <div class="row justify-content-center ">
                    <div class="col-md-4 col-10 mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="col-md-4 col-10 mb-3">
                        <label for="Email" class="form-label">Email Address:</label>
                        <input type="email" class="form-control" id="Email" name="email">

                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4 col-10 mb-3">
                        <label for="subject" class="form-label">Subject:</label>
                        <input type="text" class="form-control" id="subject" name="subject">
                    </div>
                    <div class="col-md-4 col-10 mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-10 mb-3">
                        <label for="question" class=" col-form-label text-center">Message:</label>
                        <textarea class="form-control" id="question" rows="7" name="message"></textarea>
                    </div>
                </div>
                <div class="mb-5 text-center mt-5">
                    <button type="submit" class="btn btnSend btn-primary">Submit   <i class="bi bi-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </div>

<?php
include "parts/footer.php";
?>

</body>

</html>