// Live Page JavaScript
console.log('Live page loaded');

// Check if live stream is available
const livePlayer = document.querySelector('.live-player iframe');
if (livePlayer) {
    console.log('Live stream is active');
    
    // Add live badge to header
    const liveHeader = document.querySelector('.live-header h1');
    if (liveHeader && !liveHeader.querySelector('.live-badge')) {
        const badge = document.createElement('span');
        badge.className = 'live-badge';
        badge.textContent = 'LIVE';
        badge.style.cssText = `
            display: inline-block;
            background: #ff0844;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.5em;
            margin-left: 15px;
            animation: pulse 2s infinite;
        `;
        liveHeader.appendChild(badge);
    }
}

// Auto-refresh page every 5 minutes to check for new live stream
// Uncomment if you want auto-refresh
// setTimeout(() => {
//     location.reload();
// }, 5 * 60 * 1000); // 5 minutes

// Smooth scroll for news cards
document.querySelectorAll('.news-card a').forEach(link => {
    link.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
