document.addEventListener('DOMContentLoaded', function() {
    // Insert Service
    document.getElementById('insertForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('admin_service_forms.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(response => {
                alert(response);
                location.reload();
            })
            .catch(error => {
                alert('Error: ' + error);
            });
    });



    // Update Service
    document.querySelectorAll('.updateBtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Locate the closest '.service-item' relative to the clicked button
            var serviceItem = this.closest('.service-item');

            // Update the values using vanilla JavaScript
            id = serviceItem.dataset.id;
            document.querySelector('#updateIdService').value = id;
            document.getElementById('updateName').value = serviceItem.querySelector('h5').textContent;
            document.getElementById('updateDescription').value = serviceItem.querySelector('p:nth-of-type(1)').textContent;
            document.getElementById('updateDuration').value = serviceItem.querySelector('p:nth-of-type(2)').textContent.split(': ')[1];
            document.getElementById('updatePrice').value = serviceItem.querySelector('p:nth-of-type(3)').textContent.split(': ')[1];
            document.getElementById('updateDiscount').value = serviceItem.querySelector('p:nth-of-type(4)').textContent.split(': ')[1];
        });
    });


    // Update Service
    $('#updateForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'admin_service_forms.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function(error) {
                alert('Error: ' + error.responseText);
            }
        });
    });

    // Delete Service - set service id
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            let serviceItem = this.closest('.service-item');
            document.getElementById('deleteIdService').value = serviceItem.dataset.id;
        });
    });

    // Delete Service
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch('admin_service_forms.php', {
            method: 'POST',
            body: new FormData(this)
        })
            .then(response => response.text())
            .then(response => {
                alert(response);
                location.reload();
            })
            .catch(error => {
                alert('Error: ' + error);
            });
    });
});
