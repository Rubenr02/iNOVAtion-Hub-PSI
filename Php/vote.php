<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "psi");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['USERID'])) {
    $userid = $_SESSION['USERID'];
    $post_id = trim($_POST['post_id']);

    // Check if the user has already voted for this post
    $checkVoteQuery = "SELECT * FROM VOTES WHERE USERID = ? AND IDEAID = ?";
    $checkVoteStmt = $conn->prepare($checkVoteQuery);
    $checkVoteStmt->bind_param("ii", $userid, $post_id);
    $checkVoteStmt->execute();
    $checkVoteResult = $checkVoteStmt->get_result();
    
    $checkIdeaQuery = "SELECT * FROM IDEAS WHERE IDEAID = ?";
    $checkIdeaStmt = $conn->prepare($checkIdeaQuery);
    $checkIdeaStmt->bind_param("i", $post_id);
    $checkIdeaStmt->execute();
    $checkIdeaResult = $checkIdeaStmt->get_result();

    // Initialize the statements outside the conditional blocks
    $updateVoteStmt = null;
    $insertVoteStmt = null;

    if ($checkIdeaResult->num_rows == 1) {
        // IDEAID exists, proceed with the vote
        if ($checkVoteResult->num_rows == 0) {
            if (isset($_POST['upvote'])) {
                // Update VOTESCORE for upvote
                $updateVoteQuery = "UPDATE IDEAS SET VOTESCORE = VOTESCORE + 1 WHERE IDEAID = ?";
                $updateVoteStmt = $conn->prepare($updateVoteQuery);
                $updateVoteStmt->bind_param("i", $post_id);
                $updateVoteStmt->execute();
            } elseif (isset($_POST['downvote'])) {
                // Update VOTESCORE for downvote
                $updateVoteQuery = "UPDATE IDEAS SET VOTESCORE = VOTESCORE - 1 WHERE IDEAID = ?";
                $updateVoteStmt = $conn->prepare($updateVoteQuery);
                $updateVoteStmt->bind_param("i", $post_id);
                $updateVoteStmt->execute();
            }

            // Insert the vote record
            $insertVoteQuery = "INSERT INTO VOTES (USERID, IDEAID, CREATEDON) VALUES (?, ?, NOW())";
            $insertVoteStmt = $conn->prepare($insertVoteQuery);
            $insertVoteStmt->bind_param("ii", $userid, $post_id);
            $insertVoteStmt->execute();
        } else {
            // User has already voted
            echo "You have already voted for this post.";
        }
    } else {
        // IDEAID doesn't exist in IDEAS table
        echo "Invalid IDEAID.";
    }

    // Close the prepared statements if they are not null
    if ($updateVoteStmt != null) {
        $updateVoteStmt->close();
    }
    if ($insertVoteStmt != null) {
        $insertVoteStmt->close();
    }
}

// Redirect back to the previous page or wherever you want
header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>
