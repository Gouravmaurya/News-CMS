// 404 Page JavaScript
console.log('404 page loaded');

// Auto-focus search input
const searchInput = document.querySelector('.search-box input');
if (searchInput) {
    searchInput.focus();
}

// Add animation to error icon
const errorIcon = document.querySelector('.error-icon i');
if (errorIcon) {
    setInterval(() => {
        errorIcon.style.transform = 'rotate(10deg)';
        setTimeout(() => {
            errorIcon.style.transform = 'rotate(-10deg)';
            setTimeout(() => {
                errorIcon.style.transform = 'rotate(0deg)';
            }, 200);
        }, 200);
    }, 3000);
}

// Track 404 errors (optional - for analytics)
console.log('404 Error:', window.location.href);
