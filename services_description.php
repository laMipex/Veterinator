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
        <link rel="icon" type="image/x-icon" href="photos/index_photos/icon.png">
        <link rel="stylesheet" href="styless/navBar.css">
        <link rel="stylesheet" href="styless/logIn.css">
        <link rel="stylesheet" href="styless/hover-min.css">
        <link rel="stylesheet" href="styless/descStyle.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <title>Veterinary Practice - Veterinator</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
        <script src="js/services_description.js"></script>
        <style>
            .error {
                color: red;
            }
            .error #service {
                border: 1px solid #f00;
            }
            .error #vet {
                border: 1px solid #f00;
            }
            .error #date {
                border: 1px solid #f00;
            }
            button #savePet {
                padding: 5px;
            }
            #file {
                display: none;
            }
            #table_cancel td,th{
                border: 2px solid black;
                padding: 10px;

            }

            #popUpReservations {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgb(172, 218, 210);
                box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                z-index: 1000;
                padding: 20px;
                height:700px;
                max-width: 90%;
                width: auto;
                overflow-y: auto;
            }


            #table_cancel {
                max-height: 300px;
                overflow-y: auto;
            }

            .close-button {
                position: absolute;
                top: 10px;
                right: 10px;
                cursor: pointer;
            }


        </style>
    </head>
    <body>
    <div data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">
        <?php
        $id_admin = $_SESSION['id_admin'] ?? "";
        $id_user = $_SESSION['id_user'] ?? "";
        $id_vet = $_SESSION['id_vet'] ?? "";
        getNavbarBlack($id_user, $id_admin, $id_vet);


        $r;

        if (isset($_GET["r"]) and is_numeric($_GET['r'])) {
            $r = (int)$_GET["r"];

            if (array_key_exists($r, $messages)) {
                echo '
                    
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert" style="width: 500px">
                        ' . $messages[$r] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div><br><br>
                    ';
            }
        }


        if($id_user){

            $sql_treatments = "SELECT r.id_reservation ,p.pet_name, CONCAT(v.vet_fname, ' ', v.vet_lname) AS vet_name, p.photo, r.reservation_date,
                                        r.reservation_time, r.treatment_price,r.id_service ,s.service_name
                                FROM reservations r 
                                INNER JOIN pet p ON r.id_pet = p.id_pet
                                INNER JOIN users u ON u.id_user = p.id_user 
                                INNER JOIN vet v ON r.id_vet = v.id_vet
                                INNER JOIN services s ON s.id_service = r.id_service
                                WHERE u.id_user = :id_user AND (r.reservation_date > CURDATE() OR (r.reservation_date = CURDATE() AND r.reservation_time > CURRENT_TIME))
                             AND r.code_verified != 1
                                ORDER BY r.reservation_date ASC, r.reservation_time ASC";
            $query_treatments = $pdo->prepare($sql_treatments);
            $query_treatments->bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $query_treatments->execute();
            $results_treatments = $query_treatments->fetchAll(PDO::FETCH_OBJ);

            ?>
            <div class="row">
                <div class="col-6 my-5 d-flex  justify-content-center">
                    <label for="search" class="ms-5 mt-2" >Search:</label>
                    <input type="text" id="search" class="ms-2" >
                    <div class="dropdown ms-3">
                        <img src="photos/index_photos/filter.png" id="sortIcon" alt="filter" width="40" height="40" data-bs-toggle="dropdown" aria-expanded="false" class="mt-1" style="cursor:pointer;">
                        <ul class="dropdown-menu" aria-labelledby="sortIcon">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="asc">Price Ascending</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="desc">Price Descending</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-6 my-5 d-flex justify-content-end">
                    <button type="submit" id="btnUserReservations" class="btn-primary me-5 ">My reservations</button>
                </div>

            </div>
            <div class="container" id="popUpReservations" style="display: none">
                <span class="close-button">&times;</span>
                <h1 class="mt-3 mb-5">My reservations:</h1>
                <table  id="table_cancel" >

                    <tr>
                        <th>Pet:</th>
                        <th>Name:</th>
                        <th>Date:</th>
                        <th>Time:</th>
                        <th>Vet:</th>
                        <th>Service:</th>
                        <th>Price:</th>
                        <th>Cancel:</th>
                    </tr>

                    <?php foreach ($results_treatments as $result) : ?>
                        <tr>

                            <td><img src="photos/uploadsPet/<?= $result->photo ?>" alt="Pet" width="60" height="60" style="border-radius: 50px"></td>
                            <td><?= $result->pet_name ?></td>
                            <td><?= $result->reservation_date ?></td>
                            <td><?= $result->reservation_time ?></td>
                            <td><?= $result->vet_name ?></td>
                            <td><?= $result->service_name ?></td>
                            <td><?= $result->treatment_price ?>$</td>
                            <td>
                                <input type="hidden" class="id_res" value="<?= $result->id_reservation ?>">
                                <button type="button" class="cancel">Cancel</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php
        }
        ?>

        <?php  $sql = "SELECT * FROM services ORDER BY service_name ASC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            echo '<div class="bd container ">';
            echo '<div class="row cardSearch">';
            foreach ($results as $result) {
                $id_service = $result->id_service;
                $serviceName = $result->service_name;
                $description = $result->service_description;
                $duration = $result->service_duration;
                $price = $result->price;
                $discount = $result->discount ?? "";
                $image_path = $result->photo;
                $available_date = $result->service_date ?? "";

                echo '<div class="col-md-6 col-lg-4 my-3">';
                echo '<div class="card service-card">';
                echo '<img src="photos/uploads/' . $image_path . '" class="card-img-top service-img" alt="Service Image">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title service_name">' . $serviceName . '</h5>';
                echo '<p class="card-text">' . $description . '</p>';


                list($hours, $minutes, $seconds) = explode(':', $duration);
                $totalMinutes = $hours * 60 + $minutes;

                if ($totalMinutes == 59) {
                    $totalMinutes += 1;
                }
                echo '<p class="card-text"><small class="text-muted">Duration: ' . $totalMinutes . " min".  '</small></p>';
                echo '<p class="card-text price">' . $price  . " $".'</p>';


                if ($id_user) {
                    echo '
                      <input type="hidden" class="service-id" value="'.$id_service.'">
                    <button type="button" class="btn btn-primary book-procedure" data-bs-toggle="modal" data-bs-target="#bookProcedureModal">Book procedure</button>
                ';}

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    <div class="space"></div>




    <?php
    include "parts/footer.php";
    ?>


    <!-- Modal -->
    <div class="modal fade" id="bookProcedureModal" tabindex="-1" aria-labelledby="bookProcedureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" class="service-name" id="bookProcedureModalLabel">Book Procedure: &nbsp;&nbsp;&nbsp;&nbsp; <span id="serviceName" style="color: #5d76cb;font-size: 20pt;text-transform: uppercase">
                        </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookProcedureForm">
                        <div>
                            <input type="hidden" name="service_id" value="">
                        </div>
                        <div class="mb-3">
                            <label for="pet" class="form-label">Choose pet</label>
                            <select name="pet" id="pet" class="form-select">
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="vet" class="form-label">Vet</label>
                            <select name="vet" id="vet" class="form-select">
                                <!-- Options will be populated by JS -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Choose date</label>
                            <input type="text" name="date" id="date" class="form-control datepicker">
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Choose time</label>
                            <input type="text" name="time" id="time" class="form-control timepicker">
                        </div>
                        <div class="mb-3">
                            <p  class="form-label">Price: <span class="service-price"> </span></p>

                            <button type="submit" class="btn btn-primary" id="saveDoneTreatment">Save Reservation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    </body>

    </html>
