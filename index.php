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
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="hover-min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <title>Veterinary Practice - Veterinator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
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
            <a class="nav-link active" aria-current="page" href="#">Home</a>
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
        <form action="login.php" class="d-flex">
          <button id="btnS" class="btn btn-outline-light" type="submit">Sign In</button>
        </form>
      </div>
    </div>
  </nav>
  <div class="parallax">
    <div class="box">
      <div class="headerBox">
        <p class="pText animate__animated animate__slideInLeft">We know what is best for <br> your animal!</p>
        <div class="btnF mt-2 ">
          <form action="contact.php">
            <button type="submit" class="btn btnSend btn-primary animate__animated animate__slideInLeft">Contact Us</button>
          </form>
        </div>
      </div>
    </div>
    <svg viewBox="0 0 1440 200" class="wave" preserveAspectRatio="none">
      <path fill="#ffffff" fill-opacity="1" d="M0,128L40,117.3C80,107,160,85,240,90.7C320,96,400,128,480,154.7C560,181,640,203,720,192C800,181,880,139,960,106.7C1040,75,1120,53,1200,58.7C1280,64,1360,96,1400,112L1440,128L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
    </svg>
  </div>
  <div class="bd">
    <!--About Us-->
    <div class="section">
      <div class="txt">
        <h1>About Us</h1>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
          aliqua. Vel orci porta non pulvinar neque laoreet suspendisse interdum. Nec tincidunt praesent semper feugiat. Amet
          nisl suscipit adipiscing bibendum est ultricies integer quis auctor. Metus aliquam eleifend mi in nulla posuere. Sit
          amet aliquam id diam maecenas ultricies mi.
      </div>
      <img class="dImg img-fluid" src="karlo.jpg" alt="doctor">
      </p>
    </div>
    <!--Appointment-->
    <div class="container appDiv">
      <div class="row">
        <div class="col-md-6 pawDiv">
          <a href="#"><img class="pawImg img-fluid" src="paw.png" alt="Appointment Paw"></a>
        </div>
        <div class="col-md-6 pawText">
          <h1>Ready for Appointment?</h1>
          <h3>Click on the PAW to begin!</h3>
        </div>
      </div>
    </div>
    <!--Doctors-->
    <div class="container">
      <h1 class="dH">Our Doctors</h1>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-4">
        <div class="col">
          <div class="card">
            <img class="card-img-top" src="noir.jpg" alt="Card image">
            <div class="card-body">
              <h4 class="card-title">John Doe</h4>
              <p class="card-text">Some example text.</p>
              <a href="#" class="btn btn-primary">See Profile</a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card">
            <img class="card-img-top" src="noir.jpg" alt="Card image">
            <div class="card-body">
              <h4 class="card-title">John Doe</h4>
              <p class="card-text">Some example text.</p>
              <a href="#" class="btn btn-primary">See Profile</a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card">
            <img class="card-img-top" src="noir.jpg" alt="Card image">
            <div class="card-body">
              <h4 class="card-title">John Doe</h4>
              <p class="card-text">Some example text.</p>
              <a href="#" class="btn btn-primary">See Profile</a>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card">
            <img class="card-img-top" src="noir.jpg" alt="Card image">
            <div class="card-body">
              <h4 class="card-title">John Doe</h4>
              <p class="card-text">Some example text.</p>
              <a href="#" class="btn btn-primary">See Profile</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div> <!--body-->
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