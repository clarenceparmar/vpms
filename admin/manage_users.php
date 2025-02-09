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

// Search functionality
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
}

// Fetch all users
$query = "SELECT id, username, role FROM users WHERE username LIKE '%$searchQuery%' ORDER BY id DESC";
$users = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/assets/admin_header.html'); ?>

    <header>
        <h1>User Management</h1>
    </header>
    
    </main class="main">
 
        <section>
            <h3>All Registered Users</h3>

            <!-- Search box -->
            <form method="get" class="search-form">
                <input type="text" name="search" placeholder="Search by Username" value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit">Search</button>
            </form>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['role'] == 'a' ? 'Admin' : ($row['role'] == 'u' ? 'User' : 'Other') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
