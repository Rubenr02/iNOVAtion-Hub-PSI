// Dropdown Menu Function
const menuBtn = document.querySelector(".nav-menu-btn");
const closeBtn = document.querySelector(".nav-close-btn");
const navigation = document.querySelector(".navigation");

menuBtn.addEventListener("click", () => {
  navigation.classList.add("active");
});

closeBtn.addEventListener("click", () => {
  navigation.classList.remove("active");
});


// Function to allow the sliding carousel
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


// Function for the pop-up filter and search bar for the tags
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

function toggleFilterSection() {
    var customDropdown = document.querySelector(".custom-dropdown");
    customDropdown.classList.toggle("active");
    if (customDropdown.classList.contains("active")) {
        document.getElementById("tagSearch").focus();
    }
}

function selectTag(tagName) {
    // Add your logic to handle the selected tag
    console.log("Selected Tag: " + tagName);
    toggleFilterSection(); // Close the dropdown after selection (optional)
}

function filterTags() {
    var input, filter, options, i, tag;
    input = document.getElementById("tagSearch");
    filter = input.value.toUpperCase();
    options = document.getElementById("dropdownContent").getElementsByClassName("tag-option");

    for (i = 0; i < options.length; i++) {
        tag = options[i].innerText || options[i].textContent;
        if (tag.toUpperCase().indexOf(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}

function toggleFilterSection() {
    var customDropdown = document.querySelector(".custom-dropdown");
    customDropdown.classList.toggle("active");
    if (customDropdown.classList.contains("active")) {
        document.getElementById("tagSearch").focus();
    }
}

function selectTag(tagName) {
    // Add your logic to handle the selected tag
    console.log("Selected Tag: " + tagName);
    toggleFilterSection(); // Close the dropdown after selection (optional)
}

function filterTags() {
    var input, filter, options, i, tag;
    input = document.getElementById("tagSearch");
    filter = input.value.toUpperCase();
    options = document.getElementById("dropdownContent").getElementsByClassName("tag-option");

    for (i = 0; i < options.length; i++) {
        tag = options[i].innerText || options[i].textContent;
        if (tag.toUpperCase().indexOf(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}


// Modify the existing form submission logic to use AJAX
document.querySelectorAll('.upvote-button, .downvote-button').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        var form = event.target.closest('form');
        if (form) {
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_vote.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the UI with the new votescore
                    updateVotescore(formData.get('post_id'), xhr.responseText);
                } else {
                    console.error('Error updating votescore: ' + xhr.statusText);
                }
            };
            xhr.send(formData);
        }
    });
});



// Event listener to close the popup if you press on the screen
document.addEventListener('click', function (event) {
        var customDropdown = document.querySelector(".custom-dropdown");
        var filterButton = document.querySelector(".filter-button");
        
        // Check if the clicked element is outside the filter pop-up and the filter button
        if (!customDropdown.contains(event.target) && !filterButton.contains(event.target)) {
            // If so, close the filter pop-up
            customDropdown.classList.remove("active");
        }
    });

// Function to reload the page without refreshing
function selectTag(tagName) {
       // Reload the page with the selected tag as a query parameter
       window.location.href = 'iNOVAtion-html.php?tag=' + encodeURIComponent(tagName);
}  

function filterPosts() {
    var input, filter, posts, i, postTitle, postContent;
    input = document.getElementById("postSearch");
    filter = input.value.toUpperCase();
    posts = document.querySelectorAll('.post');

    for (i = 0; i < posts.length; i++) {
        postTitle = posts[i].getElementsByClassName("post-title")[0];
        postContent = posts[i].getElementsByClassName("post-content")[0];

        // You can adjust this condition based on how you want to filter the posts
        if (postTitle.innerText.toUpperCase().indexOf(filter) > -1 ||
            postContent.innerText.toUpperCase().indexOf(filter) > -1) {
            posts[i].style.display = "";
        } else {
            posts[i].style.display = "none";
        }
    }
}

