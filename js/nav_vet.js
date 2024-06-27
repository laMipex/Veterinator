// Postavljanje event listenera za DOMContentLoaded događaj
//window.addEventListener('DOMContentLoaded', function () {
    // Funkcija init se poziva čim se učita DOM
//init();
//});

// Funkcija init koja postavlja event listenere i poziva fetchVetInfo()
document.addEventListener("DOMContentLoaded", function() {
    {
        fetchVetInfo();

        // Postavljanje event listenera za klik na dugme "Edit"
        const editButton = document.getElementById('edit-button');
        editButton.addEventListener('click', function () {
            fetchVetInfoAndShowForm();
        });

        // Postavljanje event listenera za klik na dugme "Save Changes"
        const saveButton = document.getElementById('save-button');
        saveButton.addEventListener('click', function () {
            saveVetInfo();
        });

        // Funkcija za zatvaranje forme (može se dodati ako je potrebno)
        const closeButton = document.querySelector('.close');
        closeButton.addEventListener('click', function () {
            closeEditForm();
        });
    }

// Funkcija za učitavanje podataka o veterinaru i ažuriranje slike profila u navbaru
    function fetchVetInfo() {
        fetch('parts/getVetInfo.php')
            .then(response => response.json())
            .then(data => {
                const photoSrc = data.photo ? `photos/uploads/${data.photo}` : 'photos/uploads/default_person.png';
                document.getElementById('profile_photo').src = photoSrc;
                // Ako želite prikazati ime veterinaru negde drugde u dokumentu, možete dodati ovdje logiku za to
            })
            .catch(error => {
                console.error('Error fetching vet info:', error);
                document.getElementById('profile_photo').src = 'photos/uploads/default_person.png';
            });
    }

// Funkcija za učitavanje podataka o veterinaru i prikazivanje forme za uređivanje
    function fetchVetInfoAndShowForm() {
        fetchVetInfo();
        fetch('parts/getVetInfo.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_vet_name').textContent = data.vet_name;
                document.getElementById('edit_vet_email').value = data.vet_email;
                document.getElementById('edit_vet_phone').value = data.vet_phone;
                document.getElementById('edit_city').value = data.city;
                document.getElementById('edit_photo_preview').src = `photos/uploads/${data.photo}`;
                const workStart = formatTime(data.work_start);
                const workEnd = formatTime(data.work_end);
                document.getElementById('work_start').value = workStart;
                document.getElementById('work_end').value = workEnd;

                // Prikaži formu za uređivanje
                document.getElementById('editFormContainer').style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching vet info:', error);
            });
    }

// Funkcija za zatvaranje forme za uređivanje
    function closeEditForm() {
        // Sakrij formu za uređivanje
        document.getElementById('editFormContainer').style.display = 'none';
    }

// Funkcija za čuvanje informacija o veterinaru nakon izmene
    function saveVetInfo() {
        const editForm = document.getElementById('editForm');
        const formData = new FormData(editForm);

        fetch('parts/updateVet.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert('Vet profile updated successfully:', data);

                // Sakrij formu za uređivanje nakon uspešnog čuvanja
                closeEditForm();

                // Ponovo učitajte informacije o veterinaru u navbaru nakon ažuriranja
                fetchVetInfo();
            })
            .catch(error => {
                console.error('Error updating vet profile:', error);
                // Možete dodati kod za upravljanje greškom ako je potrebno
            });
    }

// Funkcija za formatiranje vremena
    function formatTime(timeString) {
        const timeParts = timeString.split(':');
        return `${timeParts[0]}:${timeParts[1]}`;
    }

// Funkcija za prikaz slike koju korisnik izabere pre nego što je otpremi
    function previewPhoto(input) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('edit_photo_preview').setAttribute('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
});
