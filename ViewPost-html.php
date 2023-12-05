<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

// Check if the post ID is set in the URL
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Check if its either a Idea or a Problem
    $postType = isset($_GET['post_type']) ? $_GET['post_type'] : ''; // Add post_type to your URL

    if ($postType === 'idea') {
        $postTable = 'IDEAS';
        $postid = 'IDEAID';
    } elseif ($postType === 'problem') {
        $postTable = 'PROBLEMS';
        $postid = 'PROBLEMID';
    } else {
        echo "Invalid post type.";
        exit; 
    }

    // Fetch post information from the database based on the post ID
    $postQuery = "SELECT * FROM $postTable WHERE $postid = '$post_id'";
    $postResult = $conn->query($postQuery);

    if ($postResult->num_rows == 1) {
        $postRow = $postResult->fetch_assoc();

        $postTitle = $postRow['TITLE'];
        $tagID = $postRow['TAGID'];
        $postContent = $postRow['TEXT'];
        $postImage = $postRow['IMAGE'];
        $userID = $postRow['USERID'];
        $votescore = $postRow['VOTESCORE'];
        $postPDF = $postRow['FILE'];
        $postDate = $postRow['CREATEDON'];

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
        //
        $userQuery = "SELECT 
        IF(ISANONYMOUS = 1, 'Anonymous', USERS.USERNAME) AS USERNAME,
        IF(ISANONYMOUS = 1, 'Images/picture.jpg', USERS.IMAGE) AS USER_IMAGE
        FROM $postTable
        LEFT JOIN USERS ON $postTable.USERID = USERS.USERID
        WHERE $postTable.$postid = '$post_id'";

        $userResult = $conn->query($userQuery);

        if ($userResult->num_rows == 1) {
        $userRow = $userResult->fetch_assoc();

        // Check if the user is anonymous
        if ($userRow['USERNAME'] === 'Anonymous') {
            $userName = 'Anonymous';
            $userImage = 'Images/picture.jpg';
        } else {
            $userName = $userRow['USERNAME'];
            $userImage = $userRow['USER_IMAGE'];
        }
        } else {
        echo "Error fetching Name information.";
        }
    } else {
        echo "Post not found.";
    }
} else {
    echo "Post ID not provided.";
}

// Fetch comments associated with the post
$fetchCommentsQuery = "SELECT * FROM COMMENTS 
                      WHERE IDEAID = CASE 
                      WHEN '$postType' = 'idea' THEN '$post_id'
                      WHEN '$postType' = 'problem' THEN NULL
                      END

                      OR PROBLEMID = CASE
                      WHEN '$postType' = 'problem' THEN '$post_id'
                      WHEN '$postType' = 'idea' THEN NULL
                      END";

$commentsResult = $conn->query($fetchCommentsQuery);$commentsResult = $conn->query($fetchCommentsQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iNOVAtion Hub</title>
    <link rel="stylesheet" href="Styling/Post-css.css">
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

    <!-- Post Template -->
    <div class="post">
        <div class="post-header">
            <div class="user-info">
                
            <?php
            if ($userRow['USERNAME'] != 'Anonymous') {
            echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
            }
            ?>
                <img src= <?php echo $userImage; ?> alt='User Profile Picture'>
            <?php
            echo '</a>';
            ?>
            <?php
            if ($userRow['USERNAME'] != 'Anonymous') {
            echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
            }   
            ?>
                <span class="username"><?php echo $userName; ?></span>
            <?php
            echo '</a>';
            ?>
            </div>
            <br>
            <h2 class="post-title"><?php echo $postTitle; ?></h2>
            <div class="post-tag">
                <span class="input-type">
                    <?php
                    // Check the table from which the post ID is fetched so that i can display if its either a Idea or a Problem
                    if (isset($postRow['IDEAID']) && $postRow['IDEAID']) {
                        echo "Idea";
                    } elseif (isset($postRow['PROBLEMID']) && $postRow['PROBLEMID']) {
                        echo "Problem";
                    }
                    ?>
                </span>
                <span class="input-tag"><?php echo $tagName; ?></span>
                <span class="input-date"><?php echo date('Y-m-d', strtotime($postRow['CREATEDON'])); ?></span>
            </div>
        </div>

        <br>

        <div class="post-content">
            <p><?php echo $postContent; ?></p>
        </div>

        <br>

        <div class="post-image">
            <img src="<?php echo $postImage; ?>" alt="Post Image">
        </div>

        <br>

        <div class="post-files">
            <h3>Files</h3>
            <ul>
                <li><a href="<?php echo $postRow['FILE']; ?>">Download File</a></li>
            </ul>
        </div>

        <br>

        <div class="post-footer">
            <div class="post-actions">
                <form method="post" action="Php/vote.php">
                    <button class="upvote-button"><i class="uil uil-arrow-up"></i></button>
                    <button class="downvote-button"><i class="uil uil-arrow-down"></i></button>
                    <button class="comment-button"><i class="uil uil-comment"></i></button>
                </div>
                <div class="post-stats">
                    <span class="upvote-count"><?php echo $votescore; ?></span>
                </div>
                <div class="report">
                    <a href=""><i class="uil uil-exclamation-circle"></i>Report</a>
                </div>
                </form>

        </div>

        <br><br>

        <?php
        // Check if the user is logged in and 'user_id' is set in the $_SESSION array
        if (isset($_SESSION['USERID'])) {
            $loggedUserID = $_SESSION['USERID'];

            // Fetch the username and image of the logged-in user
            $loggedUserQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM USERS WHERE USERID = '$loggedUserID'";
            $loggedUserResult = $conn->query($loggedUserQuery);

            if ($loggedUserResult->num_rows == 1) {
                $loggedUserRow = $loggedUserResult->fetch_assoc();
                $loggedUserName = $loggedUserRow['USERNAME'];
                $loggedUserImage = $loggedUserRow['USER_IMAGE'];
            } else {
                echo "Error fetching user information.";
            }
        } else {
            echo "User not logged in or 'user_id' not set in the session.";
        }
        ?>

        <!-- Comment Input Box -->
        <div class="comment-container">
            <div class="comment-image">
                <img src="<?php echo isset($loggedUserImage) ? $loggedUserImage : ''; ?>" alt='User Profile Picture'>
            </div>
            <div class="comment-input-box">
                <form method="post" action="Php/comment.php?post_id=<?php echo $post_id; ?>&post_type=<?php echo $postType; ?>">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <textarea id="input-comment" name="input-comment" placeholder="Add a comment" rows="4" required></textarea>
                    <button class="submit-comment" id="post-button" type="submit" name="submit-comment"> Post
                        <i class="uil uil-plus-circle"></i>
                    </button>
                </form>
            </div>
        </div>

        <br>

        <div id="comments" class="comment-section">

    <?php
    if ($commentsResult->num_rows > 0) {
        while ($commentRow = $commentsResult->fetch_assoc()) {
            $commentUserID = $commentRow['USERID'];
            $commentText = $commentRow['CHARACTERS'];
            $commentDate = $commentRow['CREATEDON'];
            $commentID = $commentRow['COMMENTID'];

            // Fetch the username and image of the user who commented
            $commentUserQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM USERS WHERE USERID = '$commentUserID'";
            $commentUserResult = $conn->query($commentUserQuery);

            if ($commentUserResult->num_rows == 1) {
                $commentUserRow = $commentUserResult->fetch_assoc();
                $commentUserName = $commentUserRow['USERNAME'];
                $commentUserImage = $commentUserRow['USER_IMAGE']; 

        
                // Display comment
                echo "<div class='comment-section'>";
                echo "<div class='comment-header'>";
                echo '<img src="' . $commentUserImage . '" alt="User Profile Picture">';
                echo "<span class='user-name'>$commentUserName</span>";
                echo "<span class='comment-date'>$commentDate</span>";
                echo "</div>";
                echo "<div class='comment-box'>";
                echo "<p>$commentText</p>";

                // Report button for each comment
                echo "<div class='comment-actions'>";
                echo "<form method='post' action='Php/report.php'>";
                echo "<input type='hidden' name='comment_id' value='$commentID'>";
                echo "<button class='report-button' type='submit' name='submit-report'>";
                echo "<i class='uil uil-exclamation-circle'></i> Report";
                echo "</button>";
                echo "</form>";
                echo "</div>";

                echo "</div>";
                echo "</div>";
            }
        }
    } else {
        echo "No comments found.";
    }
    ?>

</div>

<script src="Scripts/iNOVAtion-js.js"></script>
<script src="Scripts/ViewPost-js.js"></script>

</body>

</html>