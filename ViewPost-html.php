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
    $senderID = $_SESSION['USERID'];

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

                
// Fetch the filtered tags
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

    $currentUserQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userid'";
    $currentUserResult = $conn->query($currentUserQuery);

    if ($currentUserResult->num_rows == 1) {
        $currentUserRow = $currentUserResult->fetch_assoc();
        $currentUserName = $currentUserRow['USERNAME'];
        $currentUserImage = $currentUserRow['USER_IMAGE'];
    } else {
        echo "Error fetching current user information.";
    }

    // Check if the form is submitted to handle the message sending
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $senderID = $userid;
        $receiverID = $_POST['receiverID'];
        $message = $_POST['message'];

        // Insert into the database
        $sql = "INSERT INTO CHATS (SENDERID, RECEIVERID, MESSAGE, SENT_ON) VALUES ($senderID, $receiverID, '$message', NOW())";
        
        if ($conn->query($sql) === TRUE) {
            echo 'Message sent successfully';
        } else {
            echo 'Error sending message: ' . $conn->error;
        }
        exit; 
    }

    // Fetch messages from the database
    $sql2 = "SELECT * FROM CHATS ORDER BY SENT_ON ASC"; 

    $result = $conn->query($sql2);

    if ($result) {
        $messages = array();

        while ($row = $result->fetch_assoc()) {
            $messages[] = array(
                'senderID' => $row['SENDERID'],
                'message' => $row['MESSAGE'],
                'sentOn' => $row['SENT_ON']
            );
        }
    } else {
        echo 'Error fetching messages: ' . $conn->error;
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        var senderID = <?php echo json_encode($userid); ?>;
    </script>
    <script type="text/javascript" src="Scripts/message-js.js"></script>
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

<<<<<<< HEAD
    <div class="navigation">
        <div class="nav-items">
            <i class="uil uil-times nav-close-btn"></i>
            <a href="iNOVAtion-html.php"><i class="uil uil-home"></i> Home</a>
            <a href="About-html.html"><i class="uil uil-info-circle"></i> About</a>
            <a href="Contact-html.html"><i class="uil uil-envelope"></i> Contact</a>
            <a href="landing-html.html"><i class="uil uil-signout"></i> Sign Out</a>
=======
      <div class="top-bar">

        <div class="left">
          <span><?php echo isset($username) ? $username : ''; ?></span>
>>>>>>> d28e949a6e923cd6cabb2b1ec27d0e8f2d1d462f
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
    
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

<button id="openChatBtn" onclick="toggleChat()">
    <i class="uil uil-comment"></i> 
</button>


<div id="chatbox">
	<div id="friendslist">
        <div id="topmenu">
        <div id="close-button" onclick="closeChat()">&#10006;</div>

            <?php 
                // Display the current users details in the chatbox header
                echo '<div class="user-avatar">';
                echo '<img src="' . $currentUserImage . '" alt="">';
                echo '</div>';
                echo '<div class="user-details">';
                echo '<span>' . $currentUserName . '</span>';
                echo '<span> Active Now </span>';
                echo '</div>';
            ?>
        </div>

        <div id="search">
	            <input type="text" id="searchfield" value="Search contacts..." />
            </div>
        
        <div id="friends">

            <?php
            $sql = "
                SELECT
                    USERID,
                    FIRSTNAME,
                    LASTNAME,
                    IMAGE,
                    EMAIL
                FROM
                    USERS
                WHERE
                USERID != '$userid'
                LIMIT 5;
                ";

            $result = $conn->query($sql);

            if ($result) {
                // Display the friends in the HTML 
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="friend" onclick="selectFriend(' . $row['USERID'] . ')">';
                    echo '<img src="' . $row['IMAGE'] . '" alt="Friend Image">';
                    echo '<p> <strong>' . $row['FIRSTNAME']  . '</strong>';
                    echo '<br>';
                    echo '<span>' . $row['EMAIL'] . '</span></p>';
                    echo '</div>';
                }

<<<<<<< HEAD
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
                </form>
            </div>
            <div class="post-stats">
                <span class="upvote-count"><?php echo $votescore; ?></span>
            </div>
            <?php
            // Check if the postType is 'problem'
            if ($postType === 'problem') {
                echo '<div class="create-solution-button">';
                echo '<a href="Create Post-html.php" class="create-solution-link">Create a solution</a>';
                echo '</div>';
            }
            ?>
            <div class="report">
                <button type="button" class="report-button">
                    <i class="uil uil-exclamation-circle"></i> Report
                </button>
            </div>
        </div>


        <script>
        $(document).ready(function() {
            $('.report-button').on('click', function() {
                var postID = <?php echo json_encode($post_id); ?>;
                var postType = <?php echo json_encode($postType); ?>;

                $.ajax({
                    type: 'POST',
                    url: 'Php/report.php',
                    data: { post_id: postID, post_type: postType },
                    success: function(response) {
                        alert(response); // Display the server response (you can replace this with your own handling logic)
                    },
                    error: function() {
                        alert('Error submitting report.');
                    }
                });
            });
        });
        </script>


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
=======
                // Free the result set
                $result->free();
>>>>>>> d28e949a6e923cd6cabb2b1ec27d0e8f2d1d462f
            } else {
                // Display an error message if the query fails
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            ?>
            
            
        </div>                
        
    </div>	
    
    <div id="chatview" class="p1">    	
        <div id="profile">

            <div id="close">
                <div class="cy"></div>
                <div class="cx"></div>
            </div>
            <p></p>
            <span></span>
        </div>

        <div id="chat-messages">
            <?php
            $currentDate = ''; // Variable to store the current date

            foreach ($messages as $message) {
                // Extract the date from the message
                $messageDate = date('M d', strtotime($message['sentOn']));
            
                // Check if the date has changed
                if ($messageDate != $currentDate) {
                    echo '<div class="message-date">' . $messageDate . '</div>';
                    $currentDate = $messageDate;
                }
            
                $messageClass = $message['senderID'] == $senderID ? 'right' : '';
            
                echo '<div class="message ' . $messageClass . '">';
                echo '<img src="' . $currentUserImage . '" alt="">';
                echo '<div class="bubble">';
                echo $message['message'];
                echo '<div class="corner"></div>';
                echo '</div>';
                echo '</div>';
            }
            
            ?>
        </div>

    	
    	
        <div id="sendmessage">
            <input type="text" id="messageInput" placeholder="Send message..." />
            <button id="send" onclick="Message()"></button>
        </div>
    
    </div>        
</div>	

<script>
    var receiverID = null; // Initialize receiverID

    function selectFriend(selectedUserID) {
        receiverID = selectedUserID;
        console.log('Selected Friend ID: ' + receiverID);
        // You can add visual indicators or other actions as needed
    }
</script>    


    <!-- Container for Top Posts and Review Posts -->
    <div class="top-posts-container">
        <!-- Top Posts Section -->
        <div class="top-posts" id="topPosts">
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


    
  <!-- Carousel Container -->
  
  <div class="carousel-container">  
    <button class="prev" onclick="plusSlides(-1)">&#10094;</button>
  
    <?php

    // Verify if the connection is ON if so proceed with the following events 
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
        $postQuery = "(SELECT 'idea' as post_type, IDEAID as POSTID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE, ISANONYMOUS, LEVEL
              FROM IDEAS)
              UNION
              (SELECT 'problem' as post_type, PROBLEMID as POSTID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE, ISANONYMOUS, LEVEL
              FROM PROBLEMS)
              ORDER BY VOTESCORE DESC
              LIMIT 5";


        $postResult = $conn->query($postQuery);

        $foundPosts = false; // This variable helps tracking if any posts are found

        if ($postResult->num_rows > 0) {
            while ($postRow = $postResult->fetch_assoc()) {
                $postType = $postRow['post_type'];
                if ($postType == 'idea') {
                    $post_id = $postRow["POSTID"];
                } elseif ($postType == 'problem' && isset($postRow["POSTID"])) {
                    $post_id = $postRow["POSTID"];
                } else {
                    // Handle the case where the post type is unknown or POSTID is not set
                    $post_id = null;
                }
                $postTitle = $postRow['TITLE'];
                $tagID = $postRow['TAGID'];
                $postContent = $postRow['TEXT'];
                $postImage = $postRow['IMAGE'];
                $userID = $postRow['USERID']; 
                $votescore = $postRow['VOTESCORE'];
                $isAnonymous = $postRow['ISANONYMOUS'];
                $level = $postRow['LEVEL'];


                // Fetch tag information from TAGS table
                $tagQuery = "SELECT TAGS FROM tags WHERE TAGID = '$tagID'";
                $tagResult = $conn->query($tagQuery);

                if ($tagResult->num_rows == 1) {
                    $tagRow = $tagResult->fetch_assoc();
                    $tagName = $tagRow['TAGS'];
                } else {
                    echo "Error fetching tag information.";
                }

                // Display the post for when the user selects to post as nonymous. 
                // This will display a predefined image and username = Anonymous
                // You also cannot check the user profile as it should be for extra security
                echo '<section class="post top-post">';
                echo '<div class="post-header">';
                echo '<div class="user-info">';
                if ($isAnonymous) {
                    echo '<img src="Images/picture.jpg" alt="Anonymous User" class="user-image">';
                    echo '<span class="username">Anonymous</span>';
                } else {

                    // Fetch Username information from USERS table (for the post)
                    $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userID'";
                    $userResult = $conn->query($userQuery);

                    if ($userResult->num_rows == 1) {
                        $userRow = $userResult->fetch_assoc();
                        $userName = $userRow['USERNAME'];
                        $userImage = $userRow['USER_IMAGE'];
                        // Display username and image for non-anonymous posts
                        // In this case you can visit the user´s profile wich id corresponds to the post
                        // You can visit its profile and see its posts but not edit or delet them(ofc)
                        echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
                        echo '<img src="' . $userImage . '" alt="User Profile Picture" class="user-image">';
                        echo '</a>';
                        echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
                        echo '<span class="username">' . $userName . '</span>';
                        echo '</a>';
                    } else {
                        echo "Error fetching User information.";
                    }
                }
                echo '</div>';
                echo '<h2 class="post-title">' . $postTitle . '</h2>';
                echo '<div class="post-tag">';
                echo '<span class="input-tag">' . $tagName . ' | Level: ' . $postRow['LEVEL'] . '</span>';
                echo '<br><br>';
                echo '</div>';
                echo '</div>';
                echo '<a href="ViewPost-html.php?post_id=' . $post_id .  '&post_type=' . $postType . '" class="post-link">';
                echo '<div class="post-content">';
                echo '<p>' . $postContent . '</p>';
                echo '</div>';
                echo '<img src="' . $postImage . '" class="post-image">';
                echo '</a>';
                echo '<div class="post-footer">';
                echo '<div class="post-actions">';
                echo '<div class="vote-container" data-post-id="' . $post_id . '">';
                echo '<form class="vote-form" method="post" action="Php/vote.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<input type="hidden" name="upvote" value="0">';
                echo '<input type="hidden" name="downvote" value="0">';
                echo '<button name="upvote" type="button" class="upvote-button">';
                echo '<i class="uil uil-arrow-up"></i>';
                echo '</button>';
                echo '<span class="post-stats">' . $votescore . '</span>';
                echo '<button name="downvote" type="button" class="downvote-button">';
                echo '<i class="uil uil-arrow-down"></i>';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                echo '<a href="ViewPost-html.php?post_id=' . $post_id . '&post_type=' . $postType . '#comments" class="post-link">';
                echo '<button class="comment-button"><i class="uil uil-comment"></i></button>';
                echo '</a>';
                echo '</div>';
                echo '</div>';
                echo '</section>';
                $foundPosts = true;
            }

            if (!$foundPosts) {
                echo "No posts found.";
            }
        } else {
            echo "No posts found.";
        }
        ?>
        <button class="next" onclick="plusSlides(1)">&#10095;</button>

    
    </div>
    
    <!-- Other Posts Section -->
<div class="other-posts">   


<div class="other-posts-section">
    <div class="other-posts-title">
        <span>Other Posts <i class=""></i></span>
    </div>
</div>
</div>
<button id="toggleLayoutBtn" class="fixed-button"><i class="uil uil-apps"></i></button> 



<div class="scrollable-posts-container">
<?php
// Fetch the remaining posts (excluding the top 5)
$remainingPostQuery = "(SELECT 'idea' as post_type, IDEAID as POSTID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE, ISANONYMOUS, LEVEL
                        FROM IDEAS)
                        UNION
                        (SELECT 'problem' as post_type, PROBLEMID as POSTID, TITLE, TAGID, TEXT, IMAGE, USERID, VOTESCORE, ISANONYMOUS, LEVEL
                        FROM PROBLEMS)
                        ORDER BY VOTESCORE DESC
                        LIMIT 5, 100"; // Starting from the 6th post, limit to 100 posts

$remainingPostResult = $conn->query($remainingPostQuery);

if ($remainingPostResult->num_rows > 0) {
    while ($postRow = $remainingPostResult->fetch_assoc()) {
        $postType = $postRow['post_type'];
                if ($postType == 'idea') {
                    $post_id = $postRow["POSTID"];
                } elseif ($postType == 'problem' && isset($postRow["POSTID"])) {
                    $post_id = $postRow["POSTID"];
                } else {
                    // Handle the case where the post type is unknown or POSTID is not set
                    $post_id = null;
                }
                $postTitle = $postRow['TITLE'];
                $tagID = $postRow['TAGID'];
                $postContent = $postRow['TEXT'];
                $postImage = $postRow['IMAGE'];
                $userID = $postRow['USERID']; 
                $votescore = $postRow['VOTESCORE'];
                $isAnonymous = $postRow['ISANONYMOUS'];
                $level = $postRow['LEVEL'];

                // Fetch tag information from TAGS table
                $tagQuery = "SELECT TAGS FROM tags WHERE TAGID = '$tagID'";
                $tagResult = $conn->query($tagQuery);

                if ($tagResult->num_rows == 1) {
                    $tagRow = $tagResult->fetch_assoc();
                    $tagName = $tagRow['TAGS'];
                } else {
                    echo "Error fetching tag information.";
                }

                // Display the post for when the user selects to post as nonymous. 
                // This will display a predefined image and username = Anonymous
                // You also cannot check the user profile as it should be for extra security
                echo '<section class="other-post">';
                echo '<div class="post-header">';
                echo '<div class="user-info">';
                if ($isAnonymous) {
                    echo '<img src="Images/picture.jpg" alt="Anonymous User" class="user-image">';
                    echo '<span class="username">Anonymous</span>';
                } else {

                    // Fetch Username information from USERS table (for the post)
                    $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM users WHERE USERID = '$userID'";
                    $userResult = $conn->query($userQuery);

                    if ($userResult->num_rows == 1) {
                        $userRow = $userResult->fetch_assoc();
                        $userName = $userRow['USERNAME'];
                        $userImage = $userRow['USER_IMAGE'];
                        // Display username and image for non-anonymous posts
                        // In this case you can visit the user´s profile wich id corresponds to the post
                        // You can visit its profile and see its posts but not edit or delet them(ofc)
                        echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
                        echo '<img src="' . $userImage . '" alt="User Profile Picture" class="user-image">';
                        echo '</a>';
                        echo '<a href="Profile-html.php?user_id=' . $userID . '" class="user-link">';
                        echo '<span class="username">' . $userName . '</span>';
                        echo '</a>';
                    } else {
                        echo "Error fetching User information.";
                    }
                }
                echo '</div>';
                echo '<h2 class="post-title">' . $postTitle . '</h2>';
                echo '<div class="post-tag">';
                echo '<span class="input-tag">' . $tagName . ' | Level: ' . $postRow['LEVEL'] . '</span>';
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
                echo '<form class="vote-form" method="post" action="Php/vote.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<input type="hidden" name="upvote" value="0">';
                echo '<input type="hidden" name="downvote" value="0">';
                echo '<button name="upvote" type="button" class="upvote-button">';
                echo '<i class="uil uil-arrow-up"></i>';
                echo '</button>';
                echo '<span class="post-stats">' . $votescore . '</span>';
                echo '<button name="downvote" type="button" class="downvote-button">';
                echo '<i class="uil uil-arrow-down"></i>';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                echo '<a href="ViewPost-html.php?post_id=' . $post_id . '&post_type=' . $postType . '#comments" class="post-link">';
                echo '<button class="comment-button"><i class="uil uil-comment"></i></button>';
                echo '</a>';
                echo '</div>';
                echo '</div>';
                echo '</section>';
                $foundPosts = true;
            }

            if (!$foundPosts) {
                echo "No posts found.";
            }
        } else {
            echo "No posts found.";
        }

?>
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

// Basic function that allows you to see the tags and preview the tags name(selected)
function selectTag(tagName) {
    console.log("Selected Tag: " + tagName);
    filterPostsByTag(tagName); 
    toggleFilterSection(); 
}


// Function to allow the sliding carousel
var slideIndex = 0;
var topPosts;
var slideInterval;

document.addEventListener("DOMContentLoaded", function() {
    topPosts = document.getElementsByClassName("top-post");
    showTopPosts();
    startSlideInterval();
});

function showTopPosts() {
    var i;
    for (i = 0; i < topPosts.length; i++) {
        topPosts[i].style.display = "none";
    }
    if (slideIndex >= topPosts.length) {
        slideIndex = 0;
    }
    if (slideIndex < 0) {
        slideIndex = topPosts.length - 1;
    }
    topPosts[slideIndex].style.display = "block";
}

function startSlideInterval() {
    slideInterval = setInterval(function() {
        slideIndex++;
        showTopPosts();
    }, 5000); 
}

// Allow the sliding
function nextTopPost() {
    clearInterval(slideInterval); 
    slideIndex++;
    showTopPosts();
    startSlideInterval(); 
}


// Slide Interval resets when slide button is pressed so it doesnt inter
function prevTopPost() {
    clearInterval(slideInterval); 
    slideIndex--;
    showTopPosts();
    startSlideInterval();
}

// Need to comment
function filterPostsByTag(selectedTag) {
    var posts = document.querySelectorAll('.post');
    
    for (var i = 0; i < posts.length; i++) {
        var postTag = posts[i].getElementsByClassName("input-tag")[0];

        if (!selectedTag || postTag.innerText.toUpperCase() === selectedTag.toUpperCase()) {
            posts[i].style.display = "";
        } else {
            posts[i].style.display = "none";
        }
    }
}

// Need to comment
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

    filterPostsByTag(filter);
}


// Event listener to close the popup if you press on the screen(so its less anoying)
document.addEventListener('click', function (event) {
        var customDropdown = document.querySelector(".custom-dropdown");
        var filterButton = document.querySelector(".filter-button");
        
        // This checks if the event pressed is outside the poopup and if so closes it
        if (!customDropdown.contains(event.target) && !filterButton.contains(event.target)) {
            customDropdown.classList.remove("active");
        }
    });

// Function to reload the page without refreshing
function selectTag(tagName) {
       window.location.href = 'iNOVAtion-html.php?tag=' + encodeURIComponent(tagName);
}  

// Function to search for the title of the posts in the page as well as the usernames (without refreshing)
function searchPosts() {
    var input, filter, posts, i, postTitle, username, postContent;
    input = document.getElementById("postSearch");
    filter = input.value.toUpperCase();
    posts = document.querySelectorAll('.post');

    for (i = 0; i < posts.length; i++) {
        postTitle = posts[i].getElementsByClassName("post-title")[0];
        postContent = posts[i].getElementsByClassName("post-content")[0];
        username = posts[i].getElementsByClassName("username")[0];

        // This helps on the search making all the irregular text readable in case is either in upper or lower text
        if (postTitle.innerText.toUpperCase().indexOf(filter) > -1 ||
            postContent.innerText.toUpperCase().indexOf(filter) > -1 ||
            username.innerText.toUpperCase().indexOf(filter) > -1) {
            posts[i].style.display = "";
        } else {
            posts[i].style.display = "none";
        }
    }
}


// Function to update the votescore in real time using ajax     
$(document).ready(function() {
  $(".vote-form button").on("click", function(e) {
    e.preventDefault();

    var container = $(this).closest(".vote-container");
    var form = container.find(".vote-form");

    // Determine whether it's an upvote or downvote button
    var isUpvote = $(this).hasClass("upvote-button");

    // Set the values accordingly
    form.find('input[name="upvote"]').val(isUpvote ? 1 : 0);
    form.find('input[name="downvote"]').val(isUpvote ? 0 : 1); 


    $.ajax({
      type: form.attr("method"),
      url: form.attr("action"),
      data: form.serialize(), // Use form.serialize() to send the form data
      dataType: "json",
      success: function(response) {
        if (response.hasOwnProperty('voteCount')) {
          // Update the vote count on success
          container.find(".post-stats").text(response.voteCount);
        } else {
          console.error('Invalid response format:', response);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("You have already voted for this post. Thank you for your collaboration!", textStatus, errorThrown);
        alert("You have already voted for this post. Thank you for your collaboration!");
      }
    });
  });
});
 
</script>


<script>

function toggleChat() {
    var chatbox = document.getElementById("chatbox");
    if (chatbox.style.display === "block") {
        chatbox.style.display = "none";
    } else {
        chatbox.style.display = "block";
    }
}

function closeChat() {
        document.getElementById('chatbox').style.display = 'none';	               
    }

document.addEventListener('DOMContentLoaded', function () {
const toggleLayoutBtn = document.getElementById('toggleLayoutBtn');
const postsContainer = document.querySelector('.scrollable-posts-container');

toggleLayoutBtn.addEventListener('click', function () {
postsContainer.classList.toggle('column-layout');

// Change the icon based on the layout state
const isColumnLayout = postsContainer.classList.contains('column-layout');
const icon = isColumnLayout ? "uil-list-ul" : "uil-apps";

// Update the icon class
toggleLayoutBtn.querySelector('i').className = "uil " + icon;
    });
});



$(document).ready(function(){
	
    var preloadbg = document.createElement("img");
    preloadbg.src = "https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/timeline1.png";
    
      $("#searchfield").focus(function(){
          if($(this).val() == "Search contacts..."){
              $(this).val("");
          }
      });
      $("#searchfield").focusout(function(){
          if($(this).val() == ""){
              $(this).val("Search contacts...");
              
          }
      });
      
      $("#sendmessage input").focus(function(){
          if($(this).val() == "Send message..."){
              $(this).val("");
          }
      });
      $("#sendmessage input").focusout(function(){
          if($(this).val() == ""){
              $(this).val("Send message...");
              
          }
      });
          
      
      $(".friend").each(function(){		
          $(this).click(function(){
              var childOffset = $(this).offset();
              var parentOffset = $(this).parent().parent().offset();
              var childTop = childOffset.top - parentOffset.top;
              var clone = $(this).find('img').eq(0).clone();
              var top = childTop+12+"px";
              
              $(clone).css({'top': top}).addClass("floatingImg").appendTo("#chatbox");									
              
              setTimeout(function(){$("#profile p").addClass("animate");$("#profile").addClass("animate");}, 100);
              setTimeout(function(){
                  $("#chat-messages").addClass("animate");
                  $('.cx, .cy').addClass('s1');
                  setTimeout(function(){$('.cx, .cy').addClass('s2');}, 100);
                  setTimeout(function(){$('.cx, .cy').addClass('s3');}, 200);			
              }, 150);														
              
              $('.floatingImg').animate({
                  'width': "68px",
                  'left':'108px',
                  'top':'20px'
              }, 200);
              
              var name = $(this).find("p strong").html();
              var email = $(this).find("p span").html();														
              $("#profile p").html(name);
              $("#profile span").html(email);			
              
              $(".message").not(".right").find("img").attr("src", $(clone).attr("src"));									
              $('#friendslist').fadeOut();
              $('#chatview').fadeIn();
          
              
              $('#close').unbind("click").click(function(){				
                  $("#chat-messages, #profile, #profile p").removeClass("animate");
                  $('.cx, .cy').removeClass("s1 s2 s3");
                  $('.floatingImg').animate({
                      'width': "40px",
                      'top':top,
                      'left': '12px'
                  }, 200, function(){$('.floatingImg').remove()});				
                  
                  setTimeout(function(){
                      $('#chatview').fadeOut();
                      $('#friendslist').fadeIn();				
                  }, 50);
              });
              
          });
      });			
  });
</script>

</body>
</html>
