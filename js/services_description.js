document.addEventListener("DOMContentLoaded", init);

function init() {
    document.querySelectorAll('.book-procedure').forEach(function(button) {
        button.addEventListener('click', function() {
            let card = button.closest('.card');
            let serviceId = card.querySelector('.service-id').value;
            let servicePrice = card.querySelector('.price').textContent.trim();

            fetchPetsDropdown();
            fetchVetDropdown(serviceId);

            let bookProcedureForm = document.getElementById('bookProcedureForm');
            bookProcedureForm.querySelector('input[name="service_id"]').value = serviceId;
            bookProcedureForm.querySelector('.service-price').textContent = servicePrice;
        });
    });

    document.querySelector('.close-button').addEventListener('click', function() {
        document.querySelector('#popUpReservations').style.display = 'none';
        document.querySelector('#btnUserReservations').textContent = 'My reservations';
    });

    document.getElementById('btnUserReservations').addEventListener('click', function () {
        let popUpReservations = document.getElementById('popUpReservations');
        if (popUpReservations.style.display === 'none' || popUpReservations.style.display === '') {
            popUpReservations.style.display = 'block';
            this.textContent = 'Hide table.';
        } else {
            popUpReservations.style.display = 'none';
            this.textContent = 'My reservations';
        }
    });

    document.getElementById('saveDoneTreatment').addEventListener('click', function(event) {
        event.preventDefault();
        AddReservation();
    });

    // Initialize datepicker and timepicker
    new Pikaday({ field: document.querySelector('.datepicker'), format: 'YYYY-MM-DD' });

    // Handle vet selection change
    document.getElementById('vet').addEventListener('change', function() {
        resetDatePickerAndTimePicker();
        let vetId = this.value;

        if (vetId !== '-1') {
            let request = new XMLHttpRequest();
            request.open("POST", "parts/getVet_workTime.php", true);
            request.setRequestHeader("Content-Type", "application/json");

            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                    let response = JSON.parse(request.responseText);
                    if (response.status === 'success') {
                        let startDate = new Date();
                        let endDate = new Date();
                        endDate.setDate(endDate.getDate() + 30);

                        // Set up datepicker
                        let datepicker = new Pikaday({
                            field: document.querySelector('.datepicker'),
                            minDate: startDate,
                            maxDate: endDate,
                            format: 'YYYY-MM-DD'
                        });

                        // Set up timepicker with vet's working hours
                        let workStart = response.work_start.substring(0, 5);
                        let workEnd = response.work_end.substring(0, 5);

                        let workEndMinus30 = new Date('1970-01-01T' + workEnd + ':00');
                        workEndMinus30.setMinutes(workEndMinus30.getMinutes() - 30);
                        let workEndMinus30String = ('0' + workEndMinus30.getHours()).slice(-2) + ':' + ('0' + workEndMinus30.getMinutes()).slice(-2);

                        // Initialize timepicker
                        // Use a library like timepicker for better UX
                    } else {
                        alert(response.message);
                    }
                }
            };

            let data = JSON.stringify({ vet_id: vetId });
            request.send(data);
        }
    });

    // Handle date selection change
    document.getElementById('date').addEventListener('change', function() {
        let vetId = document.getElementById('vet').value;
        if (vetId !== '-1') {
            let request = new XMLHttpRequest();
            request.open("POST", "parts/getReservations.php", true);
            request.setRequestHeader("Content-Type", "application/json");

            request.onreadystatechange = function() {
                if (request.readyState === 4 && request.status === 200) {
                    let response = JSON.parse(request.responseText);
                    if (response.status === 'success' && Array.isArray(response.reservations)) {
                        let reservedTimes = [];
                        let durationTimes = [];
                        response.reservations.forEach(function(reservation) {
                            let time = reservation.reservation_time.substring(0, 5);
                            let duration = reservation.service_duration.substring(0, 5);
                            reservedTimes.push(time);
                            durationTimes.push(duration);
                        });

                        let disableTimeRanges = [];

                        reservedTimes.forEach(function(time, index) {
                            let duration = durationTimes[index];
                            let [hours, minutes] = time.split(':').map(Number);
                            let [hoursDur, minutesDur] = duration.split(':').map(Number);

                            let endTime = new Date();
                            endTime.setHours(hours);
                            endTime.setMinutes(minutes + minutesDur);

                            if (hoursDur > 0) {
                                endTime.setHours(endTime.getHours() + hoursDur);
                            }

                            let formattedEndTime = formatTime(endTime);

                            disableTimeRanges.push([time, formattedEndTime]);
                        });

                        // Initialize or update timepicker with disableTimeRanges
                        // Use a library like timepicker for better UX
                    }
                }
            };

            let data = JSON.stringify({
                vet_id: vetId,
                reservation_date: document.getElementById('date').value
            });
            request.send(data);
        }
    });
}

function AddReservation() {
    let serviceId = document.getElementById('bookProcedureForm').querySelector('input[name="service_id"]').value;
    let vet = document.getElementById('vet').value;
    let date = document.getElementById('date').value;
    let time = document.getElementById('time').value;
    let petId = document.getElementById('pet').value;
    let price = document.querySelector('.service-price').textContent.trim();

    let isValid = true;

    if (vet === '-1') {
        showErrorMessage(document.getElementById('vet'), "You must select vet!");
        isValid = false;
    } else {
        hideErrorMessage(document.getElementById('vet'));
    }

    if (date === "") {
        showErrorMessage(document.getElementById('date'), "You must select date!");
        isValid = false;
    } else {
        hideErrorMessage(document.getElementById('date'));
    }

    if (time === "") {
        showErrorMessage(document.getElementById('time'), "You must select time!");
        isValid = false;
    } else {
        hideErrorMessage(document.getElementById('time'));
    }

    if (isValid) {
        let request = new XMLHttpRequest();
        request.open("POST", "parts/insert_reservation.php", true);
        request.setRequestHeader("Content-Type", "application/json");

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                let jsonData = JSON.parse(request.responseText);
                alert(jsonData.message);
                document.getElementById('bookProcedureModal').style.display = 'none';
                document.getElementById('bookProcedureForm').reset();
            }
        };

        let data = JSON.stringify({
            id_service: serviceId,
            id_pet: petId,
            id_vet: vet,
            reservation_date: date,
            reservation_time: time,
            price: price
        });
        request.send(data);
    }
}

function fetchPetsDropdown() {
    let request = new XMLHttpRequest();
    request.open("GET", "parts/pet_dropdown.php", true);
    request.setRequestHeader("Content-Type", "application/json");

    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            let petDropdown = document.getElementById('pet');
            petDropdown.innerHTML = '<option value="-1">- Choose a pet -</option>';

            if (response.status === 'success') {
                response.pets.forEach(function(pet) {
                    petDropdown.innerHTML += `<option value="${pet.id_pet}">${pet.pet_name}</option>`;
                });
            } else {
                console.error(response.message);
            }
        }
    };

    request.send();
}

function fetchVetDropdown(serviceId) {
    let request = new XMLHttpRequest();
    request.open("POST", "parts/vet_dropdown.php", true);
    request.setRequestHeader("Content-Type", "application/json");

    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            let vetDropdown = document.getElementById('vet');
            vetDropdown.innerHTML = '<option value="-1">- Choose a vet -</option>';

            if (response.status === 'success') {
                response.vets.forEach(function(vet) {
                    vetDropdown.innerHTML += `<option value="${vet.id_vet}">${vet.vet_fname} ${vet.vet_lname}</option>`;
                });
            } else {
                console.error(response.message);
            }
        }
    };

    let data = JSON.stringify({ service: serviceId });
    request.send(data);
}

function showErrorMessage(field, message) {
    field.classList.add('error');

    let error = field.nextElementSibling;
    if (!error) {
        field.insertAdjacentHTML('afterend', `<small class="text-danger">${message}</small>`);
    } else {
        error.textContent = message;
    }
}

function hideErrorMessage(field) {
    field.classList.remove('error');
    let error = field.nextElementSibling;
    if (error) {
        error.remove();
    }
}

function resetDatePickerAndTimePicker() {
    document.getElementById('date').value = '';
    document.getElementById('time').value = '';
}

function formatTime(date) {
    let hours = String(date.getHours()).padStart(2, '0');
    let minutes = String(date.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
}
