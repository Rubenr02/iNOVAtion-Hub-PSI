<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Start or resume the session
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Form</title>
  <link rel="stylesheet" href="Styling/Submit Form-css.css">
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

        <a href="Profile-html.php"><i class="uil uil-user"></i> Profile</a>

      </div>

      <div class="navigation">
        <div class="nav-items">
          <i class="uil uil-times nav-close-btn"></i>
          <a href="iNOVAtion-html.php"><i class="uil uil-home"></i> Home</a>
          <a href="About.html"><i class="uil uil-info-circle"></i> About</a>
          <a href="Contact.html"><i class="uil uil-envelope"></i> Contact</a>
          <a href="landing-html.html"><i class="uil uil-signout"></i> Sign Out</a>
        </div>
      </div>
      <i class="uil uil-apps nav-menu-btn"></i>
    </div>

  </header>

  <form action="Php/submitform.php" method="post" enctype="multipart/form-data">

    <div class="post-container">
      <h1 class="header-text">Submit a request form to level up your idea</h1>

      <h4 class="question">What inspires you to keep progressing your idea?</h4>

      <div class="input-text">
        <textarea id="post-text" name="post-text" placeholder="Add a text (up to 200 words)" rows="4"
          required></textarea>
      </div>

      <h4 class="question">How has your idea evolved since the beginning? Explain why you believe it should be accepted
        to the next level.</h4>

      <div class="input-text">
        <textarea id="post-text-2" name="post-text-2" placeholder="Add a text (up to 200 words)" rows="4"
          required></textarea>
      </div>

      <!-- JavaScript code to prevent exceeding the 200 words maximum -->
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


      <div class="input-image">
        <label for="post-image"><i class="uil uil-camera-plus"></i> Select Image...</label>
        <input type="file" id="post-image" name="post-image" accept="image/*">
      </div>

      <!-- Add a file input for PDF files -->
      <div class="input-pdf">
        <label for="post-pdf"><i class="uil uil-file-upload"></i> Select PDF File...</label>
        <input type="file" id="post-pdf" name="post-pdf" accept=".pdf">
      </div>

      <input type="hidden" name="post_id" value="<?php echo isset($_GET['post_id']) ? htmlspecialchars($_GET['post_id']) : ''; ?>">

      <button id="post-button" type="submit" name="post-button">
        Submit
        <i class="uil uil-plus-circle"></i>
      </button>

    </div>

  </form>

  <script type="text/javascript" src="Scripts/iNOVAtion-js.js"></script>

</body>

</html>