document.addEventListener('DOMContentLoaded', function () {

    fetchUserInfo();

    function fetchUserInfo() {
        fetch('parts/getUserInfo.php')
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