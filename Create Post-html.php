<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovation");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

// Fetch tags from the database
$tagQuery = "SELECT TAGS FROM TAGS";
$tagResult = $conn->query($tagQuery);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="Styling/Create Post-css.css">  
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
              <input type="text" placeholder="Search for tags, problems, ideas ...">
              <i class="uil uil-search"></i>
            </div>

            <a href="Profile-html.html"><i class="uil uil-user"></i> Profile</a>

          </div>

          <div class="navigation">
            <div class="nav-items">
              <i class="uil uil-times nav-close-btn"></i>
              <a href="#"><i class="uil uil-home"></i> Home</a>
              <a href="#"><i class="uil uil-compass"></i> Explore</a>
              <a href="#"><i class="uil uil-info-circle"></i> About</a>
              <a href="#"><i class="uil uil-envelope"></i> Contact</a>
            </div>
          </div>
          <i class="uil uil-apps nav-menu-btn"></i>
        </div>

      </header>

      <form action="Php/createpost.php" method="post" enctype="multipart/form-data">

      <div class="post-container">
        <h1>Share your new Idea</h1>

        <div class="input-title">
          <input type="text" id="post-title" name="post-title" placeholder="Add a Title" required>
        </div>

        <div class="input-text">
          <textarea id="post-text" name="post-text" placeholder="Add a text (up to 200 words)" 
              rows="4" required></textarea>
              
      </div>
      
      <script>
          document.getElementById('post-text').addEventListener('input', function () {
              var words = this.value.match(/\S+/g);
              var wordCount = words ? words.length : 0;
              
              if (wordCount > 200) {
                  // Trim the excess words
                  var trimmedText = this.value.split(/\s+/, 200).join(' ');
                  this.value = trimmedText;
                  wordCount = 200;
              }
      
              document.getElementById('word-count').textContent = 'Words remaining: ' + (200 - wordCount);
          });
      </script>
      

      <div class="input-tag">

        <select id="post-tag" name="post-tag" class="select-tag">
            <option value="" disabled selected>Add a Tag</option>
            <?php
            // Fetch tags from the database
            $tagQuery = "SELECT TAGS FROM TAGS";
            $tagResult = $conn->query($tagQuery);

            if ($tagResult->num_rows > 0) {
                while ($tagRow = $tagResult->fetch_assoc()) {
                    $tagName = $tagRow['TAGS'];
                    echo "<option value=\"$tagName\">$tagName</option>";
                }
            }
            ?>
        </select>

        <i class="uil uil-angle-down"></i>

      </div>
    
      <div class="input-type">
          <select id="post-type" name="post-type" class="select-type">
              <option value="" disabled selected>Specify Content</option>
              <option value="idea">Idea</option>
              <option value="problem">Problem</option>
          </select>
          <i class="uil uil-angle-down"></i>
      </div>

      <br>

      <div class="input-image">
        <label for="post-image"><i class="uil uil-camera-plus"></i> Select Image...</label>
          <input type="file" id="post-image" name="post-image" accept="image/*">
      </div>
      
      <!-- Add a file input for PDF files -->
      <div class="input-pdf">
          <label for="post-pdf"><i class="uil uil-file-upload"></i> Select PDF File...</label>
          <input type="file" id="post-pdf" name="post-pdf" accept=".pdf">
      </div>

      <div class="input-hide">
          <label for="hide-real-name">Anonymous</label>
          <input type="checkbox" id="hide-real-name" name="hide-real-name">
      </div>

      <button id="post-button" type="submit" name="post-button">
        Post
        <i class="uil uil-plus-circle"></i>
      </button>

    </div>

    </form>
  
</body>
</html>
