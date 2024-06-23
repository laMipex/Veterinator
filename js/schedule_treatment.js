window.addEventListener("DOMContentLoaded", init);


function init() {
    let selectService = document.querySelector('#service');

    let scheduleTreatment = document.querySelector('#schedule');



    selectService.addEventListener('change', function() {
        sendData();
        resetDatePickerAndTimePicker();

    });

    scheduleTreatment.addEventListener('click',AddReservation);


    function AddReservation() {
        const service = document.querySelector('#service');
        const vet = document.querySelector('#vet');
        const date = document.querySelector('#date');
        const time = document.querySelector('#time');
        const price = document.querySelector('#service_price');

        let isValid = true;

        if(service.value == -1){
            showErrorMessage(service,"You must select service!");
            isValid = false;
        } else{
            hideErrorMessage(service);
        }

        if(vet.value == -1){
            showErrorMessage(vet,"You must select vet!");
            isValid = false;
        } else{
            hideErrorMessage(vet);
        }
        if(date.value == "")
        {
            showErrorMessage(date,"You must select date!");
            isValid = false;
        } else{
            hideErrorMessage(date);
        }
        if(time.value == "")
        {
            showErrorMessage(time,"You must select time!");
            isValid = false;
        } else{
            hideErrorMessage(time);
        }


        if (isValid) {
            let request = new XMLHttpRequest();
            const result = document.querySelector('#result');
            result.innerHTML = '<img src="photos/index_photos/ajax_loader.gif" alt="loading">';
            request.open("POST", "parts/insert_reservation.php", true);
            request.setRequestHeader("Content-Type", "application/json");
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    const selects = document.querySelectorAll('.formField select');
                    const inputs = document.querySelectorAll('.formField input');


                    let jsonData = JSON.parse(request.response);



                    alert(jsonData.message);

                    selects.forEach((element) => {    //ovo brise vrednosti za sve izabrane
                            element.value = '';
                        }
                    );

                    inputs.forEach((element) => {    //ovo brise sve izabrane
                            element.value = '';
                        }
                    );

                    result.innerHTML = jsonData.message;
                    setTimeout(function () {
                        result.innerHTML = "";
                    }, 2000);

                }
            };

            let data = JSON.stringify({"id_service": service.value, "id_vet": vet.value , "reservation_date": date.value,"reservation_time": time.value,"treatment_price": price.innerText});
            request.send(data);
        }
        date.addEventListener('click', function() {
            hideErrorMessage(date);

        });
        time.addEventListener('change', function() {
            hideErrorMessage(time);

        });

        service.addEventListener('change', function() {
            if (service.value != -1) {
                hideErrorMessage(service);
            }
        });
        vet.addEventListener('change', function() {
            if (vet.value != -1) {
                hideErrorMessage(vet);
            }
        });
    }




    function sendData() {
        let isValid = true;

        if (isValid) {
            let request = new XMLHttpRequest();
            const vetDropdown = document.querySelector('#vet');
            const servicePrice = document.querySelector('#service_price');



            request.open("POST", "parts/vet_dropdown.php", true);
            request.setRequestHeader("Content-Type", "application/json");

            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    let response = JSON.parse(request.responseText);


                    // Clear the previous options
                    vetDropdown.innerHTML = '<option value="-1">- Choose a vet -</option>';


                    if (response.status === "success") {
                        response.vets.forEach(function (vet) {
                            let option = document.createElement('option');
                            option.value = vet.id_vet;
                            option.textContent = vet.vet_fname + ' ' + vet.vet_lname;
                            vetDropdown.appendChild(option);
                        });

                        servicePrice.textContent = response.price;
                    } else {
                        console.error(response.message);
                    }
                }
            };

            let data = JSON.stringify({"service": selectService.value});
            request.send(data);
        }






        $('#vet').change(function() {
            resetDatePickerAndTimePicker();
            let vet_id = $(this).val();


            if (vet_id != '-1') {
                $.ajax({
                    url: 'parts/getVet_workTime.php',
                    type: 'POST',
                    data: { vet_id: vet_id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {


                            var startDate = new Date();
                            startDate.setDate(startDate.getDate() + 1);
                            var endDate = new Date();
                            endDate.setDate(endDate.getDate() + 31);

                            $('.datepicker').datepicker({
                                startDate: startDate,
                                endDate: endDate,
                                daysOfWeekDisabled: [0, 6],
                                format: 'yyyy-mm-dd',
                                autoclose: true,
                                todayHighlight: true
                            }).on('changeDate', function(e) {
                                $(this).datepicker('hide');
                            });


                            workStart = response.work_start.substring(0, 5);
                            workEnd = response.work_end.substring(0, 5);

                            $('#time').timepicker('option');
                            $('#time').timepicker({
                                timeFormat: 'H:i',
                                interval: 30,
                                minTime: workStart,
                                maxTime: workEnd,
                                dynamic: true,
                                dropdown: true,
                                scrollbar: true
                            });
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error retrieving work time');
                    }
                });



                $('#date').change(function() {

                    $.ajax({
                        url: 'parts/getReservations.php',
                        type: 'POST',
                        data: JSON.stringify({
                            vet_id: vet_id,
                            reservation_date: $('#date').val()
                        }),
                        contentType: 'application/json',
                        dataType: 'json',
                        success: function(response) {
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

                                // Loop through reservedTimes and push corresponding intervals into disableTimeRanges
                                for (let i = 0; i < reservedTimes.length; i++) {
                                    let time = reservedTimes[i];
                                    let duration = durationTimes[i];

                                    let hours = parseInt(time.split(':')[0]);
                                    let minutes = parseInt(time.split(':')[1]);
                                    let hoursDur = parseInt(duration.split(':')[0]);
                                    let minutesDur = parseInt(duration.split(':')[1]);

                                    // Izračunaj krajnje vreme
                                    let endTime = new Date();
                                    endTime.setHours(hours);
                                    endTime.setMinutes(minutes + minutesDur);
                                    endTime.setSeconds(0);

                                    // Dodaj sate trajanja ako je potrebno
                                    if (hoursDur > 0) {
                                        endTime.setHours(endTime.getHours() + hoursDur);
                                    }

                                    // Formatiraj krajnje vreme kao HH:mm
                                    let formattedEndTime = formatTime(endTime);

                                    disableTimeRanges.push([time, formattedEndTime]);
                                }



                                /*let reservedTimes = [];
                                let durationTimes = [];
                                for (let i = 0; i < response.reservations.length; i++) {
                                    let time = response.reservations[i].reservation_time.substring(0, 5);
                                    let duration =response.reservations[i].service_duration.substring(0, 5);
                                    reservedTimes.push(time);
                                    durationTimes.push(duration);
                                }


                                let disableTimeRanges = [];


                                // Loop through reservedTimes and push corresponding intervals into disableTimeRanges
                                for (let i = 0; i < reservedTimes.length; i++) {
                                    let time = reservedTimes[i];
                                    let duration = durationTimes[i];
                                    let [hours, minutes] = time.split(':');
                                    let [hoursDur, minutesDur] = duration.split(':');
                                    let finalDuration = `${parseInt(minutes)}`+ `${parseInt(minutesDur)}`;
                                    let minutesDura = parseInt(duration.split(':')[1]);
                                    let endTime = `${hours}:${minutes} + minutesDura`;

                                    disableTimeRanges.push([time, endTime]);
                                }*/


                                // Initialize or update timepicker with disableTimeRanges
                                $('#time').timepicker('option', {
                                    disableTimeRanges: disableTimeRanges
                                });
                            }
                            function formatTime(date) {
                                let hours = date.getHours().toString().padStart(2, '0');
                                let minutes = date.getMinutes().toString().padStart(2, '0');
                                return `${hours}:${minutes}`;
                            }
                        },
                    });
                });


            } else {
                resetDatePickerAndTimePicker();
            }
        });




    }


    function resetDatePickerAndTimePicker() {
        const date = document.querySelector('#date');
        const time = document.querySelector('#time');
        time.value = '';
        date.value = '';
    }


}

const showErrorMessage = (field, message) => {
    const formField = field.parentElement;
    formField.classList.add('error');

    const error = formField.querySelector('small');
    error.innerText = message;
};

const hideErrorMessage = (field) => {
    const formField = field.parentElement;

    formField.classList.remove('error');

    const error = formField.querySelector('small');
    error.innerText = '';
}







