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


function toggleFilterSection() {
    var customDropdown = document.querySelector(".custom-dropdown");
    customDropdown.classList.toggle("active");
    if (customDropdown.classList.contains("active")) {
        document.getElementById("tagSearch").focus();
    }
}

// Basic function that allows you to see the tags and preview the tags name(selected)
function selectTag(tagName) {
    console.log("Selected Tag: " + tagName);
    filterPostsByTag(tagName); 
    toggleFilterSection(); 
}

// Need to comment
function filterPostsByTag(selectedTag) {
    var posts = document.querySelectorAll('.post');
    
    for (var i = 0; i < posts.length; i++) {
        var postTag = posts[i].getElementsByClassName("input-tag")[0];

        if (!selectedTag || postTag.innerText.toUpperCase() === selectedTag.toUpperCase()) {
            posts[i].style.display = "";
        } else {
            posts[i].style.display = "none";
        }
    }
}

// Need to comment
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

    filterPostsByTag(filter);
}


// Event listener to close the popup if you press on the screen(so its less anoying)
document.addEventListener('click', function (event) {
        var customDropdown = document.querySelector(".custom-dropdown");
        var filterButton = document.querySelector(".filter-button");
        
        // This checks if the event pressed is outside the poopup and if so closes it
        if (!customDropdown.contains(event.target) && !filterButton.contains(event.target)) {
            customDropdown.classList.remove("active");
        }
    });

// Function to reload the page without refreshing
function selectTag(tagName) {
       window.location.href = 'iNOVAtion-html.php?tag=' + encodeURIComponent(tagName);
}  

// Function to search for the title of the posts in the page as well as the usernames (without refreshing)
function searchPosts() {
    var input, filter, posts, i, postTitle, username, postContent;
    input = document.getElementById("postSearch");
    filter = input.value.toUpperCase();
    posts = document.querySelectorAll('.post');

    for (i = 0; i < posts.length; i++) {
        postTitle = posts[i].getElementsByClassName("post-title")[0];
        postContent = posts[i].getElementsByClassName("post-content")[0];
        username = posts[i].getElementsByClassName("username")[0];

        // This helps on the search making all the irregular text readable in case is either in upper or lower text
        if (postTitle.innerText.toUpperCase().indexOf(filter) > -1 ||
            postContent.innerText.toUpperCase().indexOf(filter) > -1 ||
            username.innerText.toUpperCase().indexOf(filter) > -1) {
            posts[i].style.display = "";
        } else {
            posts[i].style.display = "none";
        }
    }
}


// Function to update the votescore in real time using ajax     
$(document).ready(function() {
  $(".vote-form button").on("click", function(e) {
    e.preventDefault();

    var container = $(this).closest(".vote-container");
    var form = container.find(".vote-form");

    // Determine whether it's an upvote or downvote button
    var isUpvote = $(this).hasClass("upvote-button");

    // Set the values accordingly
    form.find('input[name="upvote"]').val(isUpvote ? 1 : 0);
    form.find('input[name="downvote"]').val(isUpvote ? 0 : 1); 


    $.ajax({
      type: form.attr("method"),
      url: form.attr("action"),
      data: form.serialize(), // Use form.serialize() to send the form data
      dataType: "json",
      success: function(response) {
        if (response.hasOwnProperty('voteCount')) {
          // Update the vote count on success
          container.find(".post-stats").text(response.voteCount);
        } else {
          console.error('Invalid response format:', response);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("You have already voted for this post. Thank you for your collaboration!", textStatus, errorThrown);
        alert("You have already voted for this post. Thank you for your collaboration!");
      }
    });
  });
});
