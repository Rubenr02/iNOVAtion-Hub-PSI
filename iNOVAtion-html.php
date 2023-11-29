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
 

// Fetch posts from both IDEAS and PROBLEMS tables
$postQuery = "(SELECT 'idea' as post_type, IDEAID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
              FROM IDEAS)
              UNION
              (SELECT 'problem' as post_type, PROBLEMID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
              FROM PROBLEMS)
              ORDER BY VOTESCORE DESC";

$postResult = $conn->query($postQuery);

if ($postResult->num_rows > 0) {
    while ($postRow = $postResult->fetch_assoc()) {
        $postType = $postRow["post_type"];
        $post_id  = ($postType == 'idea') ? $postRow["IDEAID"] : $postRow["PROBLEMID"];
        $postTitle = $postRow['TITLE'];
        $tagID = $postRow['TAGID'];
        $postContent = $postRow['TEXT'];
        $postImage = $postRow['IMAGE'];
        $userID = $postRow['USERID']; 
        $votescore = $postRow['VOTESCORE'];

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
    $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userID'";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows == 1) {
        $userRow = $userResult->fetch_assoc();
        $userName = $userRow['USERNAME'];
        $userImage = $userRow['USER_IMAGE'];
    } else {
        echo "Error fetching User information.";
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
<style>
        .prev, .next {
            position: absolute;
            top: 60%;
            font-size: 20px;
            cursor: pointer;
            color: #333;
            background-color: transparent;
            border: none;
            transition: color 0.3s;
        }

        .prev:hover, .next:hover {
            color: #ff4500;
        }

        .prev {
            left: 7%;
        }

        .next {
            right: 7%;
        }
</style>
<body>

    <header>

        <div class="nav-bar">

          <div class="logo">
            <a href="iNOVAtion-html.php">
              <img src="Images/iNOVAtion Hub.png" alt="#" id="main-logo">
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
    <div class="top-posts">

      <div class="top-posts-section">
        <div class="top-posts-title">
            <span>Top Posts <i class="uil uil-fire"></i></span>
        </div>
      </div>

    
  <!-- Carousel Container -->
  <div class="carousel-container">  
    <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
  
    <?php

    // Create connection
    $conn = mysqli_connect("localhost", "root", "", "psi");
    if ($conn) {
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_errno();
        }


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
    }

        // Fetch posts from both IDEAS and PROBLEMS tables
        $postQuery = "(SELECT 'idea' as post_type, IDEAID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
                    FROM IDEAS)
                    UNION
                    (SELECT 'problem' as post_type, PROBLEMID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
                    FROM PROBLEMS)
                    ORDER BY VOTESCORE DESC";

        $postResult = $conn->query($postQuery);

        if ($postResult->num_rows > 0) {
            while ($postRow = $postResult->fetch_assoc()) {
                $postType = $postRow["post_type"];
                $post_id  = ($postType == 'idea') ? $postRow["IDEAID"] : $postRow["PROBLEMID"];
                $postTitle = $postRow['TITLE'];
                $tagID = $postRow['TAGID'];
                $postContent = $postRow['TEXT'];
                $postImage = $postRow['IMAGE'];
                $userID = $postRow['USERID']; 
                $votescore = $postRow['VOTESCORE'];

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
                $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userID'";
                $userResult = $conn->query($userQuery);

                if ($userResult->num_rows == 1) {
                    $userRow = $userResult->fetch_assoc();
                    $userName = $userRow['USERNAME'];
                    $userImage = $userRow['USER_IMAGE'];
                } else {
                    echo "Error fetching User information.";
                }

                // Display the post
                echo '<section class="post">';
                echo '<div class="post-header">';
                echo '<div class="user-info">';
                echo '<img src="' . $userImage . ' " alt="User Profile Picture">';
                echo '<span class="username">' . $userName . '</span>';
                echo '</div>';
                echo '<h2 class="post-title">' . $postTitle . '</h2>';
                echo '<div class="post-tag">';
                echo '<span class="input-tag">' . $tagName . '</span>';
                echo '</div>';
                echo '</div>';
                echo '<a href="ViewPost-html.php?post_id=' . $post_id . '" class="post-link">';
                echo '<div class="post-content">';
                echo '<p>' . $postContent . '</p>';
                echo '</div>';
                echo '<img src="' . $postImage . '" class="post-image">';
                echo '</a>';
                echo '<div class="post-footer">';
                echo '<div class="post-actions">';
                echo '<form method="post" action="Php/vote.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button name="upvote" type="submit" class="upvote-button">';
                echo '<i class="uil uil-arrow-up"></i>';
                echo '</button>';
                echo '<span class="post-stats">' . $votescore . '</span>';
                echo '<button name="downvote" type="submit" class="downvote-button">';
                echo '<i class="uil uil-arrow-down"></i>';
                echo '</button>';
                echo '</form>';
                echo '<a href="ViewPost-html.php?post_id=' . $post_id . '#comments" class="post-link">';
                echo '<button class="comment-button"><i class="uil uil-comment"></i></button>';
                echo '</a>';
                echo '</div>';
                echo '</div>';
                echo '</section>';
            }
        } else {
            echo "No posts found.";
        }
        ?>
        <button class="next" onclick="plusSlides(1)">&#10095;</button>

    
    </div>
    </div>

</div>



<!-- JavaScript for the Landing Page-->
<script type="text/javascript" src="Scripts/iNOVAtion-js.js"></script>



</body>
</html>
