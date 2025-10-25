// Article Page JavaScript
console.log('Article page loaded');

// Copy article URL to clipboard
function copyArticleURL() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Article URL copied to clipboard!');
    });
}

// Add copy URL button if needed
const shareSection = document.querySelector('.article-share .share-buttons');
if (shareSection) {
    const copyBtn = document.createElement('button');
    copyBtn.className = 'share-btn';
    copyBtn.style.background = '#6c757d';
    copyBtn.innerHTML = '<i class="fa-solid fa-copy"></i> Copy Link';
    copyBtn.onclick = copyArticleURL;
    shareSection.appendChild(copyBtn);
}

// Smooth scroll for related articles
document.querySelectorAll('.related-card a').forEach(link => {
    link.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

// Add reading progress bar
const progressBar = document.createElement('div');
progressBar.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    width: 0%;
    z-index: 9999;
    transition: width 0.1s;
`;
document.body.appendChild(progressBar);

window.addEventListener('scroll', () => {
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
    progressBar.style.width = scrollPercent + '%';
});
