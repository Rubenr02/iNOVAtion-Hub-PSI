<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_SESSION['USERID'];
    $post_id = trim($_POST['post_id']);
    
    // Check if the comment form is submitted
    if (isset($_POST['submit-comment'])) {
        $commentText = mysqli_real_escape_string($conn, $_POST['input-comment']);
        
        // Check if the comment text is not empty
        if (!empty($commentText)) {
            // Determine the post type (idea or problem)
<<<<<<< HEAD
            $postType = isset($_POST['post_type']) ? $_POST['post_type'] : (isset($_GET['post_type']) ? $_GET['post_type'] : '');

=======
            $postType = isset($_POST['post_type']) ? $_POST['post_type'] : '';
            
>>>>>>> 1d0528f4e4cbd8d65937d13347466f23d2508efa
            // Identifying the tables as IDEAS or PROBLEMS
            if ($postType === 'idea') {
                $postTable = 'IDEAS';
                $postIdColumn = 'IDEAID';
            } elseif ($postType === 'problem') {
                $postTable = 'PROBLEMS';
                $postIdColumn = 'PROBLEMID';
            } else {
<<<<<<< HEAD
                echo "Invalid post type: " . htmlspecialchars($postType);
=======
                echo "Invalid post type.";
>>>>>>> 1d0528f4e4cbd8d65937d13347466f23d2508efa
                exit; 
            }

            // Insert the comment into the COMMENTS table
            $insertCommentQuery = "INSERT INTO COMMENTS ($postIdColumn, USERID, CHARACTERS, CREATEDON, VOTESCORE, COMMENT_TYPE)
                                   VALUES ('$post_id', '$userid', '$commentText', NOW(), 0, 1)";

            if ($conn->query($insertCommentQuery) === TRUE) {
                echo "Comment posted successfully!";
            } else {
                echo "Error posting comment: " . $conn->error;
            }
        } else {
            echo "Comment cannot be empty!";
        }
    }
}

// Redirect back to the previous page 
header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>