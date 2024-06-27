

<script src="js/nav_vet.js"></script>
<nav class="navbar navbar-expand-md">
    <div class="container-fluid">
        <img class="logo" src="photos/index_photos/vet.png" alt="logo">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-ls-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="services_description.php">Services</a>
                </li>
                <li class="nav-item">
               <li class="nav-item">
                    <a class="nav-link" href="pets.php">Pet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservations.php">Reservations</a>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-ls-0">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
<!--                        <i class="bi bi-person-circle"></i>-->
                        <img id="profile_photo" src="" alt="Profile Photo" width="50" height="50" style="border-radius: 50px">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li class="dropdown-item"><button class="btn" id="edit-button" >Profile</button> </li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>

    #editFormContainer{
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


    .close{
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }

</style>

<div id="editFormContainer" style="display: none;">
    <form id="editForm" method="post" enctype="multipart/form-data">
        <span class="close">&times;</span>

            <h1 id="edit_vet_name"></h1>
            <div class="form-group">
                <label for="edit_vet_email">Email</label>
                <input type="email" class="form-control" id="edit_vet_email" name="vet_email">
            </div>
            <div class="form-group">
                <label for="edit_vet_old_pass">Password</label>
                <input type="password" class="form-control" id="edit_vet_old_pass" name="edit_vet_old_pass">
            </div>
            <div class="form-group">
                <label for="edit_vet_new_pass">New Password</label>
                <input type="password" class="form-control" id="edit_vet_new_pass" name="edit_vet_new_pass">
            </div>
            <div class="form-group">
                <label for="edit_vet_phone">Phone</label>
                <input type="text" class="form-control" id="edit_vet_phone" name="vet_phone">
            </div>
            <div class="form-group">
                <label for="edit_city">City</label>
                <input type="text" class="form-control" id="edit_city" name="city">
            </div>
            <div class="form-group">
                <label for="work_start">Work Start</label>
                <input type="time" class="form-control" id="work_start" name="work_start">
            </div>
            <div class="form-group">
                <label for="work_end">Work End</label>
                <input type="time" class="form-control" id="work_end" name="work_end">
            </div>
            <div class="form-group">
                <label for="edit_photo" class="form-label">Photo</label>
                <img id="edit_photo_preview" src="" alt="Profile Photo" width="100" height="100" style="border-radius: 50px">
                <input type="file" class="form-control" id="edit_photo" name="file" onchange="previewPhoto(this);">
            </div>

        <button type="button" class="btn btn-primary" id="save-button">Save Changes</button>
    </form>
</div>

<!--Edit-->






