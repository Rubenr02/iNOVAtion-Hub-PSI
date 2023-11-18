<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovation");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process post form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post-button'])) {
    // Retrieve other necessary information here...

    $postTitle = $_POST['post-title'];
    $postText = $_POST['post-text'];
    $postTag = $_POST['post-tag'];
    $postType = $_POST['post-type'];
    $postImage = $_FILES['post-image']['name']; // File name
    $postPDF = $_FILES['post-pdf']['name']; // File name
    $isAnonymous = isset($_POST['hide-real-name']) ? 1 : 0;

    // Validate and sanitize user input here...

    // Check if the user is logged in and retrieve the user ID
    if (isset($_SESSION['USERID'])) {
        $userid = $_SESSION['USERID'];

        // Handle image upload
        $targetDirectory = "uploads/";
        $targetImage = $targetDirectory . basename($postImage);
        move_uploaded_file($_FILES['post-image']['tmp_name'], $targetImage);

        // Handle PDF upload
        $targetPDF = $targetDirectory . basename($postPDF);
        move_uploaded_file($_FILES['post-pdf']['tmp_name'], $targetPDF);

        // Get TAGID from TAGS table based on the selected tag
        $tagQuery = "SELECT TAGID FROM TAGS WHERE TAGS = '$postTag'";
        $tagResult = $conn->query($tagQuery);

        if ($tagResult->num_rows == 1) {
            $tagRow = $tagResult->fetch_assoc();
            $tagID = $tagRow['TAGID'];

            // Perform SQL query to insert a new post
            $sql = "INSERT INTO POSTS (USERID, TAGID, TITLE, DESCRIPTION, CONTENT, IMAGE, PDF_FILE, CREATEDON, IS_ANONYMOUS) 
                    VALUES ('$userid', '$tagID', '$postTitle', '$postText', '$postType', '$targetImage', '$targetPDF', NOW(), '$isAnonymous')";

            if ($conn->query($sql) === TRUE) {
                header("Location: /PSI/Loading-html.html");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: Tag not found.";
        }
    } else {
        echo "Error: User not logged in.";
    }
}
?>
