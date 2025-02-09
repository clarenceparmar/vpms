<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - VPMS</title>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">

</head>
<body>

    <header>
        <h1>Register for VPMS</h1>
    </header>
    
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/assets/public_header.html'); ?>

    <div class="form">
        <h2>Create an Account</h2>

        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
    </div>

    <?php
    session_start();

    $con = mysqli_connect("localhost", "root", "", "vpmsx");

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST['username'];
        $password = $_POST['password'];

        
        $check = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($con, $check);

        if (mysqli_num_rows($result) > 0) {
            echo "Username already exists";
        } else {
            

            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if (mysqli_query($con, $sql)) {
                
                $_SESSION['username'] = $username;
                header("Location: /common/auth.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($con);
            }
        }
    }

    mysqli_close($con);
    ?>

</body>
</html>
