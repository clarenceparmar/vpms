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

// Handle Bill Addition
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_bill'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $amount = $_POST['amount'];
    $bill_date = $_POST['bill_date'];

    if ($user_id) {
        $conn->query("INSERT INTO bills (user_id, vehicle_id, amount, bill_date) 
                      VALUES ('$user_id', '$vehicle_id', '$amount', '$bill_date')");
    }
    header("Location: user_bills.php");
    exit();
}

// Handle Bill Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM bills WHERE id='$id' AND user_id='$user_id'");
    header("Location: user_bills.php");
    exit();
}

// Fetch user's vehicles for the bill form
$vehicleOptions = "";
$vehicleResult = $conn->query("SELECT id, vehicle_type FROM REGIS_VEHICLES WHERE user_id='$user_id'");
while ($row = $vehicleResult->fetch_assoc()) {
    $vehicleOptions .= "<option value='{$row['id']}'>{$row['vehicle_type']}</option>";
}

// Fetch user's bills
$userBills = $conn->query("
    SELECT b.id, b.amount, b.bill_date, rv.vehicle_type, rv.number_plate 
    FROM bills b
    INNER JOIN REGIS_VEHICLES rv ON b.regis_vehicle_id = rv.id
    WHERE rv.user_id = '$user_id'
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Bills</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/style2.css">
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/assets/user_header.html') ?>
    <header>
        <h1>Manage Your Bills</h1>
    </header>

    <main class="main">
        <section>
            <div>
                <h3>Add a New Bill</h3>
                <div class="form">
                    <form method="POST">
                        <label for="vehicle-id">Select Vehicle:</label>
                        <select id="vehicle-id" name="vehicle_id" required>
                            <?= $vehicleOptions ?>
                        </select>

                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" step="0.01" required>

                        <label for="bill-date">Bill Date:</label>
                        <input type="date" id="bill-date" name="bill_date" required>

                        <button type="submit" name="add_bill">Add Bill</button>
                    </form>
                </div>
            </div>

            <div>
                <h3>Your Bills</h3>
                <ul>
                    <?php while ($row = $userBills->fetch_assoc()): ?>
                        <li>
                            <?= "{$row['bill_date']} - {$row['vehicle_id']} - {$row['amount']} USD" ?>
                            <a href="?delete=<?= $row['id'] ?>" style="color: red;">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>
