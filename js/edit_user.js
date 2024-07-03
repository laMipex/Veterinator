document.addEventListener('DOMContentLoaded', function () {
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
});