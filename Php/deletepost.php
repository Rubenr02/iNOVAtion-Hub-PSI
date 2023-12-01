<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "psi");

// Check for connection errors
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Process post form for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-button'])) {

    // Check if the user is logged in
    if (isset($_SESSION['USERID'])) {
        $userid = $_SESSION['USERID'];

        // Get the post ID from the form
        $postIDToDelete = isset($_POST['post_id']) ? $_POST['post_id'] : '';

        // Ensure the post ID is not empty
        if (!empty($postIDToDelete)) {

            // Determine the table (IDEAS or PROBLEMS) based on the post ID
            $tableQuery = "SELECT TABLE_NAME, COLUMN_NAME FROM (
                                SELECT 'IDEAS' AS TABLE_NAME, IDEAID AS COLUMN_NAME FROM IDEAS
                                UNION ALL
                                SELECT 'PROBLEMS' AS TABLE_NAME, PROBLEMID AS COLUMN_NAME FROM PROBLEMS
                            ) AS AllPosts
                            WHERE COLUMN_NAME = '$postIDToDelete'";

            $tableResult = $conn->query($tableQuery);

            if ($tableResult->num_rows == 1) {
                $tableRow = $tableResult->fetch_assoc();
                $tableName = $tableRow['TABLE_NAME'];
                $columnName = $tableRow['COLUMN_NAME'];

                
                // Perform SQL query to delete the post (use prepared statements to prevent SQL injection)
                $deleteQuery = $conn->prepare("DELETE FROM $tableName WHERE $columnName = ? AND USERID = ?");
                $deleteQuery->bind_param("ss", $postIDToDelete, $userid);

                if ($deleteQuery->execute()) {
                    header("Location: /PSI/Loading-html.html");
                        exit();
                } else {
                    echo "Error deleting post: " . $conn->error;
                }

                $deleteQuery->close();
            } else {
                echo "Error: Invalid post ID.";
            }
        } else {
            echo "Error: Invalid post ID.";
        }
    } else {
        echo "Error: User not logged in.";
    }
}
?>
