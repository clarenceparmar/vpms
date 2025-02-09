<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "vpmsx");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$user = null;

// Fetch user details
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($con, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_name"])) {
        $new_name = $_POST["name"];
        $update_query = "UPDATE users SET username='$new_name' WHERE id=" . $user['id'];
        if (mysqli_query($con, $update_query)) {
            $_SESSION['username'] = $new_name;
            header("Location: user_settings.php?message=Name updated successfully");
            exit();
        } else {
            header("Location: user_settings.php?error=Failed to update name");
            exit();
        }
    }

    if (isset($_POST["change_password"])) {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];

        if ($user['password'] == $old_password) { // Change to hashed verification in production
            $update_query = "UPDATE users SET password='$new_password' WHERE id=" . $user['id'];
            if (mysqli_query($con, $update_query)) {
                header("Location: user_settings.php?message=Password changed successfully");
                exit();
            } else {
                header("Location: user_settings.php?error=Failed to change password");
                exit();
            }
        } else {
            header("Location: user_settings.php?error=Incorrect old password");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

    <?php include( $_SERVER[ 'DOCUMENT_ROOT'] . '/assets/user_header.html' )?>

    <header>
        <h1>User Settings</h1>
    </header>

    <?php if (isset($_GET['message'])) { echo "<p style='color: green;'>".$_GET['message']."</p>"; } ?>
    <?php if (isset($_GET['error'])) { echo "<p style='color: red;'>".$_GET['error']."</p>"; } ?>

    <main class="main" >
        <section>
                <!-- Profile Section -->
    <div>
        <h3>Personal Information</h3>
        <div class="form">
            <form action="" method="POST">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['username']) ?>" required>
                <button type="submit" name="update_name">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Change Password Section -->
    <div>
        <h3>Change Your Password</h3>
        <div class="form">
            <form action="" method="POST">
                <label for="old_password">Old Password:</label>
                <input type="password" id="old_password" name="old_password" required>
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>
    </div>

    <!-- Logout -->
    <div>
        <h3>Logout</h3>
        <div class="form">
            <form action="/common/logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>


        </section>
    </main>

</body>
</html>
