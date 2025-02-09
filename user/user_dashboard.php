<?php
session_start();
$conn = new mysqli("localhost", "root", "", "vpmsx");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("Access Denied");
}

$username = $_SESSION['username'];
$userCheck = $conn->query("SELECT id FROM users WHERE username='$username'");
$user = $userCheck->fetch_assoc();
$user_id = $user['id'];

// Fetch all vehicles registered by the user
$vehiclesQuery = $conn->query("SELECT * FROM REGIS_VEHICLES WHERE user_id = $user_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleId = $_POST['vehicle_id'];
    $action = $_POST['action']; // Park or Unpark

    if ($action == 'park') {
        // Park the vehicle, set the start time
        $stmt = $conn->prepare("UPDATE REGIS_VEHICLES SET parking_status = 'y', start_time = NOW() WHERE id = ?");
        $stmt->bind_param("i", $vehicleId);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'unpark') {
        // Unpark the vehicle, set the end time and calculate the bill
        $stmt = $conn->prepare("SELECT price_by_hour, start_time FROM vehicles v JOIN REGIS_VEHICLES rv ON v.vehicle_type = rv.vehicle_type WHERE rv.id = ?");
        $stmt->bind_param("i", $vehicleId);
        $stmt->execute();
        $stmt->bind_result($price_per_hour, $start_time);
        $stmt->fetch();
        $stmt->close();

        // Calculate the total duration in hours
        $end_time = date('Y-m-d H:i:s');
        $start_time_obj = new DateTime($start_time);
        $end_time_obj = new DateTime($end_time);
        $interval = $start_time_obj->diff($end_time_obj);
        $hours_parked = $interval->h + ($interval->i / 60); // Convert minutes to hours

        // Calculate the total amount for the bill
        $total_amount = $hours_parked * $price_per_hour;

        // Insert a new record into the bills table
        $stmt = $conn->prepare("INSERT INTO bills (regis_vehicle_id, amount) VALUES (?, ?)");
        $stmt->bind_param("id", $vehicleId, $total_amount);
        $stmt->execute();
        $stmt->close();

        // Update the REGIS_VEHICLES table, set the parking status to 'n', and set the end time
        $stmt = $conn->prepare("UPDATE REGIS_VEHICLES SET parking_status = 'n', end_time = NOW() WHERE id = ?");
        $stmt->bind_param("i", $vehicleId);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to avoid form resubmission
    header("Location: user_parking.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Parking Management</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">
</head>
<body>
    <?php include( $_SERVER[ 'DOCUMENT_ROOT'] . '/assets/user_header.html' )?>

    <header>
        <h1>Parking Management</h1>
    </header>
    <main class="main">

    <section>
        <h3>Your Vehicles</h3>
        <table border="1" width="100%">
            <tr>
                <th>Vehicle Type</th>
                <th>Number Plate</th>
                <th>Parking Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $vehiclesQuery->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['vehicle_type']) ?></td>
                    <td><?= htmlspecialchars($row['number_plate']) ?></td>
                    <td><?= $row['parking_status'] == 'y' ? 'Parked' : 'Not Parked' ?></td>
                    <td>
                        <?php if ($row['parking_status'] == 'n'): ?>
                            <form method="POST">
                                <input type="hidden" name="vehicle_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="park">
                                <button type="submit">Park</button>
                            </form>
                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="vehicle_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="unpark">
                                <button type="submit">Unpark</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>
    <main>
</body>
</html>
