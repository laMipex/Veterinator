window.addEventListener("DOMContentLoaded", init);

let timeout;

function init() {


    const insertForm = document.querySelector('#insertForm');
    const updateForm = document.querySelector('#updateForm');

    if (insertForm) {
        insertForm.addEventListener('submit', function (e) {
            if (!validateForm(insertForm)) {
                e.preventDefault();
            }
        });
    }

    if (updateForm) {
        updateForm.addEventListener('submit', function (e) {
            if (!updateValidateForm(updateForm)) {
                e.preventDefault();
            }
        });
    }
    let updateValidateForm = () => {
        const updateServiceName = document.querySelector('#service_name');
        const updateServiceDescription = document.querySelector('#service_description');
        const updateServiceDuration = document.querySelector('#service_duration');


        let isValid = true;

        if (isEmpty(updateServiceName.value.trim())) {
            showErrorMessage(updateServiceName, "Service name can't be empty.");
            isValid = false;
        } else {
            hideErrorMessage(updateServiceName);
        }

        if (isEmpty(updateServiceDescription.value.trim())) {
            showErrorMessage(updateServiceDescription, "Service description can't be empty.");
            isValid = false;
        } else {
            hideErrorMessage(updateServiceDescription);
        }

        if (isEmpty(updateServiceDuration.value.trim())) {
            showErrorMessage(updateServiceDuration, 'Service duration can not be empty.');
            isValid = false;
        } else {
            hideErrorMessage(updateServiceDuration);
        }


        return isValid;
    };



    let validateForm = () => {

        const insertServiceName = document.querySelector('#name');
        const insertServiceDescription = document.querySelector('#description');
        const insertServiceDuration = document.querySelector('#duration');


        let isValid = true;

        if (isEmpty(insertServiceName.value.trim())) {
            showErrorMessage(insertServiceName, "Service name can't be empty.");
            isValid = false;
        } else {
            hideErrorMessage(insertServiceName);
        }

        if (isEmpty(insertServiceDescription.value.trim())) {
            showErrorMessage(insertServiceDescription, "Service description can't be empty.");
            isValid = false;
        } else {
            hideErrorMessage(insertServiceDescription);
        }

        if (isEmpty(insertServiceDuration.value.trim())) {
            showErrorMessage(insertServiceDuration, 'Service duration can not be empty.');
            isValid = false;
        } else {
            hideErrorMessage(insertServiceDuration);
        }







        return isValid;
    };

    const isEmpty = value => value === '';





    const showErrorMessage = (field, message) => {

        let error = field.nextElementSibling;

        // Find the <br> and <small> elements
        if (error && error.tagName === 'BR') {
            error = error.nextElementSibling;
        }

        if (error && error.tagName === 'SMALL') {
            error.classList.add('error');
            error.style.color = 'red';  // Optional: style the error message
            error.innerText = message;
        }
    }

    function hideErrorMessage(field) {
        let error = field.nextElementSibling;

        // Find the <br> and <small> elements
        if (error && error.tagName === 'BR') {
            error = error.nextElementSibling;
        }

        if (error && error.tagName === 'SMALL') {
            error.classList.remove('error');
            error.innerText = '';
        }
    }

}