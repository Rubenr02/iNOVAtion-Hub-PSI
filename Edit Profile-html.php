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

if ($profileUserId) {
    // Fetch the username and profile image from the database based on the provided or logged-in USERID
    $userQuery = "SELECT USERNAME, IMAGE AS USER_IMAGE FROM USERS WHERE USERID = '$profileUserId'";
    $userResult = $conn->query($userQuery);

    if ($userResult->num_rows == 1) {
        $userRow = $userResult->fetch_assoc();
        $username = $userRow['USERNAME'];
        $userImage = $userRow['USER_IMAGE'];
        
    } else {
        echo "Error fetching user information.";
        // Handle the case where no user is found with the provided ID
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="Styling/Edit Profile-css.css">  
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>

    <header>
        <div class="nav-bar">
            <div class="logo">
                <a href="iNOVAtion-html.php">
                    <img src="Images/iNOVAtion Hub.png" alt="Logo" id="main-logo">
                </a>
            </div>
            <div class="header-links">
                <a href="Profile-html.php"><i class="uil uil-user"></i> Profile</a>

            </div>
            <div class="navigation">
                <div class="nav-items">
                    <i class="uil uil-times nav-close-btn"></i>
                    <a href="#"><i class="uil uil-home"></i> Home</a>
                    <a href="#"><i class="uil uil-compass"></i> Explore</a>
                    <a href="#"><i class="uil uil-info-circle"></i> About</a>
                    <a href="landing-html.html"><i class="uil uil-signout"></i> Sign Out</a>
                </div>
            </div>
            <i class="uil uil-apps nav-menu-btn"></i>
        </div>
    </header>

    <body>
        <div class="profile-container">
            <div class="profile-header">
                <img src="<?php echo $userImage; ?>" alt="User Profile Picture" class="profile-picture" id="profilePicture">
                <div class="profile-info" id="profileInfo">
                    <h1 class="username" id="username"><?php echo $username; ?></h1>
                    <input type="text" id="description" name="description" placeholder="Add Your description here!" required>
                </div>
            </div>

            <div class="info-container">
            <h2>Account Details</h2>
            
            <form id="register-form" action="Php/updateprofile.php" method="post" enctype="multipart/form-data">

            <div class="register-step" id="register-step-1">
                
                <input type="text" id="email" name="email" placeholder="Email">
                <input type="text" id="username" name="username" placeholder="Username">
                <input type="password" id="password" name="password" placeholder="Password">
                <input type="text" id="first-name" name="first-name" placeholder="First Name">
                <input type="text" id="last-name" name="last-name" placeholder="Last Name">
                <input type="date" id="birth-date" name="birth-date">
                <select id="occupation" name="occupation">
                    <option value="Student">Student</option>
                    <option value="Professor">Professor</option>
                    <option value="Others">Others</option>
                </select>
                <br><br>
                <div class="random" id="open-code-page">
                    <a href="Insert Code-html.php">Have a reviewer code?</a>
                </div>
                
                <input type="file" id="user-image" name="user-image" accept="image/*">

            <div id="image-preview" style="display: none;">
            <img id="selected-image" src="" alt="Selected Image">
            </div>

                <button type="submit" id="finish-button" name="update">Save changes</button>
            </div>

            </form>
            </div>

        </div>
    </body>

<script type="text/javascript" src="Scripts/iNOVAtion-js.js"></script>

</body>
</html>
