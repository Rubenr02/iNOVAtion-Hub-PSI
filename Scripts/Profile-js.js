// Profile-js.js

document.addEventListener("DOMContentLoaded", function () {
    // Fetch user data from the database (replace this with your actual data fetching logic)
    const userData = {
      profilePicture: "Images/persona.avif",
      username: "Persona",
      description: "Nova IMS Student",
      posts: [
        { title: "Post 1", content: "This is the content of Post 1." },
        { title: "Post 2", content: "This is the content of Post 1." },
        { title: "Post 1", content: "This is the content of Post 1." }
      ],
    };
  
    // Update profile information
    document.querySelector(".profile-picture").src = userData.profilePicture;
    document.querySelector(".username").textContent = userData.username;
    document.querySelector(".description").textContent = userData.description;
  
    // Display published posts
    const postsContainer = document.getElementById("posts-container");
    const noPostsMessage = document.getElementById("no-posts-message");
  
    if (userData.posts.length > 0) {
      userData.posts.forEach((post) => {
        const postElement = createPostElement(post.title, post.content);
        postsContainer.appendChild(postElement);
      });
    } else {
      noPostsMessage.style.display = "block";
    }
  });

  
  // Helper function to create a post element
  function createPostElement(title, content) {
    const postElement = document.createElement("div");
    postElement.classList.add("post");
  
    // Create the HTML structure for a post
    postElement.innerHTML = `
    <div class="post">
    <div class="post-header">
      <div class="user-info">
        <img src="Images/persona.avif" alt="User Profile Picture">
        <span class="username">User_Persona</span>
      </div>
      <h2 class="post-title">Campus Campolide Gym</h2>
      <div class="post-tag">
        <span class="input-tag">Exercise</span>
        <span class="input-type">Problem</span>
      </div>
    </div>
    <div class="post-content">
      <p>
        As a student from NOVA IMS proposing the implementation of Campus Campolide Gym on our college campus, 
        I envision a modern fitness hub that addresses the health and wellness needs of our fellow students. 
        This initiative involves equipping a dedicated space with the latest exercise equipment, creating a dynamic environment 
        for physical activity, and hiring experienced trainers to provide guidance.
      </p>
    </div>
    <div class="post-footer">
      <div class="post-actions">
        <button class="upvote-button"><i class="uil uil-arrow-up"></i></button>
        <button class="downvote-button"><i class="uil uil-arrow-down"></i></button>
        <button class="comment-button"><i class="uil uil-comment"></i></button>
      </div>
      <div class="post-stats">
        <span class="vote-count"></span>
        <span class="comment-count"></span>
      </div>
      <div class="edit-delete-buttons">
      <a href="Create Post-html.html" class="edit-button" title="Edit"><i class="uil uil-edit"></i></a>
      <button class="delete-button" title="Delete"><i class="uil uil-trash-alt"></i></button>
      </div> 
    </div>
    `;
  
    return postElement;
  }
  
  