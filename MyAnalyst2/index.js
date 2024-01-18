const switchToLogin = document.querySelector('login-btn');
const switchToSignup = document.querySelector('signup-btn');

switchToLogin.addEventListener('click', () => {
    signup.style.display = 'none';
    login.style.display = 'block';
})

switchToSignup.addEventListener('click', () => {
    login.style.display = 'none';
    signup.style.display = 'block';
})