<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process profile changes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['validate'])) {
    $code = $_POST['code'];

    if (isset($_SESSION['USERID'])) {
        $userid = $_SESSION['USERID'];
    }

        // Perform SQL query
        $sql = "SELECT * FROM CODES WHERE CODE = '$code'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {

        // Perform SQL query to update user info
        $sqll = "UPDATE USERS
                SET USERTYPE = '1'
                WHERE USERID = '$userid'";

        $sqlll = "DELETE FROM CODES
                WHERE CODE = '$code'";

        if ($conn->query($sqlll) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        if ($conn->query($sqll) === TRUE) {
            // Store user information in the session so the information can be fetched in other pages
            $_SESSION['USERID'] = $userid;

            header("Location: /PSI/Loading-html.html");
            exit();
        }
    } else {
            echo "Error: Code not valid.<br>" . $conn->error;
        }     
}

// Close the database connection
$conn->close();

?>
