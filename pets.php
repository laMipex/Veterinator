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
        <link rel="stylesheet" href="styless/navBar.css">
        <link rel="stylesheet" href="styless/logIn.css"
        <link rel="stylesheet" href="styless/hover-min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
        <title>Veterinary Practice - Veterinator</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">

        </script>
        <script src="js/pets.js"></script>
        <style>

            .error {
                color: red;

            }

            .error #name {
                border: 1px solid #f00;
            }

            .error #age {
                border: 1px solid #f00;
            }

            button #savePet{
                padding: 5px;
            }
            #photo
            {
                display: none;
            }
        </style>

    </head>
    <body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

    <?php
    $id_admin = $_SESSION['id_admin'] ?? "";
    $id_user = $_SESSION['id_user'] ?? "";
    $id_vet = $_SESSION['id_vet'] ?? "";
    getNavbar($id_user,$id_admin,$id_vet);
    ?>


    <button type="submit" id="addPet" class="btn btn-primary mx-5 my-5">Add Pet</button>

    <div class="container mx-5 my-5">
        <form enctype="multipart/form-data" id="insertPetForm" style="display: none;">

            <div class="row">
                <div class="col-12 col-md-6 mx-5 pt-3">
                    <label for="pet_name" class="form-label">Pet name:</label>
                    <input type="text" class="form-control" id="pet_name" placeholder="pet_name"
                           name="pet_name">
                    <small></small>
                </div>
                <div class="col-12 col-md-6  mx-5 pt-3">
                    <label for="age" class="form-label">Pet age:</label>
                    <input type="text" class="form-control" id="age" placeholder="age"
                           name="age">
                    <small></small>
                </div>
                <div class="col-12 col-md-6 mx-5 pt-3">
                    <label for="species" class="form-label">Pet species</label>
                    <input type="text" class="form-control" id="species" placeholder="species"
                           name="species">
                    <small></small>
                </div>
                <div class="col-12 col-md-6 mx-5 pt-3">
                    <label for="breed" class="form-label">Pet breed</label>
                    <input type="text" class="form-control" id="breed" placeholder="breed"
                           name="breed">
                    <small></small>
                </div>
                <div class="col-12 col-md-6 mx-5 pt-3">
                    <label for="photo" class="form-label">Pet photo:
                        <img src="photos/uploads/upload.png" alt="upload" width="50" title="Choose an image"></label>
                    <input type="file" name="file" id="file"><br>
                    <small></small>
                </div>

            </div>
            <div class="pt-3">

                <button type="submit" class="btn btn-primary align-middle" id="savePet" >Save</button>

            </div>

        </form>


    </div>




    <br><br>
    <div id="result"></div>




    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div>




    <?php
    include "parts/footer.php";
    ?>

    </body>
</html>
