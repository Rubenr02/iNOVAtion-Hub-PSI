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
    if (isset($_POST['submit-comment'])) {
        $commentText = mysqli_real_escape_string($conn, $_POST['input-comment']);
        if (!empty($commentText)) {
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

// Redirect back to the previous page 
header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>
