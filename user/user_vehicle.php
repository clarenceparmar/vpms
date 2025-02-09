<?php
session_start();
$conn = new mysqli("localhost", "root", "", "vpmsx");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in username
$username = $_SESSION['username'] ?? '';

// Fetch user ID
$userResult = $conn->query("SELECT id FROM users WHERE username='$username'");
$user = $userResult->fetch_assoc();
$user_id = $user['id'] ?? 0;

// Handle Vehicle Addition
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_vehicle'])) {
    $vehicle_type = $_POST['vehicle_type'];
    $number_plate = $_POST['number_plate'];

    // Get vehicle_id
    $vehicleResult = $conn->query("SELECT id FROM vehicles WHERE vehicle_type='$vehicle_type'");
    $vehicle = $vehicleResult->fetch_assoc();

    if ($user_id) {
        $conn->query("INSERT INTO REGIS_VEHICLES (user_id, vehicle_type, number_plate) 
                      VALUES ('$user_id', '$vehicle_type', '$number_plate')");
    }
    header("Location: user_vehicle.php");
    exit();
}

// Handle Vehicle Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM REGIS_VEHICLES WHERE id='$id' AND user_id='$user_id'");
    header("Location: user_vehicle.php");
    exit();
}

// Fetch vehicle types for dropdown
$vehicleOptions = "";
$vehicleResult = $conn->query("SELECT vehicle_type FROM vehicles");
while ($row = $vehicleResult->fetch_assoc()) {
    $vehicleOptions .= "<option value='{$row['vehicle_type']}'>{$row['vehicle_type']}</option>";
}

// Fetch user's vehicles
$userVehicles = $conn->query("SELECT * FROM REGIS_VEHICLES WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/style2.css">
</head>
<body>
    <?php include( $_SERVER[ 'DOCUMENT_ROOT'] . '/assets/user_header.html' )?>
    <header>
        <h1>Manage Your Vehicles</h1>
    </header>

    <main class="main">
        <section>
            <div>
            <h3>Add a New Vehicle</h3>
                <div class="form">
                    <form method="POST">
                        <label for="vehicle-type">Vehicle Type:</label>
                        <select id="vehicle-type" name="vehicle_type">
                            <?= $vehicleOptions ?>
                        </select>

                        <label for="number-plate">Number Plate:</label>
                        <input type="text" id="number-plate" name="number_plate" required>

                        <button type="submit" name="add_vehicle">Add Vehicle</button>
                    </form>
                </div>
            </div>

            <div>
                <h3>Your Vehicles</h3>
                <ul>
                    <?php while ($row = $userVehicles->fetch_assoc()): ?>
                        <li>
                            <?= "{$row['vehicle_type']} - {$row['number_plate']}" ?>
                            <a href="?delete=<?= $row['id'] ?>" style="color: red;">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>

    </main>
    
</body>
</html>
