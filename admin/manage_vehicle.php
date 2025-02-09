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

// Handle form submission for adding or updating vehicles
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicleType = $_POST['vehicle_type'];
    $priceByHour = $_POST['price_by_hour'];

    // Check if it's an update or an insert
    if (isset($_POST['vehicle_id']) && $_POST['vehicle_id'] != "") {
        // Update existing vehicle type and price
        $vehicleId = $_POST['vehicle_id'];
        $stmt = $conn->prepare("UPDATE vehicles SET vehicle_type = ?, price_by_hour = ? WHERE id = ?");
        $stmt->bind_param("sdi", $vehicleType, $priceByHour, $vehicleId);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new vehicle type and price
        $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES (?, ?)");
        $stmt->bind_param("sd", $vehicleType, $priceByHour);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect back to avoid form resubmission
    header("Location: manage_vehicle.php");
    exit();
}

// Handle vehicle deletion
if (isset($_GET['delete'])) {
    $vehicleId = intval($_GET['delete']);
    $conn->query("DELETE FROM vehicles WHERE id = $vehicleId");
    header("Location: manage_vehicle.php");
    exit();
}

// Fetch all vehicles for editing
$vehiclesQuery = $conn->query("SELECT * FROM vehicles");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Vehicle Management</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">
</head>
<body>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/assets/admin_header.html'  ) ?>

    <header>
        <h1>Vehicle Management</h1>
    </header>

    <main class="main-container">
        <section>
            <h3>Add or Update Vehicle Type and Price</h3>

            <!-- Form to add/update vehicle type and price -->
            <form method="post">
                <label for="vehicle_type">Vehicle Type</label>
                <input type="text" id="vehicle_type" name="vehicle_type" required>

                <label for="price_by_hour">Price per Hour</label>
                <input type="number" id="price_by_hour" name="price_by_hour" step="0.01" required>

                <input type="hidden" name="vehicle_id" id="vehicle_id"> <!-- To hold vehicle ID for update -->

                <button type="submit">Submit</button>
            </form>

            <h3>Existing Vehicle Types</h3>
            <table border="1" width="100%">
                <tr class="fixed">
                    <th>Vehicle Type</th>
                    <th>Price per Hour</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $vehiclesQuery->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['vehicle_type']) ?></td>
                        <td><?= $row['price_by_hour'] ?></td>
                        <td>
                            <!-- Button to populate form with existing vehicle data for editing -->
                            <button onclick="populateForm(<?= $row['id'] ?>, '<?= htmlspecialchars($row['vehicle_type']) ?>', <?= $row['price_by_hour'] ?>)">Edit</button>
                            
                            <!-- Delete link -->
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this vehicle?');">
                                <button>Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </main>

    <script>
        // Function to populate the form for editing an existing vehicle type
        function populateForm(vehicleId, vehicleType, price) {
            document.getElementById('vehicle_id').value = vehicleId;
            document.getElementById('vehicle_type').value = vehicleType;
            document.getElementById('price_by_hour').value = price;
        }
    </script>

</body>
</html>
