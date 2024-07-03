document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const cardContainer = document.querySelector('.cardSearch'); // Kontejner gde se prikazuju kartice

    searchInput.addEventListener('input', function() {
        const inputValue = searchInput.value.trim().toLowerCase();

        // AJAX poziv da se dobiju filtrirani rezultati sa servera
        fetch('parts/getSearchServices.php?search_letter=' + inputValue)
            .then(response => response.text())
            .then(data => {
                cardContainer.innerHTML = data; // Zameni sadržaj kontejnera sa novim filtriranim rezultatima
            })
            .catch(error => console.error('Error fetching data: ', error));
    });


    // Sortiranje kartica po ceni
    const sortOptions = document.querySelectorAll('.sort-option');

    sortOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const sortType = option.dataset.sort; // asc ili desc
            const url = `parts/getSortedServices.php?sort=${sortType}`;

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    cardContainer.innerHTML = data; // Zameni sadržaj kontejnera sa sortiranim rezultatima
                })
                .catch(error => console.error('Error fetching data: ', error));
        });
    });

});

window.addEventListener("DOMContentLoaded", init);

    function init() {
        // When the "Book procedure" button is clicked
        document.querySelectorAll('.book-procedure').forEach(function (button) {
            button.addEventListener('click', function () {
                // Find the hidden input field with the service ID within the same card
                var card = button.closest('.card');
                var serviceId = card.querySelector('.service-id').value;
                var service = card.querySelector('.service_name').textContent.trim();
                var servicePrice = card.querySelector('.price').textContent.trim();
                fetchPetsDropdown(); // Fetch and populate the pet dropdown

                fetchVetDropdown(serviceId); // Fetch and populate the vet dropdown

                var bookProcedureForm = document.getElementById('bookProcedureForm');
                bookProcedureForm.querySelector('input[name="service_id"]').value = serviceId; // Set the service ID in the hidden input within the modal
                bookProcedureForm.querySelector('.service-price').textContent = servicePrice; // Set the price in the modal
                document.getElementById('serviceName').textContent = service; // Update the service name in the modal header


                $('#vet').val('-1');
                resetDatePickerAndTimePicker();


                $('#date').hide();
                $('#time').hide();

            });
        });





        document.querySelector('.close-button').addEventListener('click', function () {
            document.querySelector('#popUpReservations').style.display = 'none';
            document.querySelector('#btnUserReservations').textContent = 'My reservations';
        });

        document.getElementById('btnUserReservations').addEventListener('click', function () {
            var popUpReservations = document.getElementById('popUpReservations');
            if (popUpReservations.style.display === 'none' || popUpReservations.style.display === '') {
                popUpReservations.style.display = 'block';
                this.textContent = 'Hide table.';
            } else {
                popUpReservations.style.display = 'none';
                this.textContent = 'My reservations';
            }
        });


        document.querySelectorAll('.cancel').forEach(function(button) {
            button.addEventListener('click', function() {
                var row = this.closest('tr');
                var id_reservation = row.querySelector('.id_res').value;

                var data = {
                    id_reservation: id_reservation
                };

                fetch('parts/cancel_procedure.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'OK') {
                            alert('Reservation cancelled successfully.');
                            row.remove(); // Ukloni red iz tabele ako je otkazivanje uspešno
                        } else {
                            alert(data.message); // Prikaži stvarnu poruku o grešci
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred.');
                    });
            });
        });


        document.getElementById('saveDoneTreatment').addEventListener('click', function (event) {
            event.preventDefault();
            AddReservation();
        });




        // Handle vet selection change
        $('#vet').change(function () {
            resetDatePickerAndTimePicker(); // Reset date and time pickers

            // Initialize datepicker and timepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

            $('.timepicker').timepicker({
                timeFormat: 'H:i', // Use HH:mm format
                interval: 15,      // Interval of 15 minutes
                dropdown: true,    // Show dropdown to select time
                scrollbar: true    // Enable scrollbar for timepicker
            });

            let vetId = $(this).val();

            if (vetId === '-1') {
                // Sakrijte datepicker i timepicker ako vet nije odabran
                $('#date').hide();
                $('#time').hide();
            } else {
                // Prikaz datepicker-a i timepicker-a
                $('#date').show();
                $('#time').show();
            }

            if (vetId !== '-1') {
                $.ajax({
                    url: 'parts/getVet_workTime.php',
                    type: 'POST',
                    data: {vet_id: vetId},
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            // Set up datepicker for 30 days from today
                            var startDate = new Date();
                            startDate.setDate(startDate.getDate()); // Start from today
                            var endDate = new Date();
                            endDate.setDate(endDate.getDate() + 30); // 30 days from today

                            $('.datepicker').datepicker('setStartDate', startDate);
                            $('.datepicker').datepicker('setEndDate', endDate);

                            // Set up timepicker with vet's working hours
                            var workStart = response.work_start.substring(0, 5); // Extract HH:mm
                            var workEnd = response.work_end.substring(0, 5);     // Extract HH:mm

                            // Calculate 30 minutes before workEnd
                            var workEndMinus30 = new Date('1970-01-01T' + workEnd + ':00');
                            workEndMinus30.setMinutes(workEndMinus30.getMinutes() - 30);
                            var workEndMinus30String = ('0' + workEndMinus30.getHours()).slice(-2) + ':' + ('0' + workEndMinus30.getMinutes()).slice(-2);

                            // Configure timepicker options
                            $('#time').timepicker('option', {
                                'minTime': workStart,
                                'maxTime': workEnd,
                                'step': 15, // Set step to 15 minutes
                                'maxTime': workEndMinus30String // Set max time to 30 minutes before workEnd
                            });

                            // Handle date selection change
                            $('#date').change(function () {
                                let selectedDate = $('#date').val();
                                let vetId = $('#vet').val();

                                if (vetId !== '-1') {
                                    $.ajax({
                                        url: 'parts/getReservations.php',
                                        type: 'POST',
                                        data: JSON.stringify({
                                            vet_id: vetId,
                                            reservation_date: selectedDate
                                        }),
                                        contentType: 'application/json',
                                        dataType: 'json',
                                        success: function (response) {
                                            if (response.status === 'success' && Array.isArray(response.reservations)) {
                                                let reservedTimes = [];
                                                let durationTimes = [];
                                                for (let i = 0; i < response.reservations.length; i++) {
                                                    let time = response.reservations[i].reservation_time.substring(0, 5);
                                                    let duration = response.reservations[i].service_duration.substring(0, 5);
                                                    reservedTimes.push(time);
                                                    durationTimes.push(duration);
                                                }

                                                let disableTimeRanges = [];

                                                // Check if the selected date is today
                                                var today = new Date();
                                                var selectedDateObj = new Date(selectedDate);

                                                if (selectedDateObj.toDateString() === today.toDateString()) {
                                                    // Loop through reservedTimes for today and push corresponding intervals into disableTimeRanges
                                                    for (let i = 0; i < reservedTimes.length; i++) {
                                                        let time = reservedTimes[i];
                                                        let duration = durationTimes[i];

                                                        let startTime = new Date('1970-01-01T' + time + ':00');
                                                        let endTime = new Date(startTime.getTime() + getDurationInMilliseconds(duration));

                                                        let formattedEndTime = formatTime(endTime);

                                                        disableTimeRanges.push([time, formattedEndTime]);
                                                    }

                                                    let currentTime = new Date();
                                                    let roundedTime = roundToNextInterval(currentTime);
                                                    let currentFormattedTime = formatTime(roundedTime);

                                                    // Add current time as minTime for today
                                                    $('#time').timepicker('option', 'minTime', currentFormattedTime);

                                                    // Check if current time is after the last time in disableTimeRanges
                                                    let lastDisabledTime = disableTimeRanges.length > 0 ? disableTimeRanges[disableTimeRanges.length - 1][1] : workEnd;
                                                    if (compareTime(currentFormattedTime, lastDisabledTime) >= 0) {
                                                        // If current time is greater than or equal to the last disabled time, hide timepicker
                                                        $('#time').hide();
                                                    } else {
                                                        // Otherwise, show timepicker
                                                        $('#time').show();
                                                    }
                                                } else {
                                                    // Loop through reservedTimes for other dates and push corresponding intervals into disableTimeRanges
                                                    for (let i = 0; i < reservedTimes.length; i++) {
                                                        let time = reservedTimes[i];
                                                        let duration = durationTimes[i];

                                                        let startTime = new Date('1970-01-01T' + time + ':00');
                                                        let endTime = new Date(startTime.getTime() + getDurationInMilliseconds(duration));

                                                        let formattedEndTime = formatTime(endTime);

                                                        disableTimeRanges.push([time, formattedEndTime]);
                                                    }

                                                    $('#time').timepicker('option', 'minTime', workStart);
                                                    $('#time').show(); // Always show timepicker for future dates
                                                }

                                                // Initialize or update timepicker with disableTimeRanges
                                                $('#time').timepicker('option', {
                                                    disableTimeRanges: disableTimeRanges
                                                });
                                            }
                                        },
                                        error: function () {
                                            alert('Error retrieving reservations');
                                        }
                                    });
                                }
                            });

                            // Trigger change event to initialize timepicker for selected date
                            $('#date').trigger('change');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Error retrieving vet work time');
                    }
                });
            }
        });

        function compareTime(time1, time2) {
            var [hours1, minutes1] = time1.split(':').map(Number);
            var [hours2, minutes2] = time2.split(':').map(Number);

            if (hours1 > hours2) {
                return 1;
            } else if (hours1 < hours2) {
                return -1;
            } else {
                if (minutes1 > minutes2) {
                    return 1;
                } else if (minutes1 < minutes2) {
                    return -1;
                } else {
                    return 0;
                }
            }
        }

        function getDurationInMilliseconds(duration) {
            let [hours, minutes] = duration.split(':').map(Number);
            return (hours * 60 + minutes) * 60 * 1000;
        }

        function formatTime(date) {
            let hours = date.getHours().toString().padStart(2, '0');
            let minutes = date.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        function roundToNextInterval(date) {
            let minutes = date.getMinutes();
            let nextInterval = Math.ceil(minutes / 15) * 15;
            if (nextInterval === 60) {
                date.setHours(date.getHours() + 1);
                nextInterval = 0;
            }
            date.setMinutes(nextInterval);
            date.setSeconds(0);
            return date;
        }



    }

    async function AddReservation() {
        const serviceId = $('#bookProcedureForm').find('input[name="service_id"]').val();
        const vet = $('#vet');
        const date = $('#date');
        const time = $('#time');
        const petId = $('#pet');
        const price = $('.service-price').text().trim();

        let isValid = true;

        if (vet.val() == -1) {
            showErrorMessage($('#vet'), "You must select vet!");
            isValid = false;
        } else {
            hideErrorMessage($('#vet'));
        }

        if (petId.val() == -1) {
            showErrorMessage($('#pet'), "You must select pet!");
            isValid = false;
        } else {
            hideErrorMessage($('#pet'));
        }

        if (date.val() === "") {
            showErrorMessage($('#date'), "You must select date!");
            isValid = false;
        } else {
            hideErrorMessage($('#date'));
        }

        if (time.val() === "") {
            showErrorMessage($('#time'), "You must select time!");
            isValid = false;
        } else {
            hideErrorMessage($('#time'));
        }

        if (isValid) {
            // Sakrivanje modala i resetovanje forme odmah nakon klika na "Save Reservation"
            $('#bookProcedureModal').modal('hide');


            try {
                const response = await fetch('parts/insert_reservation.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        "id_service": serviceId,
                        "id_pet": petId.val(),
                        "id_vet": vet.val(),
                        "reservation_date": date.val(),
                        "reservation_time": time.val(),
                        "price": price
                    }),
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }

                const jsonData = await response.json();
                $('#bookProcedureForm')[0].reset();
            
            	
                alert(jsonData.message);
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            }
        }
    }

    // Dodavanje event listenera za promene vrednosti
    $(document).ready(function() {
        $('#vet').on('change', function() {
            if ($(this).val() != -1) {
                hideErrorMessage($('#vet'));
            }
        });

        $('#pet').on('change', function() {
            if ($(this).val() != -1) {
                hideErrorMessage($('#pet'));
            }
        });

        $('#date').on('change', function() {
            hideErrorMessage($('#date'));
        });

        $('#time').on('change', function() {
            hideErrorMessage($('#time'));
        });
    });




    function fetchPetsDropdown() {
        $.ajax({
            url: 'parts/pet_dropdown.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const petDropdown = $('#pet');
                petDropdown.empty(); // Clear previous options
                petDropdown.append('<option value="-1">- Choose a pet -</option>');
                if (response.status === 'success') {
                    response.pets.forEach(function(pet) {
                        petDropdown.append(`<option value="${pet.id_pet}">${pet.pet_name}</option>`);
                    });
                } else {
                    console.error(response.message);
                }
            },
            error: function() {
                alert('Error fetching pets');
            }
        });
    }


    function fetchVetDropdown(serviceId) {
        let request = new XMLHttpRequest();
        request.open("POST", "parts/vet_dropdown.php", true);
        request.setRequestHeader("Content-Type", "application/json");

        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                let response = JSON.parse(request.responseText);
                const vetDropdown = $('#vet');

                vetDropdown.empty(); // Clear previous options
                vetDropdown.append('<option value="-1">- Choose a vet -</option>');

                if (response.status === "success") {
                    response.vets.forEach(function (vet) {
                        vetDropdown.append(`<option value="${vet.id_vet}">${vet.vet_fname} ${vet.vet_lname}</option>`);
                    });
                } else {
                    console.error(response.message);
                }
            }
        };

        let data = JSON.stringify({ "service": serviceId });
        request.send(data);
    }





    const showErrorMessage = (field, message) => {
        field.addClass('error');

        const error = field.siblings('small');
        if (error.length === 0) {
            field.after(`<small class="text-danger">${message}</small>`);
        } else {
            error.text(message);
        }
    };

    const hideErrorMessage = (field) => {
        field.removeClass('error');
        const error = field.siblings('small');
        if (error.length !== 0) {
            error.remove();
        }
    };

    function resetDatePickerAndTimePicker() {
        const date = $('#date');
        const time = $('#time');
        date.val('');
        time.val('');
    }


