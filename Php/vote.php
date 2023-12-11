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
    $checkVoteResultIdeas = null;
    $checkVoteResultProblems = null;

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
    $updateVoteQueryIdeas = "UPDATE IDEAS SET VOTESCORE = VOTESCORE + ? WHERE IDEAID = ?";
    $updateVoteQueryProblems = "UPDATE PROBLEMS SET VOTESCORE = VOTESCORE + ? WHERE PROBLEMID = ?";

    // Initialize the statements outside the conditional blocks
    $updateVoteStmt = null;
    $insertVoteStmt = null;

    if ($checkVoteResultIdeas->num_rows == 0 && $checkVoteResultProblems->num_rows == 0) {
        if (isset($_POST['upvote']) || isset($_POST['downvote'])) {
            $upvote = isset($_POST['upvote']) ? $_POST['upvote'] : 0;
            $downvote = isset($_POST['downvote']) ? $_POST['downvote'] : 0;
            $votescore = $upvote - $downvote;
        } else {
            // Handle the case where neither upvote nor downvote is set
            echo "Invalid request: Missing upvote or downvote";
            exit();
        }

        // Determine the post type
        $postTypeQuery = "SELECT post_type FROM (SELECT 'idea' as post_type, IDEAID FROM IDEAS WHERE IDEAID = ?
                          UNION
                          SELECT 'problem' as post_type, PROBLEMID FROM PROBLEMS WHERE PROBLEMID = ?) AS post_types";
        $postTypeStmt = $conn->prepare($postTypeQuery);
        $postTypeStmt->bind_param("ii", $post_id, $post_id);
        $postTypeStmt->execute();
        $postTypeResult = $postTypeStmt->get_result();

        if ($postTypeResult->num_rows == 1) {
            $postTypeRow = $postTypeResult->fetch_assoc();
            $postType = $postTypeRow['post_type'];

            // Execute the appropriate update query based on the post type
            if ($postType == 'idea') {
                $updateVoteStmt = $conn->prepare($updateVoteQueryIdeas);
                $updateVoteStmt->bind_param("ii", $votescore, $post_id);
            } elseif ($postType == 'problem') {
                $updateVoteStmt = $conn->prepare($updateVoteQueryProblems);
                $updateVoteStmt->bind_param("ii", $votescore, $post_id);
            } else {
                // Handle the case where the post type is unknown
                echo "Invalid request: Unknown post type";
                exit();
            }

            // Execute the update query
            $updateVoteStmt->execute();

            // Insert the vote record
            $insertVoteQuery = "INSERT INTO VOTES (USERID, " . $postType . "ID, UPVOTE, DOWNVOTE, CREATEDON) VALUES (?, ?, ?, ?, NOW())";
            $insertVoteStmt = $conn->prepare($insertVoteQuery);
            $insertVoteStmt->bind_param("iiii", $userid, $post_id, $upvote, $downvote);

            // Execute the insert query
            $insertVoteStmt->execute();

            // Fetch the updated votescore
            $getVotescoreQuery = "SELECT VOTESCORE FROM " . ($postType == 'idea' ? 'IDEAS' : 'PROBLEMS') . " WHERE " . ($postType == 'idea' ? 'IDEAID' : 'PROBLEMID') . " = ?";
            $getVotescoreStmt = $conn->prepare($getVotescoreQuery);
            $getVotescoreStmt->bind_param("i", $post_id);
            $getVotescoreStmt->execute();
            $getVotescoreResult = $getVotescoreStmt->get_result();

            if ($getVotescoreResult->num_rows == 1) {
                $getVotescoreRow = $getVotescoreResult->fetch_assoc();
                $votescore = $getVotescoreRow['VOTESCORE'];

                // Send the updated votescore as part of the JSON response
                header('Content-Type: application/json');
                echo json_encode(['voteCount' => $votescore]);
                exit;
            } else {
                echo "Error fetching updated votescore.";
            }

            // Close the prepared statement
            $getVotescoreStmt->close();
        } else {
            // Handle the case where the post type is not found
            echo "Invalid request: Post type not found";
            exit();
        }
    } else {
        // User has already voted
        echo "User has already voted for this post.";
    }

    // Close the prepared statements
    if ($updateVoteStmt != null) {
        $updateVoteStmt->close();
    }
    if ($insertVoteStmt != null) {
        $insertVoteStmt->close();
    }
} else {
    // Handle other cases (e.g., user not logged in, invalid request)
    echo "Invalid request or user not logged in.";
}

// Close the database connection
$conn->close();
?>
