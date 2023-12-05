<?php
$con = mysqli_connect("localhost", "root", "", "inovationhub");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
} else {
    echo "Connected to MySQL successfully!";
}

// Close the connection (optional)
mysqli_close($con);
?>
