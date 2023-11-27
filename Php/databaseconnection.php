<?php
$con = mysqli_connect("localhost", "root", "", "psi");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
} else {
    echo "Connected to MySQL successfully!";
}

// Close the connection 
mysqli_close($con);
?>
