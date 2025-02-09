<?php 
    session_start();
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'u') {
        $name = $_SESSION['username'];
        include($_SERVER["DOCUMENT_ROOT"] . '/assets/user_header.html');
    } else {
        include($_SERVER["DOCUMENT_ROOT"] . '/assets/public_header.html');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - VPMS</title>

    <link rel="stylesheet" href="/css/style.css">
    
</head>

<body>

    <header>
        <h1>Contact Us</h1>
        <!-- <button class="back-btn" onclick="window.history.back()">Back</button> -->
    </header>

    <main class="main">
        <section>

        <div class="form" >

            <h2>Give Your Feedback</h2>

            <!-- Display Message Here -->
            <?php 
                if (isset($_GET['message'])) { 
                    echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>"; 
                } 
                if (isset($_GET['error'])) { 
                    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>"; 
                } 
            ?>

                <form action="" method="POST">

                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name"  value="<?php 
                        if(isset($name)) {
                            echo $name;
                        }else{
                            echo "";
                        }
                    ?>" required>

                    <label for="message">Your Feedback:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <label for="rating">Rating (1-5):</label>
                    <select id="rating" name="rating" required>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>

                    <button type="submit">Submit Feedback</button>
                </form>
            </div>
        </section>
    </main>
   


</body>
</html>

<?php
        $con = mysqli_connect("localhost", "root", "", "vpmsx");

        if (!$con) {
            header("Location: contact.php?error=Database connection failed.");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $user_name = $_POST['name'];
            $feedback = $_POST['message'];
            $rating = $_POST['rating'];
            $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'o';

            // Check if the user is logged in and retrieve user_id
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NULL";

            $query = "INSERT INTO feedback (user_name, feedback, rating, role) 
                    VALUES ('$user_name', '$feedback', $rating, '$role')";

            if (mysqli_query($con, $query)) {
                header("Location: contact.php?message=Feedback submitted successfully!");
            } else {
                header("Location: contact.php?error=Failed to submit feedback. Please try again.");
            }
            exit();
        }
    ?>