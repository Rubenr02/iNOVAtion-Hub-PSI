// Dropdown Menu
const menuBtn = document.querySelector(".nav-menu-btn");
const closeBtn = document.querySelector(".nav-close-btn");
const navigation = document.querySelector(".navigation");

menuBtn.addEventListener("click", () => {
  navigation.classList.add("active");
});

closeBtn.addEventListener("click", () => {
  navigation.classList.remove("active");
});


// Select the carousel container and its slides
const carousel = document.querySelector('.carousel');
const slides = carousel.querySelectorAll('.slide');

// Set the initial slide and current index
let currentIndex = 0;
showSlide(currentIndex);

// Function to display the current slide
function showSlide(index) {
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.style.display = 'block';
        } else {
            slide.style.display = 'none';
        }
    });
}

// Function to move to the next slide
function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}

// Function to move to the previous slide
function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
}

// Set up event listeners for next and previous buttons
const nextButton = document.getElementById('next-button');
const prevButton = document.getElementById('prev-button');

nextButton.addEventListener('click', nextSlide);
prevButton.addEventListener('click', prevSlide);



// Voting system function