<?php
session_start();
require "db_config.php";
require "parts/functions.php";

$id_admin = $_SESSION['id_admin'] ?? "";
$id_user = $_SESSION['id_user'] ?? "";
$id_vet = $_SESSION['id_vet'] ?? "";
getNavbar($id_user, $id_admin, $id_vet);

$id_pet = isset($_GET['pet_id']) ? $_GET['pet_id'] : null;

if ($id_pet === null) {
    echo "Pet ID not provided.";
    exit;
}

$sql = "SELECT 
        CONCAT(IFNULL(v.vet_fname, 'No chosen vet'), ' ', IFNULL(v.vet_lname, '')) AS vet_name,
        p.id_pet, p.pet_name, p.age, p.species, p.breed, p.photo                   
        FROM pet p 
        LEFT JOIN vet v ON p.id_vet = v.id_vet 
        WHERE p.id_pet = :id_pet";
$query = $pdo->prepare($sql);
$query->bindParam(':id_pet', $id_pet, PDO::PARAM_INT);
$query->execute();
$results = $query->fetch(PDO::FETCH_ASSOC);

$sql2 = "SELECT * FROM vet";
$query2 = $pdo->prepare($sql2);
$query2->execute();
$results2 = $query2->fetchAll(PDO::FETCH_OBJ);

if (!$results) {
    echo "Pet not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edit_pet_name = $_POST['pet_name'] ?? '';
    $edit_age = $_POST['age'] ?? '';
    $edit_species = $_POST['species'] ?? '';
    $edit_breed = $_POST['breed'] ?? '';
    $edit_vet = $_POST['edit_vet'] ?? null;
    $edit_photo = $_FILES['file'] ?? null;

    $name = $edit_pet_name;

    if ($edit_vet || $edit_photo) {
        if ($edit_photo && $edit_photo['error'] === UPLOAD_ERR_OK) {
            $file_temp = $edit_photo['tmp_name'];
            $file_name = $edit_photo['name'];

            if (!exif_imagetype($file_temp)) {
                echo 'File is not a picture!!';
                exit;
            }

            $ext_temp = explode(".", $file_name);
            $extension = end($ext_temp);
            $new_file_name = date("YmdHis") . "-$name.$extension";
            $upload = "photos/uploadsPet/$new_file_name";

            if (!move_uploaded_file($file_temp, $upload)) {
                echo 'Error moving uploaded file!';
                exit;
            }
        } else {
            $new_file_name = $results['photo'];
        }

        $updatePetQuery = $pdo->prepare("UPDATE pet SET 
            id_vet = :id_vet, 
            pet_name = :pet_name, 
            age = :age, 
            species = :species, 
            breed = :breed, 
            photo = :photo 
            WHERE id_pet = :id_pet");

        $updatePetQuery->execute([
            'id_pet' => $id_pet,
            'id_vet' => $edit_vet ?: $results['id_vet'],
            'pet_name' => $edit_pet_name,
            'age' => $edit_age,
            'species' => $edit_species,
            'breed' => $edit_breed,
            'photo' => $new_file_name,
        ]);

        echo 'Pet updated successfully';
    } else {
        $updatePetQuery = $pdo->prepare("UPDATE pet SET 
            pet_name = :pet_name, 
            age = :age, 
            species = :species, 
            breed = :breed 
            WHERE id_pet = :id_pet");

        $updatePetQuery->execute([
            'id_pet' => $id_pet,
            'pet_name' => $edit_pet_name,
            'age' => $edit_age,
            'species' => $edit_species,
            'breed' => $edit_breed,
        ]);

        echo 'Pet updated successfully';
    }

    $query->execute();
    $results = $query->fetch(PDO::FETCH_ASSOC);
}
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
        button #savePet {
            padding: 5px;
        }
        #file {
            display: none;
        }
    </style>
    <script src="js/edit_profile.js"></script>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="50">

<div class="container">
    <div class="profileContainer">
        <div class="profileHeader">
            <h2>Pet Profile</h2>
        </div>
        <div class="profileContent">
            <form id="profileForm" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="pet_name" class="col-sm-3 col-form-label">Pet Name</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control" id="pet_name" name="pet_name" value="<?php echo htmlspecialchars($results['pet_name']); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="age" class="col-sm-3 col-form-label">Age</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($results['age']); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="species" class="col-sm-3 col-form-label">Species</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control" id="species" name="species" value="<?php echo htmlspecialchars($results['species']); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="breed" class="col-sm-3 col-form-label">Breed</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control" id="breed" name="breed" value="<?php echo htmlspecialchars($results['breed']); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="photo" class="col-sm-3 col-form-label">Photo</label>
                    <div class="col-sm-9">
                        <img id="photo" src="photos/uploadsPet/<?php echo htmlspecialchars($results['photo']); ?>" alt="Profile Photo" width="100" height="100" style="border-radius: 50px">
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="edit-button" data-bs-toggle="modal" data-bs-target="#editModal">Edit Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="edit_pet_name">Pet Name</label>
                        <input type="text" class="form-control" id="edit_pet_name" name="pet_name" value="<?php echo htmlspecialchars($results['pet_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_age">Age</label>
                        <input type="text" class="form-control" id="edit_age" name="age" value="<?php echo htmlspecialchars($results['age']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_species">Species</label>
                        <input type="text" class="form-control" id="edit_species" name="species" value="<?php echo htmlspecialchars($results['species']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_breed">Breed</label>
                        <input type="text" class="form-control" id="edit_breed" name="breed" value="<?php echo htmlspecialchars($results['breed']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="edit_vet">Choose vet:</label>
                        <select name="edit_vet" id="edit_vet" class="form-select">
                            <option value="-1"> -Choose a vet- </option>
                            <?php foreach ($results2 as $vet): ?>
                                <option value="<?php echo htmlspecialchars($vet->id_vet); ?>">
                                    <?php echo htmlspecialchars($vet->vet_fname . ' ' . $vet->vet_lname); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_photo" class="form-label">Photo</label>
                        <img src="photos/uploadsPet/<?php echo htmlspecialchars($results['photo']); ?>" alt="Profile Photo" width="70" height="70" style="border-radius: 50px">
                        <input type="file" class="form-control" id="edit_photo" name="file">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editForm" class="btn btn-primary" id="save-button">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<?php include "parts/footer.php"; ?>

</body>
</html>
