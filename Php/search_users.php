<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "inovationhub");

$searchTerm = $_POST['searchTerm'];

if (!empty($searchTerm)) {
    $stmt = $conn->prepare("SELECT USERID, FIRSTNAME, LASTNAME, IMAGE, EMAIL FROM USERS WHERE CONCAT(FIRSTNAME, ' ', LASTNAME) LIKE ? AND USERID != ?");
    $searchPattern = "%$searchTerm%";
    $stmt->bind_param("si", $searchPattern, $_SESSION['USERID']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $users = array();

        while ($row = $result->fetch_assoc()) {
            // Add each user to the $users array
            $users[] = array(
                'userID' => $row['USERID'],
                'firstName' => $row['FIRSTNAME'],
                'lastName' => $row['LASTNAME'],
                'image' => $row['IMAGE'],
                'email' => $row['EMAIL'],
            );
        }

        // Return the $users array as JSON
        header('Content-Type: application/json');
        echo json_encode(array('users' => $users));
    } else {
        // Return an error response as JSON
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array('error' => 'Error searching users: ' . $stmt->error));
    }

    $stmt->close();
} else {
    // Return an error response as JSON
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('error' => 'Invalid search term'));
}

$conn->close();
?>
