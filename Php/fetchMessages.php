<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "inovationhub");

$senderID = $_SESSION['USERID'];
$receiverID = $_POST['receiverID'];

if (!empty($senderID) && !empty($receiverID)) {
    $stmt = $conn->prepare("SELECT * FROM CHATS WHERE (SENDERID = ? AND RECEIVERID = ?) OR (SENDERID = ? AND RECEIVERID = ?) ORDER BY SENT_ON ASC");
    $stmt->bind_param("iiii", $senderID, $receiverID, $receiverID, $senderID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<div class="message">' . $row['SENDERID'] . ': ' . $row['MESSAGE'] . '</div>';
        }
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo "Error fetching messages: " . $stmt->error;
    }

    $stmt->close();
} else {
    header('HTTP/1.1 400 Bad Request');
    echo "Invalid data received";
}

$conn->close();
?>
