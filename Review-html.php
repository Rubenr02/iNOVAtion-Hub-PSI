<?php
// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

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
    <link rel="stylesheet" href="Styling/Review-css.css">  
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

      <form action="send-email/send-review.php" method="post" enctype="multipart/form-data">

      <div class="post-container">
        <h1>Review a Post</h1>

        <input type="hidden" name="post_id" value="<?php echo isset($_GET['post_id']) ? intval($_GET['post_id']) : ''; ?>">

        <div class="input-text">
          <textarea id="post-text" name="post-text" placeholder="Add a text (up to 200 words)" 
              rows="4" required></textarea>
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
      

      <br>

      <div class="star-rating-container">
        <label for="rating">Give a Rating:</label>
        <input type="hidden" name="rating" id="rating" value="0">
        <div class="stars">
            <i class="fas fa-star" data-index="1"></i>
            <i class="fas fa-star" data-index="2"></i>
            <i class="fas fa-star" data-index="3"></i>
            <i class="fas fa-star" data-index="4"></i>
            <i class="fas fa-star" data-index="5"></i>
        </div>
      </div>


      <form action="send-email/send-review.php" method="post" enctype="multipart/form-data">

        <button id="post-button" type="submit" name="post-button">
            Post
            <i class="uil uil-plus-circle"></i>
        </button>

    </form>

    </div>

    </form>
  
    <script type="text/javascript" src="Scripts/iNOVAtion-js.js"></script>
    <script>
    const stars = document.querySelectorAll('.stars i');
    const ratingInput = document.getElementById('rating');

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            highlightStars(index);
        });

        star.addEventListener('mouseout', () => {
            resetStars();
            highlightStars(parseInt(ratingInput.value) - 1);
        });

        star.addEventListener('click', () => {
            setRating(index + 1);
        });
    });

    function highlightStars(index) {
        resetStars();
        for (let i = 0; i <= index; i++) {
            stars[i].classList.add('active');
        }
    }

    function resetStars() {
        stars.forEach((star) => {
            star.classList.remove('active');
        });
    }

    function setRating(rating) {
        ratingInput.value = rating;
        highlightStars(rating - 1);
    }

</script>
<script src="Scripts/iNOVAtion-js.js"></script>

</body>
</html>