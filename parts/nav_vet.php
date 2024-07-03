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
                        <li><a class="dropdown-item" href="edit_vet.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
