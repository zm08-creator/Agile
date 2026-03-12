<?php
session_start();
require_once "config/db.php";

// MUST be Professional (user_id = 2)
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 2) {
    header("Location: Login.php");
    exit;
}

// Get today's appointments
$today = date('Y-m-d');
$stmt = $conn->prepare("
    SELECT full_name, appointment_time, discussion, location 
    FROM appointments 
    WHERE appointment_date = ?
    ORDER BY appointment_time ASC
");
$stmt->execute([$today]);
$todaysAppts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Appointments - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="ProfDash.php">Dashboard</a>
        <a href="Login.php?logout=1" class="logout-link">Logout</a>
    </div>

    <div class="page-wrapper">
        <div class="container">
            <h1 class="page-title">Today's Appointments</h1>
            <h2 class="page-subtitle"><?= date('l, F jS, Y') ?></h2>

            <?php if (empty($todaysAppts)): ?>
                <div class="no-appointments">
                    No appointments scheduled for today.
                </div>
            <?php else: ?>
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Time</th>
                            <th>Reason for Visit</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todaysAppts as $appt): ?>
                            <tr>
                                <td><?= htmlspecialchars($appt['full_name']) ?></td>
                                <td><?= date('H:i', strtotime($appt['appointment_time'])) ?></td>
                                <td><?= htmlspecialchars($appt['discussion']) ?></td>
                                <td><?= htmlspecialchars(ucwords(str_replace('-', ' ', $appt['location']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="nav-buttons" style="margin-top: 30px;">
                <a href="ProfDash.php" class="btn back-btn">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
