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

// generate_code.php

// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Check if the user is logged in
if (isset($_SESSION['USERID'])) {
    $userid = $_SESSION['USERID'];

    // Generate a random code (you can adjust the length and characters as needed)
    $randomCode = substr(md5(uniqid(rand(), true)), 0, 8);

    // Insert the code into the CODES table
    $insertCodeQuery = "INSERT INTO CODES (USERID, CODE, CREATED_ON, TYPE_OF_USER) VALUES ('$userid', '$randomCode', NOW(), 2)";
    if ($conn->query($insertCodeQuery) === TRUE) {
        // Fetch the user's email
        $getUserEmailQuery = "SELECT EMAIL FROM USERS WHERE USERID = '$userid'";
        $userEmailResult = $conn->query($getUserEmailQuery);

        if ($userEmailResult->num_rows == 1) {
            $userEmailRow = $userEmailResult->fetch_assoc();
            $userEmail = $userEmailRow['EMAIL'];

            // Send an email with the code
            $subject = "Reviewer Code";
            $message = "Here is your reviewer's code: $randomCode. 
                        Insert it in your profile to become a reviewer";
            $emailResult = sendEmail($userEmail, $subject, $message);

            if ($emailResult === true) {
                echo "Code generated successfully and sent to the user via email.";
            } else {
                echo "Error sending email: $emailResult";
            }
        } else {
            echo "Error fetching user email.";
        }
    } else {
        echo "Error inserting code into the database: " . $conn->error;
    }
} else {
    echo "User not logged in.";
}

// Close the database connection
$conn->close();
?>
