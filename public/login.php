<!-- <?php 
    session_start();  
    if (isset($_SESSION['role'])) 
    { 
        header("Location: /common/auth.php");  
        exit(); 
    } 
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <header>
        <h1>Login</h1>
    </header>
    
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/assets/public_header.html'); ?>

    <main class="main">
        
        <section>
            <div class="form">
                <form action="" method="POST">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>

                    <a href="/public/registration.php">New User?</a>

                    <div class="button-group">
                        <button type="submit">Login</button>
                    </div>
                    
                </form>
            </div>
        </section>

    </main>



</body>
</html>


<?php
        $con = mysqli_connect("localhost", "root", "", "vpmsx");
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $username = $_POST['username'];
                $password = $_POST['password'];

                // Fetch the user with the provided username
                $sql = "SELECT username, password, role FROM users WHERE username='$username'";
                $result = mysqli_query($con, $sql);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    
                    // Check if password matches exactly
                    if ($row['password'] === $password) {
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $row['role'];
                        echo "<script>alert('Username has been set, redirecting to AUTH.php');</script>";
                        header("Location: /common/auth.php");
                        exit();
                    } else {
                        echo "<script>alert('Invalid password');</script>";
                    }
                } else {
                    echo "<script>alert('Invalid username');</script>";
                }
            }
        }
    ?>