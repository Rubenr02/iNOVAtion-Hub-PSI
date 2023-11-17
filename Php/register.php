<?php

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovation");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $birthDate = $_POST['birth-date'];
    $occupation = $_POST['occupation'];
    $image = $_POST['image'];

    // Hash the password securely
    $hashedPassword = md5($password);

    // Perform SQL query to insert new user
    $sql = "INSERT INTO USERS (EMAIL, USERNAME, PASSWORD, FIRSTNAME, LASTNAME, DATE_OF_BIRTH, OCCUPATION, IMAGE) 
            VALUES ('$email', '$username', '$hashedPassword', '$firstName', '$lastName', '$birthDate', '$occupation', '$image')";

    if ($conn->query($sql) === TRUE) {
        header("Location: /PSI/iNOVAtion-html.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
