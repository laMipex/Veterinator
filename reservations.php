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
                        box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                        z-index: 1000;
                        padding: 20px;
                        width: 90%;
                        max-width: 800px;
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

                    #diagnosis, #medication ,#condition{

                        width: 300px;
                        height: 100px;

                    }

                </style>
            </head>
            <body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

        <?php getNavbar($id_user, $id_admin, $id_vet);
        if($id_vet){
        ?>
        <br><br><br><br>

        <!--Ovde se ispisuju Poruke rezultata-->
        <div id="resultMessage"></div><br><br>

        <!--Tabela u kojoj su ispisane sve rezervacije za DANASNJI DAN-->
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


        <!--SVE REZERVACIJE NAREDNIH 30 DANA-->
        <hr>
        <div class="container mx-auto my-5">
            <h1>List of all Reservations!</h1>
        <table>

        <?php

            $sql2 = "SELECT r.id_reservation, r.reservation_date,r.reservation_time, r.code, CONCAT(u.user_fname, ' ', u.user_lname) AS user_name,
            r.id_pet, p.pet_name, r.id_service, s.service_name, r.service_duration,r.code_verified
            FROM reservations r
            INNER JOIN pet p ON p.id_pet = r.id_pet
            INNER JOIN users u ON p.id_user = u.id_user
            INNER JOIN vet v ON v.id_vet = r.id_vet
            INNER JOIN services s ON r.id_service = s.id_service
            WHERE r.id_vet = :id_vet AND DATE(r.reservation_date) > CURDATE() AND r.code_verified = 0
            ORDER BY r.reservation_time ASC";

            $query2 = $pdo->prepare($sql2);
            $query2->bindParam(':id_vet', $id_vet, PDO::PARAM_STR);
            $query2->execute();
            $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

            if ($query2->rowCount() > 0) {
            echo '
            <tr>
                <th>Date:</th>
                <th>Time:</th>
                <th>User:</th>
                <th>Pet:</th>
                <th>Service:</th>
               
            </tr>';

            foreach ($results2 as $result) {
            echo '<tr style="border-bottom: 2px solid black;">
                <td>' . $result->reservation_date . '</td>
                <td>' . $result->reservation_time . '</td>
                <td>' . $result->user_name . '</td>
                <td>' . $result->pet_name . '</td>
                <td>' . $result->service_name . '</td>
              
                
            </tr>';
            }
            } else {
            echo '<h1>Sorry, no reservation in near future!</h1>';
            }
        ?>
        </table>
        </div>

        <?php

        } elseif($id_admin){

                //        SVE REZERVACIJE NAREDNIH IKAADA
                echo '<br><br>
        <div class="row">
            <div class="col-6 my-5 d-flex justify-content-center">
                <label for="search" class="ms-5 mt-2">Search:</label>
                <input type="date" id="search" class="ms-2" style="width: 150px;">
                <button id="search_btn" class="btn btn-primary ms-3" >Search</button>
            </div>
        </div>
        <div class="container mx-auto my-5">
            <h1>List of all Reservations!</h1>
            <table id="reservationTable">
                <thead>
                    <tr>
                        <th>Date:</th>
                        <th>Time:</th>
                        <th>User:</th>
                        <th>Pet:</th>
                        <th>Service:</th>
                        <th>Vet:</th>
                    </tr>
                </thead>
                <tbody>';
                $sql2 = "SELECT r.id_reservation, r.reservation_date, r.reservation_time, r.code, CONCAT(u.user_fname, ' ', u.user_lname) AS user_name,
                    r.id_pet, p.pet_name, r.id_service, s.service_name, r.service_duration, r.code_verified, CONCAT(v.vet_fname, ' ', v.vet_lname) AS vet_name
                    FROM reservations r
                    INNER JOIN pet p ON p.id_pet = r.id_pet
                    INNER JOIN users u ON p.id_user = u.id_user
                    INNER JOIN vet v ON v.id_vet = r.id_vet
                    INNER JOIN services s ON r.id_service = s.id_service
                    ORDER BY r.reservation_date ASC, r.reservation_time ASC";

                $query2 = $pdo->prepare($sql2);
                $query2->execute();
                $results2 = $query2->fetchAll(PDO::FETCH_OBJ);

                if ($query2->rowCount() > 0) {
                    foreach ($results2 as $result) {
                        echo '<tr style="border-bottom: 2px solid black;">
                        <td>' . $result->reservation_date . '</td>
                        <td>' . $result->reservation_time . '</td>
                        <td>' . $result->user_name . '</td>
                        <td>' . $result->pet_name . '</td>
                        <td>' . $result->service_name . '</td>
                        <td>' . $result->vet_name . '</td>
                    </tr>';
                    }
                }
                echo '</tbody>
            </table>
        </div>';
        ?>

            <script>
                document.getElementById('search_btn').addEventListener('click', function() {
                    let searchDate = document.getElementById('search').value;
                    fetchReservationsbyDate(searchDate);
                });

                function fetchReservationsbyDate(searchDate = '') {
                    const formData = new FormData();
                    formData.append('date', searchDate);

                    fetch('parts/fetch_reservations.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.text())
                        .then(reservations => {
                            renderTable(reservations);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while fetching reservations.');
                        });
                }

                function renderTable(reservations) {
                    const tableBody = document.querySelector('#reservationTable tbody');
                    tableBody.innerHTML = reservations;
                }
            </script>
        <?php
        }


        include "parts/footer.php";?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>

