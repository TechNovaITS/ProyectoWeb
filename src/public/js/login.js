document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('hiddenpassword');
    const passwordInput = document.getElementById('password');

    toggleButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
});