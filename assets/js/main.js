let topNews = document.querySelector('.topNews')

let header = document.querySelector('.header')
let toggleMenu = document.querySelector('.bar')
let mainMenu = document.querySelector('.main-nav ul')

const toggle = (e) => {
    toggleMenu.classList.toggle('active')
    mainMenu.classList.toggle('activeMenu')
}

if (toggleMenu) {
    toggleMenu.addEventListener('click', toggle)
}

// Hero Slider
let slideIndex = 0;
let slides = document.querySelectorAll('.slide');
let dots = document.querySelectorAll('.dot');

function showSlide(n) {
    if (n >= slides.length) { slideIndex = 0 }
    if (n < 0) { slideIndex = slides.length - 1 }

    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
}

function changeSlide(n) {
    slideIndex += n;
    showSlide(slideIndex);
}

function currentSlide(n) {
    slideIndex = n;
    showSlide(slideIndex);
}

// Auto slide every 5 seconds
setInterval(() => {
    slideIndex++;
    showSlide(slideIndex);
}, 5000);

showSlide(slideIndex);



let topHeader = document.querySelector('.topHeader')

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('sticky')
        topHeader.classList.add('hidden')
    }
    else {
        header.classList.remove('sticky')
        topHeader.classList.remove('hidden')
    }
})







// Load and display top headlines from JSON
const add_topNews = (data) => {
    let html = ''
    let title = ''
    let content = ''
    data.forEach((element) => {
        if (element.title.length < 100) {
            title = element.title
        }
        else {
            title = element.title.slice(0, 100) + "..."
        }

        if (element.content.length < 150) {
            content = element.content
        }
        else {
            content = element.content.slice(0, 150) + "..."
        }

        html += `<div class="news">
                    <div class="img">
                        <img src=${element.image} alt="image">
                    </div>
                    <div class="text">
                        <div class="news-header">
                            <div class="title">
                                <p>${title}</p>
                            </div>
                            <div class="date">
                                <p>${element.date}</p>
                            </div>
                        </div>
                        <div class="description">
                            <p>${content}</p>
                        </div>
                    </div>
                </div>`
    })
    topNews.innerHTML = html
}

// Load N9 India posts from topheadline.json
fetch('topheadline.json')
    .then(response => response.json())
    .then(data => {
        add_topNews(data.n9india_posts)
    })
    .catch(error => {
        console.error('Error loading top headlines:', error)
    })