<?php

// Create connection
$conn =  mysqli_connect("localhost", "root", "", "inovation");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Function to sanitize user input

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];


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
}

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $birthDate = $_POST['date-of-birth'];
    $occupation = $_POST['occupation'];

    $hashedPassword = md5($password); 

    // Perform SQL query to insert new user
    $sql = "INSERT INTO USERS (FIRSTNAME, LASTNAME, DATE_OF_BIRTH, OCCUPATION, EMAIL, PASSWORD) 
            VALUES ('$firstName', '$lastName', '$birthDate', '$occupation', '$email', '$hashedPassword')";

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
