document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('chooseVet').addEventListener('click', function() {
        const selectedVet = document.getElementById('vet').value;
        const idPet = document.getElementById('id_pet').value;

        if (selectedVet == -1) {
            alert('Please choose a veterinarian.');
            return;
        }

        // Confirmation dialog
        const confirmMsg = "Are you sure you want to choose this veterinarian?";
        if (!confirm(confirmMsg)) {
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_vet.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            alert(response.message);
                        } else {
                            alert("Error: " + response.message);
                        }
                    } catch (e) {
                        console.error("Failed to parse JSON response: " + e);
                        console.error("Response text: " + xhr.responseText);
                    }
                } else {
                    console.error("Request failed: " + xhr.status);
                }
            }
        };
        const data = JSON.stringify({ "id_pet": idPet, "id_vet": selectedVet });
        xhr.send(data);
    });
});
