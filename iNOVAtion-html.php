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
 
// Fetch tags from the database
$tagQuery = "SELECT TAGS FROM TAGS";
$tagResult = $conn->query($tagQuery);

// Fetch the filtered tag (if any)
$filteredTag = isset($_GET['tag']) ? $_GET['tag'] : null;

// Fetch posts with or without the filtered tag from both IDEAS and PROBLEMS tables
$postQuery = "(SELECT 'idea' as post_type, IDEAID, IDEAS.TITLE, IDEAS.TAGID, TEXT, IMAGE, IDEAS.USERID, VOTESCORE
              FROM IDEAS
              INNER JOIN TAGS ON IDEAS.TAGID = TAGS.TAGID
              " . ($filteredTag ? "WHERE TAGS.TAGS = '$filteredTag'" : "") . ")
              UNION
              (SELECT 'problem' as post_type, PROBLEMID, PROBLEMS.TITLE, PROBLEMS.TAGID, TEXT, IMAGE, PROBLEMS.USERID, VOTESCORE
              FROM PROBLEMS
              INNER JOIN TAGS ON PROBLEMS.TAGID = TAGS.TAGID
              " . ($filteredTag ? "WHERE TAGS.TAGS = '$filteredTag'" : "") . ")
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

            <a href="Profile-html.php"><i class="uil uil-user"></i> Profile</a>
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
      
      <div class="custom-dropdown" id="customDropdown">
        <button class="filter-button" onclick="toggleFilterSection()">
            <i class="uil uil-filter"></i> Filter
        </button>
        <div class="dropdown-content" id="dropdownContent">
            <input type="text" id="tagSearch" placeholder="Search tags" oninput="filterTags()">
            <?php
            // Fetch tags from the database
            $tagQuery = "SELECT TAGS FROM TAGS";
            $tagResult = $conn->query($tagQuery);

            if ($tagResult->num_rows > 0) {
                while ($tagRow = $tagResult->fetch_assoc()) {
                    $tagName = $tagRow['TAGS'];
                    echo "<div class='tag-option' onclick='selectTag(\"$tagName\")'>$tagName</div>";
                }
            }
            ?>
        </div>
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
                echo '<br><br>';
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


<script>
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
</script>

</body>
</html>
