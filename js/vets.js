window.addEventListener("DOMContentLoaded", init);

function init() {
    const insertVet = document.querySelector('#insert');
    const insertVetContainer = document.querySelector('.formField');
    const sendMailButton = document.querySelector('#sendMailButton');
    let banButtons = document.querySelectorAll('.bann_vet_btn');

    if (insertVet !== null) {
        insertVet.addEventListener('click', function (e) {
            if (insertVetContainer.style.display !== "block") {
                insertVetContainer.style.display = "block";
                insertVet.textContent = 'Hide form.';
            } else {
                insertVetContainer.style.display = "none";
                insertVet.textContent = 'Insert Vet';
            }
            e.preventDefault();
        });
    }

    if (sendMailButton !== null) {
        sendMailButton.addEventListener('click', function (e) {
            e.preventDefault();
            sendData();
        });
    }

    if (banButtons != null) {
        banButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                banVet(this);
            });
        });
    }

    var modals = document.querySelectorAll('.modal');
    modals.forEach(function (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                backdrop.remove();
            });
            document.body.classList.remove('modal-open');
            document.body.style = '';
        });
    });

    document.querySelectorAll('.edit_vet_btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Prikazivanje modalnog prozora
            var modalElement = document.querySelector('.editContainer');

            if (modalElement) {
                var modal = new bootstrap.Modal(modalElement);
                modal.show();

                var vet = this.closest('.card');
                var id = vet.dataset.id;

                // Popunjavanje forme sa podacima o veterinaru
                var vetFnameInput = document.getElementById('vet_fname');
                var vetLnameInput = document.getElementById('vet_lname');
                var editVetEmailInput = document.getElementById('edit_vet_email');
                var editVetPhoneInput = document.getElementById('edit_vet_phone');
                var editCityInput = document.getElementById('edit_city');
                var editSpecializationInput = document.getElementById('edit_specialization');
                var editWorkStartInput = document.getElementById('edit_work_start');
                var editWorkEndInput = document.getElementById('edit_work_end');
                var currentPhotoEdit = document.getElementById('current_photo_edit');
                var editVetServices = document.getElementById('edit_vet_services');
                var vetServices = document.getElementById('vet_services');

                if (vetFnameInput && vetLnameInput && editVetEmailInput && editVetPhoneInput && editCityInput && editWorkStartInput && editWorkEndInput) {
                    // Postavljanje vrednosti na polja forme
                    vetFnameInput.value = vet.querySelector('.card-title').textContent.trim().split(' ')[0];
                    vetLnameInput.value = vet.querySelector('.card-title').textContent.trim().split(' ')[1];
                    editVetEmailInput.value = vet.querySelector('.vet_email').textContent;
                    editVetPhoneInput.value = vet.querySelector('.vet_phone').textContent;
                    editCityInput.value = vet.querySelector('.city').textContent;
                    editSpecializationInput.value = vet.querySelector('.vet_specialization').textContent;
                    editWorkStartInput.value = vet.querySelector('.work_time').textContent.split(' - ')[0];
                    editWorkEndInput.value = vet.querySelector('.work_time').textContent.split(' - ')[1];
                    currentPhotoEdit.src = vet.querySelector('.card-img-top').src;

                    // Popunjavanje select polja sa uslugama
                    var originalSelect = vet.querySelector('.vet_services');
                    editVetServices.innerHTML = ''; // Resetovanje opcija u modalnom prozoru

                    // Kreiranje opcije za -1 i dodavanje na početak select polja
                    var defaultOption = document.createElement('option');
                    defaultOption.value = '-1';
                    defaultOption.textContent = 'Delete vet service'; // Tekst koji želite prikazati za opciju -1
                    editVetServices.appendChild(defaultOption);

                    // Dodavanje ostalih opcija iz originalnog select polja nakon opcije za -1
                    originalSelect.querySelectorAll('option').forEach(function (option) {
                        var newOption = document.createElement('option');
                        newOption.value = option.value;
                        newOption.textContent = option.textContent;
                        editVetServices.appendChild(newOption);
                    });

                    // Isključivanje usluga koje su već izabrane
                    var selectedServices = Array.from(editVetServices.options).map(option => option.value);
                    Array.from(vetServices.options).forEach(function (option) {
                        if (selectedServices.includes(option.value)) {
                            option.style.display = 'none'; // Sakrij opciju
                        } else {
                            option.style.display = 'block'; // Prikaži opciju
                        }
                    });

                    // Postavljanje skrivenog polja sa ID veteriara
                    document.querySelector('#editForm .id_vet').value = id;

                } else {
                    console.error('Neki od elemenata nisu pronađeni!');
                }
            } else {
                console.error('Modal element nije pronađen!');
            }
        });
    });

    document.getElementById('save-button').addEventListener('click', function () {
        let formData = new FormData(document.getElementById('editForm'));
        let idVet = document.querySelector('#editForm .id_vet').value;
        formData.append('id_vet', idVet);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'parts/updateVet.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);

                // Zatvaranje modalnog prozora
                var modalElement = document.querySelector('.editContainer');
                var modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();

                // Osvježavanje prikaza kartica
                updateVetCard(idVet, formData);
            } else {
                console.error('Error:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Request failed');
        };

        xhr.send(formData);
    });


    function updateVetCard(idVet, formData) {
        let card = document.querySelector(`.card[data-id="${idVet}"]`);
        if (card) {
            // Ažuriranje podataka na kartici
            card.querySelector('.card-title').textContent = `${formData.get('vet_fname')} ${formData.get('vet_lname')}`;
            card.querySelector('.vet_email').textContent = formData.get('vet_email');
            card.querySelector('.vet_phone').textContent = formData.get('vet_phone');
            card.querySelector('.city').textContent = formData.get('city');
            card.querySelector('.vet_specialization').textContent = formData.get('specialization');
            card.querySelector('.work_time').textContent = `${formData.get('work_start')} - ${formData.get('work_end')}`;

            // Ažuriranje slike ako je nova slika uploadovana
            if (formData.get('file') && formData.get('file').size > 0) {
                let newImageUrl = URL.createObjectURL(formData.get('file'));
                card.querySelector('.card-img-top').src = newImageUrl;
            }
            else{
                card.querySelector('.card-img-top').src ;
            }


            // Ažuriranje select polja za usluge
            let vetServicesSelect = card.querySelector('.vet_services');
            updateSelectOptions(vetServicesSelect, formData.getAll('vet_services[]'));

            // Sakrivanje već izabranih usluga
            let editVetServices = card.querySelector('.edit_vet_services');
            if (editVetServices) {
                var selectedServices = Array.from(editVetServices.options).map(option => option.value);
                Array.from(vetServicesSelect.options).forEach(function (option) {
                    if (selectedServices.includes(option.value)) {
                        option.style.display = 'none'; // Sakrij opciju
                    } else {
                        option.style.display = 'block'; // Prikaži opciju
                    }
                });
            }

            // Uklanjanje izabranih usluga iz select polja
            let editVetServices2 = formData.get('edit_vet_services');
            if (editVetServices2 !== null) {
                let selectedServices = [editVetServices2.toString()]; // Pretvara u niz ID-jeva

                // Uklanja izabrane opcije iz select polja
                removeSelectedOptions(vetServicesSelect, selectedServices);
            }


        }
    }

    function updateSelectOptions(selectElement, newServicesArray) {
        // Kreiraj niz postojećih vrednosti u select polju
        let existingOptions = Array.from(selectElement.options).map(option => option.value);

        // Dodaj nove opcije ako već ne postoje
        newServicesArray.forEach(service => {
            if (!existingOptions.includes(service)) {
                let newOption = document.createElement('option');
                newOption.value = service;
                newOption.textContent = getServiceNameById(service); // Pretpostavljamo da postoji funkcija koja vraća ime usluge na osnovu ID-a
                selectElement.appendChild(newOption);
            }
        });
    }

    function removeSelectedOptions(selectElement, selectedServicesArray) {
        selectedServicesArray.forEach(service => {
            let optionToRemove = Array.from(selectElement.options).find(option => option.value === service.toString());
            if (optionToRemove) {
                selectElement.removeChild(optionToRemove);
            }
        });
    }
// Pretpostavljena funkcija za dobijanje imena usluge na osnovu ID-a
    function getServiceNameById(serviceId) {
        const services = {
            1: 'Microchipping',
            2: 'General Check-Up',
            4: 'Emergency Care',
            5: 'Neuter Surgery',
            13: 'Vaccinations',
            14: 'Nutritional Counseling',
            15: 'Dental Cleaning',
            23: 'Parasite Control',
            24: 'Behavioral Consultation'
            // Dodaj ostale usluge po potrebi
        };
        return services[serviceId] || 'Unknown Service';
    }



    function banVet(button) {
        let card = button.closest('.card');
        let id_vet = card.querySelector('.id_vet').value;
        const result = document.querySelector('#result');

        let isValid = true;

        if (isValid) {
            const request = new XMLHttpRequest();
            request.open("POST", "parts/ban_vet.php", true);
            request.setRequestHeader("Content-Type", "application/json");

            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    let jsonData = JSON.parse(request.responseText);

                    alert(jsonData.message);

                    result.innerHTML = jsonData.message;
                    setTimeout(function () {
                        result.innerHTML = "";
                    }, 2000);

                    card.parentElement.remove();
                }
            };

            let data = JSON.stringify({ "id_vet": id_vet });
            request.send(data);
        }
    }

    function sendData() {
        const emailVet = document.querySelector('#mailVet');
        const result = document.querySelector('#result');

        let isValid = true;

        if (isEmpty(emailVet.value.trim())) {
            showErrorMessage(emailVet, 'Email cannot be empty.');
            isValid = false;
        } else {
            hideErrorMessage(emailVet);
        }

        if (isValid) {
            const request = new XMLHttpRequest();
            request.open("POST", "parts/sendMailVet.php", true);
            request.setRequestHeader("Content-Type", "application/json");

            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    let jsonData = JSON.parse(request.responseText);

                    alert(jsonData.message);

                    if (jsonData.status === 'OK') {
                        // Hide the form if insertion is successful
                        insertVetContainer.style.display = "none";
                        insertVet.textContent = 'Insert Vet';
                    }

                    emailVet.value = '';
                    result.innerHTML = jsonData.message;
                    setTimeout(function () {
                        result.innerHTML = "";
                    }, 2000);
                }
            };

            let data = JSON.stringify({ "vet_email": emailVet.value });
            request.send(data);
        }

        emailVet.addEventListener('input', function () {
            hideErrorMessage(emailVet);
        });
    }

    const showErrorMessage = (field, message) => {
        const insertVetContainer = field.parentElement;
        insertVetContainer.classList.add('error');

        const error = insertVetContainer.querySelector('small');
        error.innerText = message;
    };

    const hideErrorMessage = (field) => {
        const insertVetContainer = field.parentElement;
        insertVetContainer.classList.remove('error');

        const error = insertVetContainer.querySelector('small');
        error.innerText = '';
    };

    const isEmpty = value => value === '';
}
