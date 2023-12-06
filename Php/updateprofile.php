<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process profile changes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $birthDate = $_POST['birth-date'];
    $occupation = $_POST['occupation'];

    if (isset($_SESSION['USERID'])) {
        $userid = $_SESSION['USERID'];
    }

    // Check if the image file is selected
    if (isset($_FILES["user-image"]) && $_FILES["user-image"]["error"] == 0) {
        // Image upload directory
        $targetDirectory = "Images/";

        // Create the target directory if it does not exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["user-image"]["name"];
        $filetype = $_FILES["user-image"]["type"];
        $filesize = $_FILES["user-image"]["size"];

        // Validating the extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Please select a valid image file format.");
        }

        // Setting a 10MB maximum for the files upload
        $maxsize = 10 * 1024 * 1024;
        if ($filesize > $maxsize) {
            die("Error: Image size is larger than the allowed limit.");
        }

        // Move the uploaded image to the target directory
        $targetImage = $targetDirectory . basename($filename);
        if (move_uploaded_file($_FILES["user-image"]["tmp_name"], $targetImage)) {

            // Hash the password securely
            $hashedPassword = md5($password);

            // Perform SQL query to update user info
            $sql = "UPDATE USERS
                    SET EMAIL='$email', USERNAME='$username', PASSWORD='$hashedPassword', FIRSTNAME='$firstName', LASTNAME='$lastName', DATE_OF_BIRTH='$birthDate', OCCUPATION='$occupation', IMAGE='$targetImage'  
                    WHERE USERID='$userid'";

            if ($conn->query($sql) === TRUE) {
                // Store user information in the session so the information can be fetched in other pages
                $_SESSION['USERID'] = $userid;
                $_SESSION['USERNAME'] = $username;

                header("Location: /PSI/Loading-html.html");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: There was a problem uploading your image. Please try again.";
        }
    } else {
        echo "Error: Please select an image.";
    }
}


?>
