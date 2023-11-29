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

var slideIndex = 0;
var posts;

document.addEventListener("DOMContentLoaded", function() {
    posts = document.getElementsByClassName("post");
    showPosts();
});

function showPosts() {
    var i;
    for (i = 0; i < posts.length; i++) {
        posts[i].style.display = "none";
    }
    if (slideIndex >= posts.length) {
        slideIndex = 0;
    }
    if (slideIndex < 0) {
        slideIndex = posts.length - 1;
    }
    posts[slideIndex].style.display = "block";
    
}

function plusSlides(n) {
    slideIndex += n;
    if (slideIndex >= posts.length) {
        slideIndex = 0;
    }
    if (slideIndex < 0) {
        slideIndex = posts.length - 1;
    }
    showPosts();
}