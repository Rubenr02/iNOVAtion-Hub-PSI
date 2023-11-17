<?php

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovation");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Function to sanitize user input

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    echo $username = $_POST['username'];
    echo $password = $_POST['password'];


    $hashedPassword = md5($password);

    // Perform SQL query
    $sql = "SELECT * FROM USERS WHERE (USERNAME = '$username' OR EMAIL = '$username') AND PASSWORD = '$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        header("Location: /PSI/iNOVAtion-html.html");
        exit();
    } else {
        echo "Invalid username or password";
        echo "Number of rows returned: $result->num_rows";

    }
}


// Close the database connection
$conn->close();
?>
