let loginForm = document.querySelector('.login');
let signupForm = document.querySelector('.signup');

// hide the signup form by default
signupForm.style.display = 'none';

// add event listeners to show/hide the appropriate form based on user interaction
const loginLink = document.querySelector('#login-link');
const signupLink = document.querySelector('#signup-link');

loginLink.addEventListener('click', () => {
    console.log("clicked");
  loginForm.style.display = 'block';
  signupForm.style.display = 'none';
});

signupLink.addEventListener('click', () => {
    console.log("clicked");
  loginForm.style.display = 'none';
  signupForm.style.display = 'block';
});

const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');

if (error === 'true') {
  loginForm.style.display = 'none';
  signupForm.style.display = 'block';
} else {
  loginForm.style.display = 'block';
  signupForm.style.display = 'none';
}