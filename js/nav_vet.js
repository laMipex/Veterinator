document.addEventListener("DOMContentLoaded", function() {

        fetchVetInfo();


// Funkcija za učitavanje podataka o veterinaru i ažuriranje slike profila u navbaru
        function fetchVetInfo() {
            fetch('parts/getVetInfo.php')
                .then(response => response.json())
                .then(data => {
                    const photoSrc = data.photo ? `photos/uploads/${data.photo}` : 'photos/uploads/default_person.png';
                    document.getElementById('profile_photo').src = photoSrc;
                })
                .catch(error => {
                    console.error('Error fetching vet info:', error);
                    document.getElementById('profile_photo').src = 'photos/uploads/default_person.png';
                });

        }


});