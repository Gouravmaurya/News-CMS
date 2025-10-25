// State Page JavaScript
console.log('State page loaded');

// Smooth scroll to top when clicking pagination
document.querySelectorAll('.pagination .page-link').forEach(link => {
    link.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

// Add loading animation for images
document.querySelectorAll('.article-card img').forEach(img => {
    img.addEventListener('load', function() {
        this.style.opacity = '1';
    });
    img.style.opacity = '0';
    img.style.transition = 'opacity 0.3s';
});
