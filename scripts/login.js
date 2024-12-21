document.addEventListener('DOMContentLoaded', function() {
    const errorDiv = document.getElementById('errorDiv');
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
    
        if (errorDiv) {
            setTimeout(function() {
                errorDiv.style.transition = 'opacity 0.5s';
                errorDiv.style.opacity = '0';
                setTimeout(() => {
                    errorDiv.remove();
                }, 500);
            }, 3000);
        }
    });
});