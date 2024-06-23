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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Veterinary Practice - Veterinator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/admin_service_description.js"></script>
</head>
<body>
<div data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">
    <?php
    $id_admin = $_SESSION['id_admin'] ?? "";
    $id_user = $_SESSION['id_user'] ?? "";
    $id_vet = $_SESSION['id_vet'] ?? "";
    getNavbar($id_user,$id_admin,$id_vet);
    ?>

    <form id="insertFormButton" class="d-flex mx-5 my-5">
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#insertModal">Insert</button>
    </form>

    <div class="container">
        <?php
        $sql = "SELECT * FROM services ORDER BY service_name ASC";
        $query = $pdo->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                echo '
                <div class="row mx-5 my-5 service-item" data-id="'.$result->id_service.'">
                    <div class="col-12 col-md-4">
                        <p>Service name: ' . $result->service_name . '</p>
                        <p>Service description: ' . $result->service_description . '</p>
                        <p>Service duration: ' . $result->service_duration . '</p>   
                        <p>Service price: ' . $result->price . '</p>
                        <p>Service discount: ' . $result->discount . '</p>                                               
                    </div>
                    <div class="col-10 col-md-4">
                        <img src="photos/uploads/' . $result->photo . '" alt="Service Image" width="250" height="200"><br><br>                
                    </div>                 
                    <div class="col-2  col-md-4">                                  
                        <button class="btn btn-primary updateBtn" data-bs-toggle="modal" data-bs-target="#updateModal">Update</button><br><br>                   
                        <button class="btn btn-primary deleteBtn" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                    </div>
                    <hr> 
                </div>';
            }
        }
        ?>
    </div>
</div>

<!-- Insert Modal -->
<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertModalLabel">Insert Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="insertForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="insert">
                    <div class="mb-3">
                        <label for="name" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Service Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Service Duration</label>
                        <input type="text" class="form-control" id="duration" name="duration">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Service Price</label>
                        <input type="text" class="form-control" id="price" name="price">
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Service Discount</label>
                        <input type="text" class="form-control" id="discount" name="discount">
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Service Photo</label>
                        <input type="file" class="form-control" id="photo" name="file">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_service" id="updateIdService">
                    <input type="hidden" name="action" value="update">
                    <div class="mb-3">
                        <label for="updateName" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="updateName" name="service_name">
                    </div>
                    <div class="mb-3">
                        <label for="updateDescription" class="form-label">Service Description</label>
                        <textarea class="form-control" id="updateDescription" name="service_description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="updateDuration" class="form-label">Service Duration</label>
                        <input type="text" class="form-control" id="updateDuration" name="service_duration">
                    </div>
                    <div class="mb-3">
                        <label for="updatePrice" class="form-label">Service Price</label>
                        <input type="text" class="form-control" id="updatePrice" name="service_price">
                    </div>
                    <div class="mb-3">
                        <label for="updateDiscount" class="form-label">Service Discount</label>
                        <input type="text" class="form-control" id="updateDiscount" name="service_discount">
                    </div>
                    <div class="mb-3">
                        <label for="updatePhoto" class="form-label">Service Photo</label>
                        <input type="file" class="form-control" id="updatePhoto" name="file">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteForm" method="post">
                    <input type="hidden" name="id_service" id="deleteIdService">
                    <input type="hidden" name="action" value="delete">
                    <p>Are you sure you want to delete this service?</p>
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
