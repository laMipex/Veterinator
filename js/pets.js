window.addEventListener("DOMContentLoaded", init);

function init() {
    const viewInsertPetForm = document.querySelector('#addPet');
    const insertPetForm = document.querySelector('#insertPetForm');

    if (viewInsertPetForm !== null) {
        viewInsertPetForm.addEventListener('click', function (e) {
            if (insertPetForm.style.display !== "block") {
                insertPetForm.style.display = "block";
                viewInsertPetForm.textContent = 'Hide form.';
            } else {
                insertPetForm.style.display = "none";
                viewInsertPetForm.textContent = 'Add pet';
            }
            e.preventDefault();
        });
    }

    insertPetForm.addEventListener('submit', function (e) {
        e.preventDefault();
        sendData();
    });

    function sendData() {
        const form = document.querySelector('#insertPetForm');
        const formData = new FormData(form);

        let isValid = true;

        if (isEmpty(formData.get('pet_name').trim())) {
            showErrorMessage(document.querySelector('#pet_name'), 'Pet name cannot be empty.');
            isValid = false;
        }

        /*if (isEmpty(formData.get('age').trim())) {
            showErrorMessage(document.querySelector('#age'), 'Pet age cannot be empty.');
            isValid = false;
        }*/
        if (isEmpty(formData.get('species').trim())) {
            showErrorMessage(document.querySelector('#species'), 'Species cannot be empty.');
            isValid = false;
        }

        if (isValid) {
            const request = new XMLHttpRequest();
            const result = document.querySelector('#result');
            request.open("POST", "insert_pet.php", true);

            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    const inputs = document.querySelectorAll('.insertPetForm input');
                    let jsonData = JSON.parse(request.responseText);

                    alert(jsonData.message);

                    inputs.forEach((element) => {
                        element.value = '';
                    });

                    result.innerHTML = jsonData.message;
                    setTimeout(function () {
                        result.innerHTML = "";
                    }, 2000);
                }
            };

            request.send(formData);
        }

        const petName = document.querySelector('#pet_name');
        const species = document.querySelector('#species');

        petName.addEventListener('input', function () {
            hideErrorMessage(petName);
        });

        petAge.addEventListener('input', function () {
            hideErrorMessage(petAge);
        });
    }

    const showErrorMessage = (field, message) => {
        const insertPetForm = field.parentElement;
        insertPetForm.classList.add('error');

        const error = insertPetForm.querySelector('small');
        error.innerText = message;
    };

    const hideErrorMessage = (field) => {
        const insertPetForm = field.parentElement;
        insertPetForm.classList.remove('error');

        const error = insertPetForm.querySelector('small');
        error.innerText = '';
    };

    const isEmpty = value => value === '';
}

