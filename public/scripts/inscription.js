$(document).ready(() => {

    $('.txtTelephone').mask('000-000-0000', { placeholder: '___-___-____' });
    $('.txtCodePostal').mask('ABA BAB', {
        placeholder: 'A1A 1A1',
        translation: {
            A: { pattern: /[a-zA-Z]/ },
            B: { pattern: /[0-9]/ },
        }
    });

    // Prend les ID des inputs des passwords et enleve le coller
    document.getElementById('inscription_form_password_first').onpaste = e => e.preventDefault();
    document.getElementById('inscription_form_password_second').onpaste = e => e.preventDefault();


    $('.txtCodePostal').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    const inscriptionForm = registrationForm = document.querySelectorAll('.besoin-validation-inscription');

    addValidationToForm(inscriptionForm);

});

function addValidationToForm(forms) {
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        });
}
