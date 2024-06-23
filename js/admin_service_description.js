$(document).ready(function() {
    // Insert Service
    $('#insertForm').on('submit', function(e) {
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

    // Update Service - populate modal with current values
    $('.updateBtn').on('click', function() {
        let serviceItem = $(this).closest('.service-item');
        $('#updateIdService').val(serviceItem.data('id'));
        $('#updateName').val(serviceItem.find('p').eq(0).text().split(': ')[1]);
        $('#updateDescription').val(serviceItem.find('p').eq(1).text().split(': ')[1]);
        $('#updateDuration').val(serviceItem.find('p').eq(2).text().split(': ')[1]);
        $('#updatePrice').val(serviceItem.find('p').eq(3).text().split(': ')[1]);
        $('#updateDiscount').val(serviceItem.find('p').eq(4).text().split(': ')[1]);
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
    $('.deleteBtn').on('click', function() {
        let serviceItem = $(this).closest('.service-item');
        $('#deleteIdService').val(serviceItem.data('id'));
    });

    // Delete Service
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'admin_service_forms.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function(error) {
                alert('Error: ' + error.responseText);
            }
        });
    });
});
