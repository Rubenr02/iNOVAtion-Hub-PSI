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
        $messages = array();

        while ($row = $result->fetch_assoc()) {
            // Add each message to the $messages array
            $messages[] = array(
                'senderID' => $row['SENDERID'],
                'message' => $row['MESSAGE']
            );
        }

        // Return the $messages array as JSON
        header('Content-Type: application/json');
        echo json_encode(array('messages' => $messages));
    } else {
        // Return an error response as JSON
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array('error' => 'Error fetching messages: ' . $stmt->error));
    }

    $stmt->close();
} else {
    // Return an error response as JSON
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'Invalid data received'));
}

$conn->close();
?>
