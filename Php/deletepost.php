<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

// Check for connection errors
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Process post form for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-button'])) {

    // Check if the user is logged in
    if (isset($_SESSION['USERID'])) {
        $userid = $_SESSION['USERID'];

        // Get the post ID and post type from the form
        $postIDToDelete = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        $postType = isset($_POST['post_type']) ? $_POST['post_type'] : '';

        // Debug: Output post ID and type
        echo "Post ID: $postIDToDelete<br>";
        echo "Post Type: $postType<br>";

        // Ensure the post ID and type are not empty
        if (!empty($postIDToDelete) && !empty($postType)) {

            // Debug: Output table and column names
            echo "Table Name: $tableName<br>";
            echo "Column Name: $columnName<br>";

            // Determine the table (IDEAS or PROBLEMS) based on the post type
            $tableName = ($postType == 'idea') ? 'IDEAS' : 'PROBLEMS';
            $columnName = ($postType == 'idea') ? 'IDEAID' : 'PROBLEMID';

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
            echo "Error: Invalid post ID or post type.";
        }
    } else {
        echo "Error: User not logged in.";
    }
}
?>
