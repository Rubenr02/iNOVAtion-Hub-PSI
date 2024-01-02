<?php
// Start or resume the session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "inovationhub");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Process review form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_SESSION['USERID'];
    $post_id = trim($_POST['post_id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post-button'])) {
        // Retrieve the USERID from the IDEAS table based on IDEAID
        $getUserSql = "SELECT USERID FROM IDEAS WHERE IDEAID = '$post_id'";
        $getUserResult = $conn->query($getUserSql);

        if ($getUserResult && $getUserResult->num_rows > 0) {
            $row = $getUserResult->fetch_assoc();
            $postUserId = $row['USERID'];

            // Insert review data into REVIEWS table
            $postText = mysqli_real_escape_string($conn, $_POST['post-text']);
            $evaluation = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

            $sql = "INSERT INTO REVIEWS (IDEAID, USERID, CREATEDON, EVALUATION, NOTES) 
                    VALUES ('$post_id', '$userid', NOW(), '$evaluation', '$postText')";

            if ($conn->query($sql) === TRUE) {
                // Update the level of the idea based on the evaluation
                $newLevel = calculateNewLevel($evaluation);

                // Update the LEVEL in the IDEAS table
                $updateLevelSql = "UPDATE IDEAS SET LEVEL = LEVEL + '$newLevel' WHERE IDEAID = '$post_id'";

                if ($conn->query($updateLevelSql) === TRUE) {
                    // Update the ACCEPTED column in the FORMS table
                    $updateAcceptedSql = "UPDATE FORMS SET ACCEPTED = 1 WHERE IDEAID = '$post_id'";

                    if ($conn->query($updateAcceptedSql) === TRUE) {
                        // Delete the corresponding row from FORMS table
                        $deleteFormSql = "DELETE FROM FORMS WHERE IDEAID = '$post_id'";
                        
                        if ($conn->query($deleteFormSql) === TRUE) {
                            header("Location: /PSI/Loading-html.html");
                            exit();
                        } else {
                            echo "Error deleting form: " . $deleteFormSql . "<br>" . $conn->error;
                        }
                        
                    } else {
                        echo "Error updating form acceptance: " . $updateAcceptedSql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error updating idea level: " . $updateLevelSql . "<br>" . $conn->error;
                }
            } else {
                echo "Error inserting review data: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error retrieving USERID: " . $getUserSql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: User not logged in.";
    }
}

// Function to calculate the new level based on the evaluation (Example logic)
function calculateNewLevel($evaluation) {
    // Adjust this logic based on your requirements
    $newLevel = 0; // Default level

    if ($evaluation >= 3) {
        $newLevel += 1;
    }

    return $newLevel;
}
?>
