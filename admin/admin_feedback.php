<?php
session_start();
$conn = new mysqli("localhost", "root", "", "vpmsx");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is an admin
if (!isset($_SESSION['username'])) {
    die("Access Denied");
}

$username = $_SESSION['username'];
$adminCheck = $conn->query("SELECT role FROM users WHERE username='$username'");
$admin = $adminCheck->fetch_assoc();

if ($admin['role'] != 'a') {
    die("Access Denied: Admins Only!");
}

// Handle feedback deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM feedback WHERE id='$id'");
    header("Location: admin_feedback.php");
    exit();
}

// Fetch all feedback
$feedbacks = $conn->query("SELECT id, user_name, feedback, rating FROM feedback ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Feedback</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">

</head>
<body>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/assets/admin_header.html'  ) ?>
    
    <header>
        <h1>Feedback Management</h1>
    </header>
    <main class="main">
    
    <section>
        <h3>All User Feedback</h3>
        <table border="1" width="100%">
            <tr>
                <th>Username</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $feedbacks->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['user_name'] ?? 'Anonymous' ?></td>
                    <td><?= htmlspecialchars($row['feedback']) ?></td>
                    <td><?= $row['rating'] ?>/5</td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" style="color: red;">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
    </section>

    </main>

</body>
</html>
