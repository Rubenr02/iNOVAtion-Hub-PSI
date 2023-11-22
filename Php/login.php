<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "psi");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Function to sanitize user input

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    echo "Username: $username<br>";
    echo "Password: $password<br>";

    $hashedPassword = md5($password);

    // Perform SQL query
    $sql = "SELECT * FROM USERS WHERE (USERNAME = '$username' OR EMAIL = '$username') AND PASSWORD = 'password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Store user information in the session
        $_SESSION['USERID'] = $row['USERID'];
        $_SESSION['USERNAME'] = $row['USERNAME'];

        // Redirect to the loading page
        header("Location: /PSI/Loading-html.html");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

?>
