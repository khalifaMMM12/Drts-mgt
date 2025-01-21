document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const togglePassword = (inputId, toggleId) => {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        
        if (!input || !toggle) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            toggle.classList.remove('fa-eye');
            toggle.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            toggle.classList.remove('fa-eye-slash');
            toggle.classList.add('fa-eye');
        }
    };

    // Add event listeners for password toggles
    const passwordToggles = [
        { toggle: 'toggleCurrentPassword', input: 'current_password' },
        { toggle: 'toggleNewPassword', input: 'new_password' },
        { toggle: 'toggleConfirmPassword', input: 'confirm_password' }
    ];

    passwordToggles.forEach(({ toggle, input }) => {
        const toggleElement = document.getElementById(toggle);
        if (toggleElement) {
            toggleElement.addEventListener('click', () => togglePassword(input, toggle));
        }
    });
});

function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.getElementById('logoutModal').classList.add('flex');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
    document.getElementById('logoutModal').classList.remove('flex');
}