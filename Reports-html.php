<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

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
    
// Check if the user is of usertype 1(reviewer)
$usertypeQuery = "SELECT USERTYPE FROM USERS WHERE USERID = '$userid'";
$usertypeResult = $conn->query($usertypeQuery);

if ($usertypeResult->num_rows == 1) {
    $usertypeRow = $usertypeResult->fetch_assoc();
    $usertype = $usertypeRow['USERTYPE'];
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
              (SELECT 'problem' as post_type, PROBLEMS.PROBLEMID, PROBLEMS.TITLE, PROBLEMS.TAGID, PROBLEMS.TEXT, PROBLEMS.IMAGE, PROBLEMS.USERID, PROBLEMS.VOTESCORE
              FROM PROBLEMS
              INNER JOIN TAGS ON PROBLEMS.TAGID = TAGS.TAGID
              INNER JOIN USERS ON PROBLEMS.USERID = USERS.USERID
              " . ($filteredTag ? "WHERE TAGS.TAGS = '$filteredTag'" : "") . ")
              ORDER BY VOTESCORE DESC";


$postResult = $conn->query($postQuery);

if ($postResult->num_rows > 0) {
    while ($postRow = $postResult->fetch_assoc()) {
        $postType = $postRow["post_type"];
        if ($postType == 'idea') {
            $post_id = $postRow["IDEAID"];
        } elseif ($postType == 'problem' && isset($postRow["PROBLEMID"])) {
            $post_id = $postRow["PROBLEMID"];
        } else {
            // Handle the case where the post type is unknown or PROBLEMID is not set
            $post_id = null;
        }
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

    // Fetch and display forms
    $formsQuery = "SELECT * FROM FORMS";
    $formsResult = $conn->query($formsQuery);

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
    <title>Display Forms</title>
    <link rel="stylesheet" href="Styling/Form_Review-css.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                <input type="text" id="postSearch" placeholder="Search for problems, ideas ..." oninput="searchPosts()">
                <i class="uil uil-search"></i>
            </div>

            <?php if (isset($userid)) : ?>
                <a href="Profile-html.php?user_id=<?php echo $userid; ?>"><i class="uil uil-user"></i> Profile</a>
            <?php endif; ?>

        </div>

        <div class="navigation">
            <div class="nav-items">
                <i class="uil uil-times nav-close-btn"></i>
                <a href="iNOVAtion-html.php"><i class="uil uil-home"></i> Home</a>
                <a href="About.html"><i class="uil uil-info-circle"></i> About</a>
                <a href="Contact.html"><i class="uil uil-envelope"></i> Contact</a>
                <a href="landing-html.html"><i class="uil uil-signout"></i> Sign Out</a>
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

    <!-- Container for Top Posts and Review Posts -->
    <div class="top-posts-container">
        <!-- Top Posts Section -->
        <div class="top-posts">
            <div class="top-posts-section">
                <div class="top-posts-title">
                    <span>Top Posts <i class="uil uil-fire"></i></span>
                </div>
            </div>
        </div>

        <!-- Review Posts Section -->
        <?php
        if ($usertype == 1 || $usertype == 2) {
            echo '<div class="review-posts">';
            echo '<div class="review-posts-section">';
            echo '<div class="review-posts-title">';
            echo '</div>';
            echo '<a href="Form_Review-html.php"><i class="uil uil-newspaper"></i> Posts to Review</a>';
            echo '</div>';
            echo '</div>';
        }
        ?>
        <?php
        if($usertype == 2 || $usertype == 3) {
            echo '<div class="reported-posts">';
            echo '<div class="reported-posts-section">';
            echo '<div class="reported-posts-title">';
            echo '</div>';
            echo '<a href="Reports-html.php"><i class="uil uil-exclamation-circle"></i> Reported Posts</a>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

        <!-- Display reported posts for reviewers -->
        <?php
        // Fetch posts with or without the filtered tag from both IDEAS and PROBLEMS tables
        if ($usertype == 2 || $usertype == 3) {
            // Display reported posts for usertype 2 and 3
            $postQuery = "(SELECT 'idea' as post_type, IDEAID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
                        FROM IDEAS
                        WHERE REPORT = 1)
                        UNION
                        (SELECT 'problem' as post_type, PROBLEMID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
                        FROM PROBLEMS
                        WHERE REPORT = 1)
                        ORDER BY VOTESCORE DESC";
        } else {
            // Display regular posts for other user types
            $postQuery = "(SELECT 'idea' as post_type, IDEAID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
                        FROM IDEAS
                        INNER JOIN TAGS ON IDEAS.TAGID = TAGS.TAGID
                        " . ($filteredTag ? "WHERE TAGS.TAGS = '$filteredTag'" : "") . ")
                        UNION
                        (SELECT 'problem' as post_type, PROBLEMID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE
                        FROM PROBLEMS
                        INNER JOIN TAGS ON PROBLEMS.TAGID = TAGS.TAGID
                        INNER JOIN USERS ON PROBLEMS.USERID = USERS.USERID
                        " . ($filteredTag ? "WHERE TAGS.TAGS = '$filteredTag'" : "") . ")
                        ORDER BY VOTESCORE DESC";
        }

        $postResult = $conn->query($postQuery);

        if ($postResult->num_rows > 0) {
            while ($postRow = $postResult->fetch_assoc()) {
                // Display information about each post
                $postType = $postRow["post_type"];
                if ($postType == 'idea') {
                    $post_id = $postRow["IDEAID"];
                } elseif ($postType == 'problem' && isset($postRow["PROBLEMID"])) {
                    $post_id = $postRow["PROBLEMID"];
                } else {
                    // Handle the case where the post type is unknown or PROBLEMID is not set
                    $post_id = null;
                }
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

                // Fetch and display forms
                $formsQuery = "SELECT * FROM FORMS";
                $formsResult = $conn->query($formsQuery);

                // Fetch Username information from USERS table (for the post)
                $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userID'";
                $userResult = $conn->query($userQuery);

                if ($userResult->num_rows == 1) {
                    $userRow = $userResult->fetch_assoc();
                    $userName = $userRow['USERNAME'];
                    $userImage = $userRow['USER_IMAGE'];

                    // Display post information with corresponding details
                    echo '<section class="post">';
                    echo '<div class="post-header">';
                    echo '<div class="user-info">';
                    echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
                    echo '<img src="' . $userImage . '" alt="User Profile Picture" class="user-image">';
                    echo '</a>';
                    echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
                    echo '<span class="username">' . $userName . '</span>';
                    echo '</a>';
                    echo '</div>';
                    echo '<h2 class="post-title">' . $postTitle . '</h2>';
                    echo '<div class="post-tag">';
                    echo '<span class="input-tag">' . $tagName . '</span>';
                    echo '<br><br>';
                    echo '</div>';
                    echo '</div>';
                    echo '<a href="ViewPost-html.php?post_id=' . $post_id . '&post_type=' . $postType . '" class="post-link">';
                    echo '<div class="post-content">';
                    echo '<p>' . $postContent . '</p>';
                    echo '</div>';
                    echo '<img src="' . $postImage . '" class="post-image">';
                    echo '</a>';
                    echo '<div class="post-footer">';
                    echo '<div class="post-actions">';
                    echo '<div class="vote-container" data-post-id="' . $post_id . '">';
                    echo '</div>';
                    echo '</div>';
                    echo '<a href="ViewPost-html.php?post_id=' . $post_id . '&post_type=' . $postType . '#comments" class="post-link">';
                    echo '</a>';
                    echo '<form action="Php/deletepost.php?post_id=' . $post_id . '" method="post" onsubmit="return confirm(\'Are you sure you want to delete this post?\');">';
                    echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                    echo '<input type="hidden" name="post_type" value="' . $postType . '">';
                    echo '<button type="submit" name="delete-button" class="delete-button">Delete</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</section>';
                } else {
                    echo "Error fetching User information.";
                }
            }
        } else {
            echo "No posts found.";
        }
        ?>
    </div>
    
<script src="Scripts/iNOVAtion-js.js"></script>
</body>

</html>
