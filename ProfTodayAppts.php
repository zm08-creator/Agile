<?php
session_start();
require_once "config/db.php";

// MUST be Professional
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 2) {
    header("Location: Login.php");
    exit;
}

// Get TODAY'S appointments only
$stmt = $pdo->query("
    SELECT full_name, dob, address, appointment_date, appointment_time, discussion, created_at 
    FROM appointments 
    WHERE DATE(appointment_date) = CURDATE()
    ORDER BY appointment_time ASC
");
$todaysAppts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Appointments - Professional</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="ProfDash.php">Dashboard</a>
        <a href="Login.php?logout=1">Logout</a>
    </div>

    <div class="page-wrapper">
        <div class="container">
            <h1>✨ Today's Appointments</h1>
            <p>Appointments for <?= date('l, jS F Y') ?> (<?= date('H:i') ?>)</p>
            
            <?php if (empty($todaysAppts)): ?>
                <div class="no-appointments">
                    No appointments scheduled for today.
                </div>
            <?php else: ?>
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Time</th>
                            <th>Discussion</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todaysAppts as $appt): ?>
                            <tr>
                                <td><?= htmlspecialchars($appt['full_name']) ?></td>
                                <td><?= date('H:i', strtotime($appt['appointment_time'])) ?></td>
                                <td><?= htmlspecialchars($appt['discussion']) ?></td>
                                <td><?= htmlspecialchars(substr($appt['address'], 0, 30)) ?>...</td>
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
