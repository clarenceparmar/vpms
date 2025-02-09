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

// Fetch all registered vehicles, now filtering by number_plate
$query = "SELECT * FROM REGIS_VEHICLES WHERE number_plate LIKE '%$searchQuery%'";
$vehicles = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Registered Vehicles</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">

</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/assets/admin_header.html'  ) ?>

    <main class="main-container">
    <header>
        <h1>Registered Vehicles Management</h1>
    </header>

    <section>
        <h3>All Registered Vehicles</h3>

        <!-- Search box -->
        <form method="get">
            <input type="text" name="search" placeholder="Search by Number Plate" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Show All button (Reset search) -->
        <form method="get" style="margin-top: 10px;">
            <button type="submit" name="search" value="">Show All</button>
        </form>

        <table border="1" width="100%">
            <tr>
                <th>Vehicle Type</th>
                <th>Number Plate</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Parking Status</th>
            </tr>
            <?php while ($row = $vehicles->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['vehicle_type']) ?></td>
                    <td><?= htmlspecialchars($row['number_plate']) ?></td>
                    <td><?= $row['start_time'] ?></td>
                    <td><?= $row['end_time'] ?></td>
                    <td><?= $row['parking_status'] == 'y' ? 'Parked' : 'Not Parked' ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    </main>


</body>
</html>
