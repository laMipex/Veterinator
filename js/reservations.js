document.addEventListener("DOMContentLoaded", function() {
    fetchReservations();


    document.body.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'submit') {
            let row = e.target.closest('tr');
            let idRes = row.querySelector('input[type="hidden"]').value;
            let codeInput = row.querySelector('#codeInput').value;
            let negtivePoints = row.querySelector('#negative_points').value;
            sendData(idRes, codeInput, negtivePoints);
        }
    });

    document.querySelector('#saveDoneTreatment').addEventListener('click', function() {
        let form = document.querySelector('#treatmentForm');
        let condition = document.querySelector('#condition').value;
        let diagnosis = document.querySelector('#diagnosis').value;
        let medication = document.querySelector('#medication').value;
        let duration = document.querySelector('#duration').value;
        let reservationCode = document.querySelector('#codeForm').value;

        let data = {
            duration:duration,
            condition: condition,
            diagnosis: diagnosis,
            medication: medication,
            reservationCode: reservationCode
        };

        let request = new XMLHttpRequest();
        request.open("POST", "parts/doneTreatment.php", true);
        request.setRequestHeader("Content-Type", "application/json");
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                let response = JSON.parse(request.responseText);
                if (response.status === 'success') {
                    alert('Treatment saved successfully');
                    form.style.display = 'none';
                } else {
                    alert('Failed to save treatment: ' + response.message);
                }
            }
        };
        request.send(JSON.stringify(data));
    });


    document.querySelector('.close-button').addEventListener('click', function() {
        document.querySelector('#treatmentForm').style.display = 'none';
    });
});

function fetchReservations() {
    let tableReservation = document.querySelector("#table_reservation");
    let request = new XMLHttpRequest();
    let url = "parts/getListReservation.php";
    request.open("POST", url, true);
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            tableReservation.innerHTML = request.responseText;
        }
    };
    request.send();
}

function sendData(idRes, codeInput, negtivePoints) {
    let treatmentForm = document.querySelector('#treatmentForm');
    const resultMessage = document.querySelector('#resultMessage');
    const result = document.querySelector('#result');
    const inputs = document.querySelectorAll('.clean input');

    let isValid = true;
    treatmentForm.style.display = "none";

    if (isValid) {
        let request = new XMLHttpRequest();
        resultMessage.innerHTML = '<img src="photos/index_photos/ajax_loader.gif" alt="loading">';

        request.open("POST", "parts/getCodeReservations.php", true);
        request.setRequestHeader("Content-Type", "application/json");
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                let jsonData = JSON.parse(request.responseText);
                if (jsonData.status === 'success') {

                    if (jsonData.reservations && jsonData.reservations.length > 0) {
                        result.innerHTML = "";

                        jsonData.reservations.forEach(reservation => {
                            var reservationTime = reservation.service_duration;
                            var splitTime = reservationTime.split(':');
                            var formattedTime = splitTime[0] + ':' + splitTime[1];
                            const reservationInfo = `
                                <div>                                                              
                                    <p>User: ${reservation.user_fname} ${reservation.user_lname}</p>
                                    <p>Pet Name: ${reservation.pet_name}</p>
                                    <p>Service: ${reservation.service_name}</p>
                                    <p>Date: ${reservation.reservation_date}</p>
                                    <p>Time: ${reservation.reservation_time}</p>
                                    <p>Price: ${reservation.treatment_price}$</p>
                                    <label for="duration">Duration:</label>
                                    <input type="text" id="duration" value="${formattedTime}">
                                    <input type="hidden" id="codeForm" value="${reservation.code}">
                                
                                </div>
                            `;
                            result.innerHTML += reservationInfo;
                        });
                        inputs.forEach(element => element.value = '');
                        treatmentForm.style.display = "block";
                        resultMessage.innerHTML = `<p>${jsonData.message}</p>`;
                        } else {
                            treatmentForm.style.display = "none";
                            resultMessage.innerHTML = `<p>${jsonData.message}</p>`;
                        }

                        resultMessage.innerHTML = `<p>${jsonData.message}</p>`;
                        fetchReservations();

                } else {
                    inputs.forEach(element => element.value = '');
                    treatmentForm.style.display = "none";
                    resultMessage.innerHTML = `<p>${jsonData.message}</p>`;
                    fetchReservations();
                }

               // setTimeout(() => resultMessage.innerHTML = "", 5000);
            }
        };

        let data = JSON.stringify({"code_input": codeInput,"negative_points": parseInt(negtivePoints),"idRes":idRes });
        request.send(data);
    }
}

