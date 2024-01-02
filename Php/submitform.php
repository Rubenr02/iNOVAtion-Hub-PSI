<?php

// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process post form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_SESSION['USERID'];
    $post_id = isset($_POST['post_id']) ? trim($_POST['post_id']) : null;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post-button'])) {
        $postText = mysqli_real_escape_string($conn, $_POST['post-text']);
        $postText2 = mysqli_real_escape_string($conn, $_POST['post-text-2']);
        $postIdeaID = $post_id;

        // Image upload directory (since this is a localhost server)
        $targetDirectory = "Images/";

        // PDF upload directory
        $pdfDirectory = "PDFs/";

        // Create the target directories (in case they do not exist)
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0755, true);
        }

        // If there were no errors uploading the files
        if (isset($_FILES["post-image"]) && $_FILES["post-image"]["error"] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["post-image"]["name"];
            $filetype = $_FILES["post-image"]["type"];
            $filesize = $_FILES["post-image"]["size"];

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
            if (move_uploaded_file($_FILES["post-image"]["tmp_name"], $targetImage)) {

                // Defining the table where the forms will be inserted
                $tableName = 'FORMS';
                $checkIdeaID = "SELECT * FROM ideas WHERE IDEAID = '$postIdeaID'";
                $result = $conn->query($checkIdeaID);

                if ($result->num_rows > 0) {
                    // The IDEAID exists, proceed with the insertion
                    $sql = "INSERT INTO FORMS (USERID, IDEAID, TEXT1, TEXT2, IMAGE, FILE, CREATEDON, ACCEPTED) 
                            VALUES ('$userid', '$postIdeaID', '$postText', '$postText2', '$targetImage', '', NOW(), 0)";

                    if ($conn->query($sql) === TRUE) {
                        header("Location: /PSI/Loading-html.html");
                        exit();
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                    if (isset($_FILES["post-pdf"]) && $_FILES["post-pdf"]["error"] == 0) {
                        // Handle PDF file upload
                        $allowedPdf = array("pdf" => "application/pdf");
                        $pdfFilename = $_FILES["post-pdf"]["name"];
                        $pdfFiletype = $_FILES["post-pdf"]["type"];
                        $pdfFilesize = $_FILES["post-pdf"]["size"];

                        // Validating the PDF extension
                        $pdfExt = pathinfo($pdfFilename, PATHINFO_EXTENSION);
                        if (!array_key_exists($pdfExt, $allowedPdf)) {
                            die("Error: Please select a valid PDF file.");
                        }

                        // Setting a 10MB maximum for the PDF file upload
                        $maxPdfSize = 10 * 1024 * 1024;
                        if ($pdfFilesize > $maxPdfSize) {
                            die("Error: PDF file size is larger than the allowed limit.");
                        }

                        // Move the uploaded PDF file to the target directory
                        $targetPdf = $pdfDirectory . basename($pdfFilename);
                        if (move_uploaded_file($_FILES["post-pdf"]["tmp_name"], $targetPdf)) {
                            // Proceed with the form submission
                            $sql = "INSERT INTO FORMS (USERID, IDEAID, TEXT1, TEXT2, IMAGE, FILE, CREATEDON, ACCEPTED) 
                                    VALUES ('$userid', '$postIdeaID', '$postText', '$postText2', '$targetImage', '$targetPdf', NOW(), 0)";

                            if ($conn->query($sql) === TRUE) {
                                header("Location: /PSI/Loading-html.html");
                                exit();
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                        } else {
                            echo "Error: There was a problem uploading your PDF file. Please try again.";
                        }
                    } else {
                        echo "Error: Please select a PDF file.";
                    }
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