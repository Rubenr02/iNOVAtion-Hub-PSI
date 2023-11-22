<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "psi");

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
    $postPDF = $_FILES['post-pdf']['name'];
    $isAnonymous = isset($_POST['hide-real-name']) ? 1 : 0;

    // Validate and sanitize user input here...

    // Check if the user is logged in and retrieve the user ID
    if (isset($_SESSION['USERID'])) {
        $userid = $_SESSION['USERID'];

        // Handle image upload
        $targetDirectory = "Images/";

        // Create the target directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Check if file was uploaded without errors
        if (isset($_FILES["post-image"]) && $_FILES["post-image"]["error"] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["post-image"]["name"];
            $filetype = $_FILES["post-image"]["type"];
            $filesize = $_FILES["post-image"]["size"];

            // Validate file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                die("Error: Please select a valid image file format.");
            }

            // Validate file size - 10MB maximum
            $maxsize = 10 * 1024 * 1024;
            if ($filesize > $maxsize) {
                die("Error: Image size is larger than the allowed limit.");
            }

            // Move the uploaded image to the target directory
            $targetImage = $targetDirectory . basename($filename);
            if (move_uploaded_file($_FILES["post-image"]["tmp_name"], $targetImage)) {
                // Get TAGID from TAGS table based on the selected tag
                $tagQuery = "SELECT TAGID FROM TAGS WHERE TAGS = '$postTag'";
                $tagResult = $conn->query($tagQuery);

                if ($tagResult->num_rows == 1) {
                    $tagRow = $tagResult->fetch_assoc();
                    $tagID = $tagRow['TAGID'];

                    // Determine the table based on $postType
                    $tableName = ($postType == 'idea') ? 'IDEAS' : 'PROBLEMS';

                    // Perform SQL query to insert a new post
                    $sql = "INSERT INTO $tableName (USERID, TAGID, TITLE, TEXT, IMAGE, FILE, CREATEDON, ISANONYMOUS) 
                            VALUES ('$userid', '$tagID', '$postTitle', '$postText', '$targetImage', '$postPDF', NOW(), '$isAnonymous')";

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
                echo "Error: There was a problem uploading your image. Please try again.";
            }
        } else {
            echo "Error: Please select an image.";
        }
    } else {
        echo "Error: User not logged in.";
    }
}
?>
