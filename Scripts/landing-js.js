// Scroll indicator bar operating according to the scroll bar
window.addEventListener("scroll", () => {
  const indicatorBar = document.querySelector(".scroll-indicator-bar");
  const pageScroll = window.scrollY;
  const height = document.documentElement.scrollHeight - window.innerHeight;
  const scrollValue = (pageScroll / height) * 100;
  indicatorBar.style.width = scrollValue + "%";
});

// Pop-up Menu with the navigation tools
const menuBtn = document.querySelector(".nav-menu-btn");
const closeBtn = document.querySelector(".nav-close-btn");
const navigation = document.querySelector(".navigation");

menuBtn.addEventListener("click", () => {
  navigation.classList.add("active");
});

closeBtn.addEventListener("click", () => {
  navigation.classList.remove("active");
});

// Scroll animation for specified elements
const animatedElements = document.querySelectorAll('.content');
const animationObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('scroll-animation');
    } else {
      entry.target.classList.remove('scroll-animation');
    }
  });
}, { threshold: 0.15 });

animatedElements.forEach(element => {
  animationObserver.observe(element);
});


// Fade in effect for heading element
const heading = document.getElementById("typing-heading");
const logo = document.getElementById("main-logo"); 

function fadeIn() {
  heading.classList.add('active');
}

function checkVisibility() {
  const rect = heading.getBoundingClientRect();
  const windowHeight = window.innerHeight;

  if (rect.top < windowHeight / 2 && rect.bottom >= 0) {
    fadeIn();
  }
}

window.addEventListener("load", checkVisibility);
window.addEventListener("scroll", checkVisibility);

// Scroll animation for home section
const homeSection = document.querySelector('.home');

function animateHomeSection() {
  const rect = homeSection.getBoundingClientRect();
  const windowHeight = window.innerHeight;

  if (rect.top < windowHeight / 1.2 && rect.bottom >= 0) {
    homeSection.classList.add('scroll-animation');
  }
}

window.addEventListener('load', animateHomeSection);
window.addEventListener('scroll', animateHomeSection);

// Basic animatio for the Logo 
function animateLogo() {
  const rect = logo.getBoundingClientRect();
  const windowHeight = window.innerHeight;

  if (rect.top < windowHeight / 2 && rect.bottom >= 0) {
    logo.classList.add('scroll-animation');
  }
}

window.addEventListener('load', animateLogo);
window.addEventListener('scroll', animateLogo);


// Login and Register Pop-ups functions
function openPopup(popupId, focusElementId) {
  const popup = document.getElementById(popupId);
  const popupContent = popup.querySelector(".popup-content");

  popup.style.display = "block";
  popup.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
  popupContent.style.transform = "translate(-50%, -50%)";
  popupContent.style.opacity = "1";

  const focusElement = document.getElementById(focusElementId);
  focusElement.focus();
}

function closePopup(popupId) {
  const popup = document.getElementById(popupId);
  const popupContent = popup.querySelector(".popup-content");

  popup.style.backgroundColor = "rgba(0, 0, 0, 0)";
  popupContent.style.transform = "translate(-50%, -50%) scale(0.7)";
  popupContent.style.opacity = "0";

  setTimeout(() => {
    popup.style.display = "none";
  }, 300);
}

// Functions for inter-changing between the register and the login pop-up 
document.getElementById("login-link").addEventListener("click", function(event) {
  event.preventDefault();
  openPopup("login-popup", "username");
});

document.getElementById("close-login-popup").addEventListener("click", function(event) {
  event.preventDefault();
  closePopup("login-popup");
});

document.getElementById("open-login-popup").addEventListener("click", function(event) {
  event.preventDefault();
  closePopup("register-popup");
  openPopup("login-popup", "email");
});


document.getElementById("open-register-popup").addEventListener("click", function(event) {
  event.preventDefault();
  closePopup("login-popup"); // Close the login popup
  openPopup("register-popup", "email"); // Open the register popup
});

document.getElementById("open-register-popup2").addEventListener("click", function(event) {
  event.preventDefault();
  closePopup("login-popup"); // Close the login popup
  openPopup("register-popup", "email"); // Open the register popup
});

document.getElementById("close-register-popup").addEventListener("click", function(event) {
  event.preventDefault();
  closePopup("register-popup");
});

// Toggle password visibility with the eye-slash
function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const passwordToggle = document.getElementById("password-toggle");

  passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  passwordToggle.className = passwordInput.type === "password" ? "password-toggle uil uil-eye-slash" : "password-toggle uil uil-eye";
}

document.getElementById("password-toggle").addEventListener("click", function(event) {
  event.preventDefault();
  togglePasswordVisibility();
});


// Registration steps handling for the 3 steps of the register process 
const steps = document.querySelectorAll(".register-step");
for (let i = 1; i <= 3; i++) {
  const continueButton = document.getElementById(`continue-button-${i}`);

  continueButton.addEventListener("click", function() {
    steps[i - 1].style.display = "none";
    steps[i].style.display = "block";
  });

  if (i === 3) {
    const finishButton = document.getElementById("finish-button");
    finishButton.addEventListener("click", function() {
      document.getElementById("register-popup").style.display = "none";
    });
  }
}

document.getElementById("open-login-popup").addEventListener("click", function(event) {
  event.preventDefault(); 
  openPopup("login-popup", "email");
});


// Function to allow previewing the inserted image in the register field
const imageInput = document.getElementById("imageInput");
    const imagePreview = document.getElementById("image-preview");
    const selectedImage = document.getElementById("selected-image");

    imageInput.addEventListener("change", () => {
      const file = imageInput.files[0];

      if (file) {
        
        const reader = new FileReader();

        reader.onload = (e) => {
          
          selectedImage.src = e.target.result;
        
          imagePreview.style.display = "block";
        };

        reader.readAsDataURL(file);
      } else {
      
        selectedImage.src = "";
      
        imagePreview.style.display = "none";
      }
    });