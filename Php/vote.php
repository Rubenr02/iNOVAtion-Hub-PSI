<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['USERID'])) {
    $userid = $_SESSION['USERID'];
    $post_id = trim($_POST['post_id']);

    // Initialize $checkVoteResult before using it
    $checkVoteResult = null;

    // For Ideas
    $checkVoteQueryIdeas = "SELECT * FROM VOTES WHERE USERID = ? AND IDEAID = ?";
    $checkVoteStmtIdeas = $conn->prepare($checkVoteQueryIdeas);
    $checkVoteStmtIdeas->bind_param("ii", $userid, $post_id);
    $checkVoteStmtIdeas->execute();
    $checkVoteResultIdeas = $checkVoteStmtIdeas->get_result();

    // For Problems
    $checkVoteQueryProblems = "SELECT * FROM VOTES WHERE USERID = ? AND PROBLEMID = ?";
    $checkVoteStmtProblems = $conn->prepare($checkVoteQueryProblems);
    $checkVoteStmtProblems->bind_param("ii", $userid, $post_id);
    $checkVoteStmtProblems->execute();
    $checkVoteResultProblems = $checkVoteStmtProblems->get_result();

    // Use correct variable names in update queries
    $updateVoteQueryIdeas = "UPDATE IDEAS SET VOTESCORE = VOTESCORE + 1 WHERE IDEAID = ?";
    $updateVoteQueryProblems = "UPDATE PROBLEMS SET VOTESCORE = VOTESCORE + 1 WHERE PROBLEMID = ?";

    // Initialize the statements outside the conditional blocks
    $updateVoteStmt = null;
    $insertVoteStmt = null;

    if ($checkIdeaResult->num_rows == 1) {
        // IDEAID exists, proceed with the vote
        if ($checkVoteResultIdeas->num_rows == 0) {
            if (isset($_POST['upvote'])) {
                // Update VOTESCORE for upvote
                $updateVoteStmt = $conn->prepare($updateVoteQueryIdeas);
                $updateVoteStmt->bind_param("i", $post_id);
                $updateVoteStmt->execute();
            } elseif (isset($_POST['downvote'])) {
                // Update VOTESCORE for downvote
                $updateVoteStmt = $conn->prepare($updateVoteQueryIdeas);
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
            echo "User has already voted for this idea.";
        }

    } elseif ($checkProblemResult->num_rows == 1) {
        if ($checkVoteResultProblems->num_rows == 0) {
            if (isset($_POST['upvote'])) {
                // Update VOTESCORE for upvote
                $updateVoteStmt = $conn->prepare($updateVoteQueryProblems);
                $updateVoteStmt->bind_param("i", $post_id);
                $updateVoteStmt->execute();
            } elseif (isset($_POST['downvote'])) {
                // Update VOTESCORE for downvote
                $updateVoteStmt = $conn->prepare($updateVoteQueryProblems);
                $updateVoteStmt->bind_param("i", $post_id);
                $updateVoteStmt->execute();
            }

            // Insert the vote record
            $insertVoteQuery = "INSERT INTO VOTES (USERID, PROBLEMID, CREATEDON) VALUES (?, ?, NOW())";
            $insertVoteStmt = $conn->prepare($insertVoteQuery);
            $insertVoteStmt->bind_param("ii", $userid, $post_id);
            $insertVoteStmt->execute();
        } else {
            // User has already voted
            echo "User has already voted for this problem.";
        }
    } else {
        // IDEAID or PROBLEMID doesn't exist in IDEAS or PROBLEMS table
        echo "Invalid IDEAID or PROBLEMID.";
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
