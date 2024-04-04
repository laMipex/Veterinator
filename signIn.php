<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mihajlo Baranovski, Milana Sabovljev">
    <meta name="description" content="Veterinary Practice">
    <meta name="keyword" content="animals, care, Veterinary">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/x-icon" href="icon.png">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="logIn.css"
    <link rel="stylesheet" href="hover-min.css">
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
<nav class="navbar navbar-expand-md">
    <div class="container-fluid">
        <img class="logo" src="vet.png" alt="logo">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-ls-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Surgery</a></li>
                        <li><a class="dropdown-item" href="#">Dental</a></li>
                        <li><a class="dropdown-item" href="#">Pharmacy</a></li>
                        <li><a class="dropdown-item" href="#">Anesthesia</a></li>
                        <li><a class="dropdown-item" href="#">Vaccinations</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>
            <form action="logIn.php" class="d-flex">
                <button id="btnS" class="btn btn-outline-dark" type="submit">Log In</button>
            </form>
        </div>
    </div>
</nav>
<div class="container">
    <br><br>
    <h1 class="text-center my-5">Sign Up</h1>
    <form method="post" action="createNewUser.php" class="col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto mt-4">
        <div class="mb-3">
            <label for="firstname" class="form-label">First name</label>
            <input type="text" name="firstname" class="form-control" id="firstname" placeholder="John" required>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Last name</label>
            <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Dow" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="example@mail.com" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" required>
            <input type="checkbox" onclick="myFunction()" class="mt-3">Show Password
        </div>


        <div class="mb-5 text-center mt-5">
        <button type="submit" name="submit" class="btn btnSend btn-primary text-center">Sign Up</button>
        </div>
    </form>
</div>
<section class="footer">
    <div class="footer-row">
        <div class="footer-col">
            <h4>Services</h4>
            <ul class="links">
                <li><a href="#">Surgery</a></li>
                <li><a href="#">Dental</a></li>
                <li><a href="#">Pharmacy</a></li>
                <li><a href="#">Anesthesia</a></li>
                <li><a href="#">Vaccinations</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Info</h4>
            <ul class="links">
                <li><a class="unclickable" href="#">Number</a></li>
                <li><a class="unclickable" href="#">Street</a></li>
                <li><a class="unclickable" href="#">City</a></li>
                <li><a class="unclickable" href="#">Email</a></li>
                <li><a class="unclickable" href="#">Working Time</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Special Thanks</h4>
            <ul class="links">
                <li><a href="#">One</a></li>
                <li><a href="#">Two</a></li>
                <li><a href="#">Three</a></li>
                <li><a href="#">Four</a></li>
                <li><a href="#">Five</a></li>
            </ul>
        </div>
    </div>
    <hr>
    <p class="copyright">&copy; 2024 All Rights Reserved</p>
</section>

</body>
</html>