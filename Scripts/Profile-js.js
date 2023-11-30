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
            <span class="username"><?php echo $userName; ?></span>
        </div>
        <h2 class="post-title"><?php echo $postTitle; ?></h2>
        <div class="post-tag">
            <span class="input-tag"><?php echo $tagName; ?></span>
        </div>
    </div>
    <a href="ViewPost-html.html" class="post-link">
        <div class="post-content">
            <p><?php echo $postContent; ?></p>
        </div>
        <img src="<?php echo $postImage; ?>" class="post-image">
    </a>
    <div class="post-footer">
        <div class="post-actions">
            <form method="post" action="Php/vote.php">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <button name="upvote" type="submit" class="upvote-button">
                    <i class="uil uil-arrow-up"></i>
                </button>
                <span class="post-stats"><?php echo $votescore; ?></span>
                <button name="downvote" type="submit" class="downvote-button">
                    <i class="uil uil-arrow-down"></i>
                </button>
            </form>
            <a href="ViewPost-html.html#comments" class="post-link">
              <button class="comment-button"><i class="uil uil-comment"></i></button>
            </a>
        </div>
</div>
    `;
  
    return postElement;
  }
  
  