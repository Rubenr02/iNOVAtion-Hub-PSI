// Scroll indicator bar
window.addEventListener("scroll", () => {
  const indicatorBar = document.querySelector(".scroll-indicator-bar");
  const pageScroll = window.scrollY;
  const height = document.documentElement.scrollHeight - window.innerHeight;
  const scrollValue = (pageScroll / height) * 100;
  indicatorBar.style.width = scrollValue + "%";
});

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
const logo = document.getElementById("main-logo"); // Add reference to the new logo image

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

// Add a similar function for the new logo
function animateLogo() {
  const rect = logo.getBoundingClientRect();
  const windowHeight = window.innerHeight;

  if (rect.top < windowHeight / 2 && rect.bottom >= 0) {
    logo.classList.add('scroll-animation');
  }
}

window.addEventListener('load', animateLogo);
window.addEventListener('scroll', animateLogo);


// Login and Register Pop-up functions
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
  closePopup("register-popup"); // Close the login popup
  openPopup("login-popup", "email"); // Open the register popup
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

// Toggle password visibility
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



// Password match validation in registration form
document.getElementById("register-form").addEventListener("submit", function(event) {
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm-password");
  const errorText = document.getElementById("password-error");

  if (passwordInput.value !== confirmPasswordInput.value) {
    errorText.textContent = "Passwords do not match.";
    event.preventDefault(); // Prevent form submission
  } else {
    errorText.textContent = ""; // Clear the error message if passwords match
  }
});

// Registration steps handling
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
  event.preventDefault(); // Prevent the default link behavior (e.g., navigating to a new page).
  openPopup("login-popup", "email");
});


// Preview Image in register form
document.getElementById('picture').addEventListener('change', function () {
    const selectedImage = document.getElementById('selected-image');
    const imagePreview = document.getElementById('image-preview');

    const file = this.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            selectedImage.src = e.target.result;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    }
});

document.getElementById('finish-button').addEventListener('click', function () {

});
