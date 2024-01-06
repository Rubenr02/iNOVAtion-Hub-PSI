<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $postID = $_POST['post_id'];
    $postType = $_POST['post_type'];

    // Perform necessary validation and sanitization

    // Update your database with the report information
    $reportQuery = "";

    if ($postType === 'idea') {
        $reportQuery = "UPDATE IDEAS SET REPORT = 1 WHERE IDEAID = '$postID'";
    } elseif ($postType === 'problem') {
        $reportQuery = "UPDATE PROBLEMS SET REPORT = 1 WHERE PROBLEMID = '$postID'";
    }

    if (!empty($reportQuery)) {
        $reportResult = $conn->query($reportQuery);

        if ($reportResult) {
            echo "Report submitted successfully.";
        } else {
            echo "Error submitting report: " . $conn->error;
        }
    } else {
        echo "Invalid post type.";
    }
} else {
    echo "Invalid request method.";
}
