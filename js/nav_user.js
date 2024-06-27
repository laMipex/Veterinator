document.addEventListener('DOMContentLoaded', function () {

    fetchUserInfo();
    const editButton = document.getElementById('edit-button');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const saveButton = document.getElementById('save-button');
    const editForm = document.getElementById('editForm');

    editButton.addEventListener('click', function () {
        editModal.show();
    });

    saveButton.addEventListener('click', function () {
        editForm.submit();
    });

    const oldPasswordInput = document.getElementById('edit_user_old_pass');
    const newPasswordInput = document.getElementById('edit_user_new_pass');

    oldPasswordInput.addEventListener('input', function () {
        if (oldPasswordInput.value.trim().length > 0) {
            newPasswordInput.parentElement.style.display = 'block'; // Show the parent container
        } else {
            newPasswordInput.parentElement.style.display = 'none'; // Hide the parent container
        }
    });

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