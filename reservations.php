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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Veterinary Practice - Veterinator</title>
        <script src="js/reservations.js"></script>
        <link rel="stylesheet" href="styless/reservations.css">
        <style>

            .error {
                color: red;

            }

            .error #codeInput {
                border: 1px solid #f00;
            }


            input, button {
                padding: 5px;
            }

            #resultMessage{
                color: red;
            }

            td{
                padding: 20px;
                margin: 20px;
                border: 2px solid black;
            }
            th{
                text-align: center;
            }

            input[type="number"] {
                width: 50px;
            }

            input[type="text"] {
                width: 100px;
                font-size: 15pt;
            }

            #treatmentForm{
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgb(172,218,210);
                background: linear-gradient(90deg, rgba(172,218,210,1) 0%, rgba(205,233,228,1) 25%, rgba(255,255,255,1) 63%, rgba(192,133,221,1) 100%);
                box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                z-index: 1000;
                padding: 20px;
                width: 80%;
                max-width: 500px;
            }

            #result {
                position: relative;
            }

            .close-button {
                position: absolute;
                top: 10px;
                right: 10px;
                cursor: pointer;
            }

        </style>
    </head>
    <body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<?php getNavbar($id_user, $id_admin, $id_vet);?>


<br><br><br><br>

<!--Ovde se ispisuju Poruke rezultata-->

<div id="resultMessage"></div><br><br>

<!--Tabela u kojoj su ispisane sve rezervacije za danasnji dan-->

<div class="container mx-auto my-5">
    <h1>Reservations for the day:</h1><br><br>
    <table id="table_reservation">

    </table>
</div>

<!--Popup forma sa potrebnim informacijama i mogucim unosom za elektronski karton-->

     <form  method="post" name="pet_card" id="treatmentForm" style="display: none;">
         <span class="close-button">&times;</span>
         <h1>Enter data in pet card</h1>
         <div class="row">

             <div class="col-6 mt-3">
                <div id="result"></div>
             </div>

            <div class="col-6  mb-3">
                <label for="condition" class="form-label mt-3">Pet condition</label>
                <input type="text" class="form-control" id="condition"
                       name="condition">
                <small></small>

                <label for="diagnosis" class="form-label mt-3">Diagnosis</label>
                <input type="text" class="form-control" id="diagnosis"
                       name="diagnosis">
                <small></small>

                 <label for="medication" class="form-label mt-3">Prescribed medication</label>
                 <input type="text" class="form-control" id="medication"
                        name="medication">
                 <small></small>
            </div>
         </div>
            <button type="submit" class="btn btn-primary" id="saveDoneTreatment">Save treatment</button>
    </form>


<?php include "parts/footer.php";?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>

