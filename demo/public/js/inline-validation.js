/**
 * Real-time Inline Form Validation
 * Attached to forms across registration, login, contact, and job-posting.
 */

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form[novalidate]');

    forms.forEach(function (form) {
        const inputs = form.querySelectorAll('input, select, textarea');

        // Validation logic for a single input
        const validateInput = (input) => {
            const feedbackElement = input.parentElement.querySelector('.invalid-feedback') || 
                                    input.closest('.mb-3').querySelector('.invalid-feedback');
            
            if (!feedbackElement) return;

            let isValid = true;
            let errorMessage = '';

            // Native HTML5 validation
            if (!input.checkValidity()) {
                isValid = false;
                if (input.validity.valueMissing) {
                    errorMessage = 'This field is required.';
                } else if (input.validity.typeMismatch) {
                    if (input.type === 'email') errorMessage = 'Please enter a valid email address.';
                    else errorMessage = 'Invalid format.';
                } else if (input.validity.tooShort) {
                    errorMessage = `Must be at least ${input.minLength} characters.`;
                } else {
                    errorMessage = input.validationMessage;
                }
            }

            // Custom Password Match Validation for Register
            if (input.id === 'password_confirm' || input.name === 'password_confirm') {
                const passwordInput = form.querySelector('input[name="password"]');
                if (passwordInput && input.value !== passwordInput.value) {
                    isValid = false;
                    errorMessage = 'Passwords do not match.';
                }
            }

            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedbackElement.textContent = '';
                feedbackElement.style.display = 'none';
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                feedbackElement.textContent = errorMessage;
                feedbackElement.style.display = 'block';
            }

            return isValid;
        };

        // Real-time validation on blur and keyup
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateInput(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid')) {
                    validateInput(input);
                }
            });
        });

        // Form submit override
        form.addEventListener('submit', function (e) {
            let isFormValid = true;
            inputs.forEach(input => {
                if (!validateInput(input)) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                e.preventDefault();
                e.stopPropagation();
                // Focus first invalid input
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
            }
        }, false);
    });
});
