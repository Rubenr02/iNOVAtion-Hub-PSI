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

    // SQL query to fetch the user
    $sql = "SELECT * FROM USERS WHERE (USERNAME = '$username' OR EMAIL = '$username') AND PASSWORD = '$hashedPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();

        // Store user information in the session so the information can be fetched in other pages
        $_SESSION['USERID'] = $row['USERID'];
        $_SESSION['USERNAME'] = $row['USERNAME'];

        // Redirect to the loading page --> that will latter on redirect to the Main Page (iNOVAtion Page)
        header("Location: /PSI/Loading-html.html");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

?>
