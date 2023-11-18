<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovation");

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

      </div>

    <!-- Post Template -->
    <div class="post">

        <div class="post-header">

          <div class="user-info">
            <img src="Images/persona.avif" alt="User Profile Picture">
            <span class="username">User_Persona</span>
          </div>

          <h2 class="post-title">Campus Campolide Gym</h2>~

          <div class="post-tag">
            <span class="input-tag">Exercise</span>
            <span class="input-type">Problem</span>
          </div>

        </div>

        <a href="ViewPost-html.html" class="post-link">

        <div class="post-content">

          <p>
            As a student from NOVA IMS proposing the implementation of Campus Campolide Gym on our college campus, 
            I envision a modern fitness hub that addresses the health and wellness needs of our fellow students. 
            This initiative involves equipping a dedicated space with the latest exercise equipment, creating a dynamic environment 
            for physical activity, and hiring experienced trainers to provide guidance.
          </p>

        </div>

        <img src="Images/gym.png" alt="Campus Gym Image" class="post-image">

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