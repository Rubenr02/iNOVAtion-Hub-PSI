<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "psi");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['USERID'])) {
    // Retrieve the user ID from the session
    $userid = $_SESSION['USERID'];

    // Fetch the username from the database based on the USERID
    $sql = "SELECT USERNAME FROM USERS WHERE USERID = '$userid'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row['USERNAME'];
    }

// Fetch posts from the database
$postQuery = "SELECT * FROM IDEAS";
$postResult = $conn->query($postQuery);


if ($postResult->num_rows > 0) {
  while ($postRow = $postResult->fetch_assoc()) {
      $postTitle = $postRow['TITLE'];
      $tagID = $postRow['TAGID']; // Replace with the actual column name
      $postContent = $postRow['TEXT'];
      $postImage = $postRow['IMAGE'];
      $userID = $postRow['USERID']; // Replace with the actual column name


      // Fetch tag information from TAGS table
      $tagQuery = "SELECT TAGS FROM tags WHERE TAGID = '$tagID'";
      $tagResult = $conn->query($tagQuery);

      if ($tagResult->num_rows == 1) {
        $tagRow = $tagResult->fetch_assoc();
        $tagName = $tagRow['TAGS'];

      } else {
          echo "Error fetching tag information.";
      }

      // Fetch Username information from USERS table (for the post)
      $userQuery = "SELECT USERNAME FROM users WHERE USERID = '$userID'";
      $userResult = $conn->query($userQuery);

      if ($userResult->num_rows == 1) {
        $userRow = $userResult->fetch_assoc();
        $userName = $userRow['USERNAME'];

      } else {
          echo "Error fetching Name information.";
      }

  }
} else {
    echo "No posts found.";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iNOVAtion Hub</title>
    <link rel="stylesheet" href="Styling/iNOVAtion-css.css">  
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>

    <header>

        <div class="nav-bar">

          <div class="logo">
            <a href="iNOVAtion-html.php">
              <img src="Images/iNOVAtion Hub.png" alt="Your Logo" id="main-logo">
            </a>
          </div>

          <div class="header-links">
            <div class="search-bar">
              <input type="text" placeholder="Search for tags, problems, ideas ...">
              <i class="uil uil-search"></i>
            </div>

            <a href="Profile-html.html"><i class="uil uil-user"></i> Profile</a>
          </div>

          <div class="navigation">
            <div class="nav-items">
              <i class="uil uil-times nav-close-btn"></i>
              <a href="#"><i class="uil uil-home"></i> Home</a>
              <a href="#"><i class="uil uil-compass"></i> Explore</a>
              <a href="#"><i class="uil uil-info-circle"></i> About</a>
              <a href="#"><i class="uil uil-envelope"></i> Contact</a>
            </div>
          </div>

          <i class="uil uil-apps nav-menu-btn"></i>

        </div>

      </header>

      <div class="top-bar">

        <div class="left">
          <span><?php echo isset($username) ? $username : ''; ?></span>
        </div>

        <div class="center">
          
          <div class="create-post-button">
              <a href="Create Post-html.php" class="create-post-link">
                  Create a Post
                  <button class="plus-icon">+</button>
              </a>
          </div>

      </div>
      
        <div class="right">
            Filter
            <button class="filter-button"><i class="uil uil-filter"></i></button>
        </div>

    </div>

    <!-- Top Posts Section -->
    <section class="top-posts">

      <div class="top-posts-section">
        <div class="top-posts-title">
            <span>Top Posts <i class="uil uil-fire"></i></span>
        </div>
        <!--  Posts content goes here -->
    </div>

    <!-- Post Template -->
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
            <button class="upvote-button"><i class="uil uil-arrow-up"></i></button>
            <button class="downvote-button"><i class="uil uil-arrow-down"></i></button>
            <a href="ViewPost-html.html" class="post-link">
                <button class="comment-button"><i class="uil uil-comment"></i></button>
            </a>
        </div>
        <div class="post-stats">
            <span class="vote-count"></span>
            <span class="comment-count"></span>
        </div>
    </div>
</div>


  </section>

  <script type="text/javascript" src="Scripts/iNOVAtion-js.js"></script>

</body>
</html>