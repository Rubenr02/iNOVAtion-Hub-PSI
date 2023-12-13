<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

// Check if a specific user ID is provided in the URL
$profileUserId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// If no specific user ID is provided, check if the current user is logged in
if (!$profileUserId && isset($_SESSION['USERID'])) {
    $profileUserId = $_SESSION['USERID'];
}

if (isset($_SESSION['USERID'])) {
    $visitorid = $_SESSION['USERID'];
}

if ($profileUserId) {
    // Fetch the username and profile image from the database based on the provided or logged-in USERID
    $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM USERS WHERE USERID = '$profileUserId'";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows == 1) {
        $userRow = $userResult->fetch_assoc();
        $username = $userRow['USERNAME'];
        $userImage = $userRow['USER_IMAGE'];
        
    } else {
        echo "Error fetching user information.";
        // Handle the case where no user is found with the provided ID
    }

    // Fetch posts with or without the filtered tag from both  and PROBLEMS tables
    $postQuery = "(SELECT 'idea' as post_type, IDEAID as post_id, IDEAS.TITLE, IDEAS.TAGID, IDEAS.TEXT, IDEAS.IMAGE as post_image, IDEAS.USERID, IDEAS.VOTESCORE
                    FROM IDEAS
                    INNER JOIN TAGS ON IDEAS.TAGID = TAGS.TAGID
                    INNER JOIN USERS ON IDEAS.USERID = USERS.USERID
                    WHERE IDEAS.USERID = '$profileUserId' AND USERS.USERNAME != 'anonymous' AND IDEAS.ISANONYMOUS = 0)
                    UNION
                    (SELECT 'problem' as post_type, PROBLEMID as post_id, PROBLEMS.TITLE, PROBLEMS.TAGID, PROBLEMS.TEXT, PROBLEMS.IMAGE as post_image, PROBLEMS.USERID, PROBLEMS.VOTESCORE
                    FROM PROBLEMS
                    INNER JOIN TAGS ON PROBLEMS.TAGID = TAGS.TAGID
                    INNER JOIN USERS ON PROBLEMS.USERID = USERS.USERID
                    WHERE PROBLEMS.USERID = '$profileUserId' AND USERS.USERNAME != 'anonymous' AND PROBLEMS.ISANONYMOUS = 0)
                    ORDER BY VOTESCORE DESC";



    $postResult = $conn->query($postQuery);

    $posts = []; // Initialize an array to store posts

    if ($postResult->num_rows > 0) {
        while ($postRow = $postResult->fetch_assoc()) {
            $postType = $postRow["post_type"];
            $post_id = $postRow["post_id"];
            $postTitle = $postRow['TITLE'];
            $tagID = $postRow['TAGID'];
            $postContent = $postRow['TEXT'];
            $postUserID = $postRow['USERID'];  
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
            $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$postUserID'";
            $userResult = $conn->query($userQuery);

            if ($userResult->num_rows == 1) {
                $userRow = $userResult->fetch_assoc();
                $userName = $userRow['USERNAME'];
                $userImage = $userRow['USER_IMAGE'];
            } else {
                echo "Error fetching User information.";
            }

            $posts[] = [
                'postId' => $post_id,
                'postTitle' => $postTitle,
                'postContent' => $postContent,
                'tagName' => $tagName,
                'votescore' => $votescore,
                'userName' => $userName,
                'userId' => $postUserID,  
                'userImage' => $userImage,
                'postType' => $postType,  
            ];
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="Styling/Profile-css.css">  
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>

    <header>
        <div class="nav-bar">
            <div class="logo">
                <a href="iNOVAtion-html.php">
                    <img src="Images/iNOVAtion Hub.png" alt="Logo" id="main-logo">
                </a>
            </div>
            <div class="header-links">
                <a href="#"><i class="uil uil-user"></i> Profile</a>
            </div>
            <div class="navigation">
                <div class="nav-items">
                    <i class="uil uil-times nav-close-btn"></i>
                    <a href="#"><i class="uil uil-home"></i> Home</a>
                    <a href="#"><i class="uil uil-compass"></i> Explore</a>
                    <a href="#"><i class="uil uil-info-circle"></i> About</a>
                    <a href="landing-html.html"><i class="uil uil-signout"></i> Sign Out</a>
                </div>
            </div>
            <i class="uil uil-apps nav-menu-btn"></i>
        </div>
    </header>

    <body>
        <div class="profile-container">
            <div class="profile-header">
                <div class="edit-profile" id="editProfileBtn">
                    <?php if ($visitorid == $profileUserId) : ?>
                        <a href="Edit Profile-html.php" class="edit-profile-button">
                            <i class="uil uil-edit"></i> Edit Profile
                        </a>
                    <?php endif; ?> 
<<<<<<< HEAD
                    
                    <?php
                    // Check if the user is of usertype 2
                    $usertypeQuery = "SELECT USERTYPE FROM USERS WHERE USERID = '$visitorid'";
                    $usertypeResult = $conn->query($usertypeQuery);

                    if ($usertypeResult->num_rows == 1) {
                        $usertypeRow = $usertypeResult->fetch_assoc();
                        $usertype = $usertypeRow['USERTYPE'];

                        // Display the "Generate Code" button for usertype 2
                        if ($usertype == 2) {
                            echo '<form class="generate-code" action="send-email/generate_code.php" method="post">
                                    <button type="submit" name="generate" class="generate-code-button">Generate Code</button>
                                </form>';
                        }
                    }
                    ?>
=======
                    <?php
                // Check if the user is of usertype 2
                $usertypeQuery = "SELECT USERTYPE FROM USERS WHERE USERID = '$visitorid'";
                $usertypeResult = $conn->query($usertypeQuery);

                if ($usertypeResult->num_rows == 1) {
                    $usertypeRow = $usertypeResult->fetch_assoc();
                    $usertype = $usertypeRow['USERTYPE'];

                    // Display the "Generate Code" button for usertype 2
                    if ($usertype == 2) {
                        echo '<form class="generate-code" action="send-email/generate_code.php" method="post">
                                <button type="submit" name="generate" class="generate-code-button">Generate Code</button>
                            </form>';
                    }
                }
                ?>
>>>>>>> 5b52f9783ce6009c0c8f200f4e1dfecc4043cb20

                </div>
                
                <img src="<?php echo $userImage; ?>" alt="User Profile Picture" class="profile-picture" id="profilePicture">
                <div class="profile-info" id="profileInfo">
                    <h1 class="username" id="username"><?php echo $username; ?></h1>
                    <p class="description" id="description">Add your description here!</p>  
                </div>
            </div>

            <?php if (!empty($posts)) : ?>
        <div class="published-posts">
        <h2>Your Published Posts</h2>

        <?php foreach($posts as $post) : ?>
            <div class='post'>
                <div class='post-header'>
                    <div class='user-info'>
                        <img src="<?= $post['userImage'] ?>" alt="User Profile Picture">
                        <span class="username"><?= $post['userName'] ?></span>
                    </div>
                    <a href="ViewPost-html.php?post_id=<?php echo $post['postId']; ?>&post_type=<?php echo $post['postType']; ?>">
                        <h2 class="post-title"><?= $post['postTitle'] ?></h2>
                        <p class="post-tag"><?= $post['tagName'] ?></p>
                        <br>
                        <p class="post-content"><?= $post['postContent'] ?></p>
                    </a>
                </div>
                <div class='post-footer'>
                    <div class='post-actions'>
                        <form method='post' action='Php/vote.php'>
                            <input type='hidden' name='post_id' value='<?= $post['postId'] ?>'>
                            <button name='upvote' type='submit' class='upvote-button'>
                                <i class='uil uil-arrow-up'></i>
                            </button>
                            <span class='post-stats'><?= $post['votescore'] ?></span>
                            <button name='downvote' type='submit' class='downvote-button'>
                                <i class='uil uil-arrow-down'></i>
                            </button>
                        </form>
                        <a href='ViewPost-html.html#comments' class='post-link'>
                            <button class='comment-button'><i class='uil uil-comment'></i></button>
                        </a>
                    </div>


                    <?php if ($visitorid == $profileUserId) : ?>
                        <div class='edit-delete-buttons'>

                            <!-- Submit Form button with icon -->
                            <a href='Submit_Form-html.php?edit_post_id=<?= $post['postId'] ?>'>
                                <i class='uil uil-file-edit-alt'></i> Submit Form
                            </a>

                            <!-- Edit button with icon -->
                            <a href='Create Post-html.php?edit_post_id=<?= $post['postId'] ?>'>
                                <i class='uil uil-pen'></i> Edit
                            </a>

                            <!-- Delete button with icon -->
                            <form method='post' action='Php/deletepost.php'>
                                <input type='hidden' name='post_id' value='<?= $post['postId'] ?>'>
                                <input type="hidden" name="post_type" value="<?php echo $post['postType']; ?>">
                                <button name='delete-button' type='submit' class='delete-button'>
                                    <i class='uil uil-trash-alt'></i> Delete
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p>No Posts Yet to Show!</p>
<?php endif; ?>
            </div>
        </div>
    </body>

<script type="text/javascript" src="Scripts/Profile-js.js"></script>

<script>
function generateCode() {
    // Assuming you have a JavaScript function to handle code generation on the client side
    // You can generate a random code and then send it to the server to be inserted into the database

    // For example, you can use AJAX to send a request to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "generate_code.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Handle the response if needed
            console.log(xhr.responseText);
        }
    };

    // Send the request
    xhr.send();
}
</script>

</body>
</html>
