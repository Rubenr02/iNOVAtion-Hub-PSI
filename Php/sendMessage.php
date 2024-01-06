<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "inovationhub");


$senderID = $_SESSION['USERID'];
$receiverID = $_POST['receiverID'];
$message = $_POST['message'];

if (!empty($senderID) && !empty($receiverID) && !empty($message)) {
    $stmt = $conn->prepare("INSERT INTO CHATS (SENDERID, RECEIVERID, MESSAGE, SENT_ON) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $senderID, $receiverID, $message);

    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Error sending message: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid data received";
}

$conn->close();
?>
