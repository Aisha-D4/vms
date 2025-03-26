const signUpButton = document.getElementById('signUpButton');
const signInButton = document.getElementById('signInButton');
const signInForm = document.getElementById('signIn');
const signUpForm = document.getElementById('signup');
const userTypeDropdown = document.getElementById('userType');
const signUpContainer = document.querySelector('#signup.container');

// Toggle between Sign Up and Sign In forms
signUpButton.addEventListener('click', function () {
    signInForm.style.display = "none";
    signUpForm.style.display = "block";
});

signInButton.addEventListener('click', function () {
    signInForm.style.display = "block";
    signUpForm.style.display = "none";
});

// Function to toggle fields based on user type selection
function toggleFields() {
    const userType = userTypeDropdown.value;
    const volunteerFields = document.getElementById('volunteerFields');
    const organizationFields = document.getElementById('organizationFields');

    if (userType === 'volunteer') {
        volunteerFields.style.display = 'block';
        organizationFields.classList.remove('show'); // Hide organization fields
        document.getElementById('fName').required = true;
        document.getElementById('lName').required = true;
        document.getElementById('organizationName').required = false;
        signUpContainer.classList.remove('organization-mode');
    } else if (userType === 'organization') {
        volunteerFields.style.display = 'none';
        organizationFields.classList.add('show'); // Show organization fields
        document.getElementById('fName').required = false;
        document.getElementById('lName').required = false;
        document.getElementById('organizationName').required = true; // Make organization name required
        signUpContainer.classList.add('organization-mode');
    }
}

// Add event listener to the userType dropdown
userTypeDropdown.addEventListener('change', toggleFields);

// Call toggleFields on page load to set initial state
document.addEventListener('DOMContentLoaded', function () {
    toggleFields();
});