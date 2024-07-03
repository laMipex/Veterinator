document.addEventListener('DOMContentLoaded', function () {
    const editButton = document.getElementById('edit-button');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const saveButton = document.getElementById('save-button');
    const editForm = document.getElementById('editForm');

    editButton.addEventListener('click', function (event) {
        event.preventDefault();  // Sprečava podrazumevano ponašanje dugmeta
        editModal.show();
    });

    saveButton.addEventListener('click', function () {
        editForm.submit();
    });
});
