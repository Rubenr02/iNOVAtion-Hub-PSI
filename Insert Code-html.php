<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

// Check if a specific user ID is provided in the URL
$profileUserId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// If no specific user ID is provided, check if the current user is logged in
if (!$profileUserId && isset($_SESSION['USERID'])) {
    $profileUserId = $_SESSION['USERID'];
}

if (isset($_SESSION['USERID'])) {
    $visitorid = $_SESSION['USERID'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="Styling/Insert Code-css.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

</head>

<body>

    <header>

        <div class="nav-bar">

            <div class="logo">
                <a href="iNOVAtion-html.php">
                    <img src="Images/iNOVAtion Hub.png" alt="Your Logo" id="main-logo">
                </a>
            </div>

            <div class="header-links">

                <div class="search-bar">
                    <input type="text" placeholder="Search for problems or ideas...">
                    <i class="uil uil-search"></i>
                </div>

                <a href="Profile-html.php"><i class="uil uil-user"></i> Profile</a>

            </div>

            <div class="navigation">
                <div class="nav-items">
                    <i class="uil uil-times nav-close-btn"></i>
                    <a href="iNOVAtion-html.php"><i class="uil uil-home"></i> Home</a>
                    <a href="About-html.html"><i class="uil uil-info-circle"></i> About</a>
                    <a href="Contact-html.html"><i class="uil uil-envelope"></i> Contact</a>
                    <a href="landing-html.html"><i class="uil uil-signout"></i> Sign Out</a>
                </div>
            </div>
            <i class="uil uil-apps nav-menu-btn"></i>
        </div>

    </header>

    <div class="profile-container">
        <div class="info-container">
            <h2>Insert your code below</h2>
            <br>
            <form id="register-form" action="Php/code.php" method="post" enctype="multipart/form-data">

                <div class="register-step" id="register-step-1">
                    <input type="text" id="code" name="code" placeholder="Code">
                    <button type="submit" id="finish-button" name="validate">Validate code</button>
                </div>

            </form>
        </div>

    </div>

    <!-- JavaScript for the Landing Page-->
    <script type="text/javascript" src="Scripts/iNOVAtion-js.js"></script>

</body>

</html>