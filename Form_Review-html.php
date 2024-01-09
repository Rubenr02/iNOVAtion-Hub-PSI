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
            <a href="About-html.html"><i class="uil uil-info-circle"></i> About</a>
            <a href="Contact-html.html"><i class="uil uil-envelope"></i> Contact</a>
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

    <?php
    // Fetch forms from the database with corresponding idea information
    $formsQuery = "SELECT FORMS.FORMID, FORMS.IDEAID, FORMS.USERID, FORMS.CREATEDON, FORMS.ACCEPTED, FORMS.FILE, FORMS.IMAGE AS FORM_IMAGE, FORMS.TEXT1, FORMS.TEXT2, IDEAS.TITLE, IDEAS.TAGID, IDEAS.LEVEL, TAGS.TAGS
                FROM FORMS
                INNER JOIN IDEAS ON FORMS.IDEAID = IDEAS.IDEAID
                INNER JOIN TAGS ON IDEAS.TAGID = TAGS.TAGID";

    $formsResult = $conn->query($formsQuery);

    // Display forms with corresponding idea information
    if ($formsResult->num_rows > 0) {
        while ($formRow = $formsResult->fetch_assoc()) {
            $formId = $formRow['FORMID'];
            $ideaId = $formRow['IDEAID'];
            $userId = $formRow['USERID'];
            $createdOn = $formRow['CREATEDON'];
            $accepted = $formRow['ACCEPTED'];
            $file = $formRow['FILE'];
            $formImage = $formRow['FORM_IMAGE'];
            $text1 = $formRow['TEXT1'];
            $text2 = $formRow['TEXT2'];
            $ideaTitle = $formRow['TITLE'];
            $tagId = $formRow['TAGID'];
            $tagName = $formRow['TAGS'];
            $level = $formRow['LEVEL'];

        // Fetch Username information from USERS table (for the form)
        $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userId'";
        $userResult = $conn->query($userQuery);

        if ($userResult->num_rows == 1) {
            $userRow = $userResult->fetch_assoc();
            $userName = $userRow['USERNAME'];
            $userImage = $userRow['USER_IMAGE'];

            // Display form information with corresponding idea details
            echo '<section class="post">';
            echo '<div class="post-header">';
            echo '<div class="user-info">';
            echo '<a href="Profile-html.php?user_id=' . $userId . '" class="user-link">';
            echo '<img src="' . $userImage . '" alt="User Profile Picture" class="user-image">';
            echo '</a>';
            echo '<a href="Profile-html.php?user_id=' . $userId . '" class="user-link">';
            echo '<span class="username">' . $userName . '</span>';
            echo '</a>';
            echo '</div>';
            echo '<h2 class="post-title">' . $ideaTitle . '</h2>';
            echo '<div class="post-tag">';
            echo '<span class="input-tag">' . $tagName . ' | Level: ' . $level . '</span>';
            echo '<br><br>';
            echo '</div>';
            echo '</div>';
            echo '<a href="ViewPost-html.php?post_id=' . $ideaId . '&post_type=idea" class="post-link">';
            echo '<div class="post-content">';
            echo '<p>' . $text1 . '</p>';
            echo '</div>';
            echo '<div class="post-content">';  
            echo '<p>' . $text2 . '</p>';
            echo '</div>';
            echo '<img src="' . $formImage . '" class="post-image">';
            echo '</a>';
            echo '<div class="post-footer">';
            echo '<div class="post-actions">';
            echo '<div class="vote-container" data-post-id="' . $ideaId . '">';
            echo '</div>';
            echo '<a href="ViewPost-html.php?post_id=' . $ideaId . '&post_type=idea#comments" class="post-link">';
            echo '</a>';
            echo '</div>';
            echo '<a href="Review-html.php?post_id=' . $ideaId . '" class="review-button">Review <i class="uil uil-check-circle"></i></a>';
            echo '</div>';
            echo '</section>';
        } else {
            echo "Error fetching User information.";
        }
        }
    } else {
        echo "No forms found.";
    }


    ?>
    </div>
    <script src="Scripts/iNOVAtion-js.js"></script>

