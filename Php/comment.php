<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "psi");

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
        // Validate and sanitize the comment input
        $commentText = mysqli_real_escape_string($conn, $_POST['input-comment']);
        // Check if the comment text is not empty
        if (!empty($commentText)) {
            // Insert the comment into the COMMENTS table
            $insertCommentQuery = "INSERT INTO COMMENTS (IDEAID, USERID, CHARACTERS, CREATEDON, VOTESCORE, COMMENT_TYPE)
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

// Redirect back to the previous page or wherever you want
header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>
