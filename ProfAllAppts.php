<?php
session_start();
require_once "config/db.php"; // Your DB connection

// MUST be Professional
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 2) {
    header("Location: Login.php");
    exit;
}

// Get ALL appointments (since only 1 professional exists)
$stmt = $pdo->query("
    SELECT full_name, dob, address, appointment_date, appointment_time, discussion, created_at 
    FROM appointments 
    ORDER BY appointment_date DESC, appointment_time ASC
");
$appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments - Professional</title>
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
            <h1>📋 All Appointments</h1>
            
            <?php if (empty($appointments)): ?>
                <div class="no-appointments">
                    No appointments found.
                </div>
            <?php else: ?>
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Discussion</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td><?= htmlspecialchars($appt['full_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($appt['appointment_date'])) ?></td>
                                <td><?= date('H:i', strtotime($appt['appointment_time'])) ?></td>
                                <td><?= htmlspecialchars($appt['discussion']) ?></td>
                                <td><?= date('d/m H:i', strtotime($appt['created_at'])) ?></td>
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
