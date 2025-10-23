document.addEventListener('DOMContentLoaded', function() {
    const loginBtn = document.getElementById('loginBtn');
    const signupBtn = document.getElementById('signupBtn');
    const loginModal = document.getElementById('loginModal');
    const signupModal = document.getElementById('signupModal');
    const successModal = document.getElementById('successModal');
    const closeLogin = document.querySelector('#loginModal .close');
    const closeSignup = document.querySelector('#signupModal .close-signup');
    const closeSuccess = document.querySelector('#successModal .close-success');

    function openModal(modal) {
        modal.style.display = 'block';
        if (modal === signupModal && typeof grecaptcha !== 'undefined' && document.querySelector('.g-recaptcha:empty')) {
            grecaptcha.render(document.querySelector('.g-recaptcha'), {
                'sitekey': '6LeBJUkrAAAAAGV981rOb-JGnuQ0kippVZvziSlq'
            });
        }
    }

    function closeModal(modal) {
        modal.style.display = 'none';
        if (modal === signupModal && typeof grecaptcha !== 'undefined') {
            grecaptcha.reset();
        }
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', () => openModal(loginModal));
    }
    if (closeLogin) {
        closeLogin.addEventListener('click', () => closeModal(loginModal));
    }

    if (signupBtn) {
        signupBtn.addEventListener('click', () => {
            openModal(signupModal);
        });
    }
    if (closeSignup) {
        closeSignup.addEventListener('click', () => closeModal(signupModal));
    }

    if (closeSuccess) {
        closeSuccess.addEventListener('click', () => closeModal(successModal));
    }

    window.addEventListener('click', function(event) {
        if (event.target == loginModal) {
            closeModal(loginModal);
        }
        if (event.target == signupModal) {
            closeModal(signupModal);
        }
        if (event.target == successModal) {
            closeModal(successModal);
        }
    });

    const signupForm = document.querySelector('#signupModal .modal-form');
    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            const recaptchaResponse = grecaptcha.getResponse();

            if (recaptchaResponse.length === 0) {
                alert('Please complete the reCAPTCHA verification.');
                event.preventDefault();
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('registered') === 'success') {
        openModal(successModal);
        window.history.replaceState(null, null, window.location.pathname);
    }
});