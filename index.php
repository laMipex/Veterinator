<?php
session_start();
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
  <link rel="icon" type="image/x-icon" href="photos/index_photos/icon.png">
  <link rel="stylesheet" href="styless/style.css">
  <link rel="stylesheet" href="styless/hover-min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <title>Veterinary Practice - Veterinator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
      <img class="dImg img-fluid" src="photos/index_photos/karlo.jpg" alt="doctor">
      </p>
    </div>
    <!--Appointment-->

      <?php

      $src = "";
      $message ="";
      if($id_user){
          $src = "services_description.php";
          $r =22;
      }
      else{
          $src = "login.php";
          $r =21;

      }
      ?>
    <div class="container appDiv">
      <div class="row">
        <div class="col-md-6 pawDiv">
            <p><?php $message ?></p>

          <a href="<?php echo $src ?>?r=<?php echo $r ?>"><img class="pawImg img-fluid" src="photos/index_photos/paw.png" alt="Appointment Paw"></a>
        </div>
        <div class="col-md-6 pawText">
          <h1>Ready for Appointment?</h1>
          <h3>Click on the PAW to begin!</h3>
        </div>
      </div>
    </div>


      <!--Doctors-->
      <?php

      $query = $pdo->prepare("SELECT * FROM vet WHERE active = 1 AND is_banned = 0 ORDER BY vet_fname ASC,vet_lname ASC  ");
      $query->execute();
      $vets = $query->fetchAll();

echo '<div class="container mb-5">
       
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-4">';

if ($query->rowCount() > 0) {
    foreach ($vets as $vet) {

        $query2 = $pdo->prepare("SELECT s.service_name, vs.id_service 
                                      FROM vet v 
                                      INNER JOIN vet_services vs
                                      ON v.id_vet = vs.id_vet
                                      INNER JOIN services s 
                                      ON s.id_service = vs.id_service
                                      WHERE v.id_vet = :id_vet;
                                        ");

        $query2->bindParam(':id_vet', $vet['id_vet'], PDO::PARAM_STR);
        $query2->execute();
        $services = $query2->fetchAll();

        // Formatiranje vremena
        $work_start = date('H:i', strtotime($vet['work_start']));
        $work_end = date('H:i', strtotime($vet['work_end']));


        // Provera da li postoji slika
        $photo = !empty($vet['photo']) ? 'photos/uploads/'.$vet['photo'] : 'photos/uploads/default_vet.png';


        echo '
        <div class="col">
            <div class="card">
               <img class="card-img-top" src="' . $photo . '" alt="Card Vet" height="250" style="padding: 20px;border-radius: 50px">
                <div class="card-body">
                    <h4 class="card-title">' . $vet['vet_fname'] . " ". $vet['vet_lname'] .'</h4>
                    <div class="card-info">
                        <label for="vet_email">Email:</label>
                        <p class="card-text vet_email">' . $vet['vet_email'] . '</p>
                    </div>
                    <div class="card-info">
                        <label for="vet_phone">Phone:</label>
                        <p class="card-text vet_phone">0' . $vet['vet_phone'] . '</p>
                    </div>
                    <div class="card-info">
                        <label for="city">City:</label>
                        <p class="card-text city">' . $vet['city'] . '</p>
                    </div>
                    <div class="card-info">
                        <label for="vet_specialization">Specialization:</label>
                        <p class="card-text vet_specialization">' . $vet['specialization'] . '</p>
                    </div>
                     <div class="card-info">
                        <label for="work_time">Work time:</label>
                        <p class="card-text work_time">' . $work_start . " - " . $work_end .'</p>
                    </div>
                     <div class="card-info mb-3">
                        <label for="vet_services" class="form-label">Services</label>
                        <select name="vet_services" class="vet_services" class="form-select">';
                            if ($query2->rowCount() > 0) {

                                foreach ($services as $service) {
                                    echo '<option value="' . $service['id_service'] . '">' . $service['service_name'] . '</option>';
                                }
                            }
                            else{
                                echo '<option value="-1">- no services yet -</option>';
                            }

                                echo '          </select>
                    </div>
                    
                   
                </div>
            </div>
        </div>';
    }
} else {
    echo '<h1>Sorry, no vets yet!</h1>';
}

echo '</div>
    </div> </div>';

echo '<style>

    .card-title{
        font-size: 17pt;
        margin-bottom:25px;
    }
    .card-info {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .card-info label {
        margin-right: 10px;
        font-style: italic;
        font-size: 9pt;
    }
    .card-info p {
        margin: 0;
    }
</style>'; ?>





<?php
include "parts/footer.php";
?>




</body>

</html>