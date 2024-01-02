<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer autoloader
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Function to send an email
function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'inovationhubcodes@gmail.com';
    $mail->Password = 'gvxn pcxu rihw hhre';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('inovationhubcodes@gmail.com');
    $mail->addAddress($to);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $imagePath = 'src/iNOVAtion Hub.png'; 
    $cid = $mail->addEmbeddedImage($imagePath, 'logo', 'logo.png'); // 

    // Embed the image in the HTML body
    $mail->Body = "<html><body><p>$message</p><img src='cid:$cid'></body></html>";

    try {
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process review form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_SESSION['USERID'];
    $post_id = trim($_POST['post_id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post-button'])) {
        // Retrieve the USERID from the IDEAS table based on IDEAID
        $getUserSql = "SELECT USERID FROM IDEAS WHERE IDEAID = '$post_id'";
        $getUserResult = $conn->query($getUserSql);

        if ($getUserResult && $getUserResult->num_rows > 0) {
            $row = $getUserResult->fetch_assoc();
            $postUserId = $row['USERID'];

            // Insert review data into REVIEWS table
            $postText = mysqli_real_escape_string($conn, $_POST['post-text']);
            $evaluation = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

            $sql = "INSERT INTO REVIEWS (IDEAID, USERID, CREATEDON, EVALUATION, NOTES) 
                    VALUES ('$post_id', '$userid', NOW(), '$evaluation', '$postText')";

            if ($conn->query($sql) === TRUE) {
                // Update the level of the idea based on the evaluation
                $newLevel = calculateNewLevel($evaluation);

                // Update the LEVEL in the IDEAS table
                $updateLevelSql = "UPDATE IDEAS SET LEVEL = LEVEL + '$newLevel' WHERE IDEAID = '$post_id'";

                if ($conn->query($updateLevelSql) === TRUE) {
                    // Update the ACCEPTED column in the FORMS table
                    $updateAcceptedSql = "UPDATE FORMS SET ACCEPTED = 1 WHERE IDEAID = '$post_id'";

                    if ($conn->query($updateAcceptedSql) === TRUE) {
                        // Delete the corresponding row from FORMS table
                        $deleteFormSql = "DELETE FROM FORMS WHERE IDEAID = '$post_id'";
                        
                        if ($conn->query($deleteFormSql) === TRUE) {
                            // Fetch the user's email
                            $getUserEmailQuery = "SELECT EMAIL FROM USERS WHERE USERID = '$postUserId'";
                            $userEmailResult = $conn->query($getUserEmailQuery);

                            if ($userEmailResult->num_rows == 1) {
                                $row = $userEmailResult->fetch_assoc();
                                $userEmail = $row['EMAIL'];
                                $notes = $row['NOTES'];
                            
                                // Determine the approval status based on the rating
                                $approvalStatus = ($evaluation >= 3) ? "approved" : "not approved";
                            
                                // Send an email with the review details
                                $subject = "Your post has been reviewed";
                                $message = "<p>Rating: $evaluation</p>
                                            <p>Notes: $postText</p>
                                            <p>Your post has been $approvalStatus. Thank you for contributing to iNOVAtion Hub!</p>";
                                sendEmail($userEmail, $subject, $message);

                                header("Location: /PSI/Loading-html.html");
                                exit();
                            } else {
                                echo "Error fetching user email: " . $getUserEmailQuery . "<br>" . $conn->error;
                            }  
                            } else {
                                echo "Error fetching user email: " . $getUserEmailQuery . "<br>" . $conn->error;
                            }                            
                    } else {
                        echo "Error updating form acceptance: " . $updateAcceptedSql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error updating idea level: " . $updateLevelSql . "<br>" . $conn->error;
                }
            } else {
                echo "Error inserting review data: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error retrieving USERID: " . $getUserSql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: User not logged in.";
    }
}

// Function to calculate the new level based on the evaluation (Example logic)
function calculateNewLevel($evaluation) {
    // Adjust this logic based on your requirements
    $newLevel = 0; // Default level

    if ($evaluation >= 3) {
        $newLevel += 1;
    }

    return $newLevel;
}

// Close the database connection
$conn->close();
?>
