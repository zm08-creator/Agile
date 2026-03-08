<?php
session_start();
require_once "config/db.php";

// MUST be Professional
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 2) {
    header("Location: Login.php");
    exit;
}

// Check for date filter from calendar
$dateFilter = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : null;

// Build query based on date filter
if ($dateFilter) {
    $stmt = $conn->prepare("
        SELECT full_name, dob, address, appointment_date, appointment_time, discussion, created_at 
        FROM appointments 
        WHERE DATE(appointment_date) = ?
        ORDER BY appointment_time ASC
    ");
    $stmt->execute([$dateFilter]);
    $appointments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $pageTitle = "Appointments for " . date('d/m/Y', strtotime($dateFilter));
} else {
    // Show ALL appointments
    $stmt = $conn->prepare("
        SELECT full_name, dob, address, appointment_date, appointment_time, discussion, created_at 
        FROM appointments 
        ORDER BY appointment_date DESC, appointment_time ASC
    ");
    $stmt->execute();
    $appointments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $pageTitle = "All Appointments";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Professional</title>
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
            <h1>📋 <?= htmlspecialchars($pageTitle) ?></h1>
            
            <?php if ($dateFilter): ?>
                <p class="page-subtitle">Selected from calendar: <?= date('l, jS F Y', strtotime($dateFilter)) ?></p>
            <?php endif; ?>
            
            <?php if (empty($appointments)): ?>
                <div class="no-appointments">
                    <?php if ($dateFilter): ?>
                        No appointments found for <?= date('d/m/Y', strtotime($dateFilter)) ?>.
                    <?php else: ?>
                        No appointments found.
                    <?php endif; ?>
                </div>

                <?php else: ?>

                    <table class="appointments-table">

                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Discussion</th>
                            <th>Location</th>
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
                                <td><?= htmlspecialchars(ucwords(str_replace('-', ' ', $appt['location']))) ?></td>
                                <td><?= date('d/m H:i', strtotime($appt['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php endif; ?>

            <div class="nav-buttons" style="margin-top: 30px;">
                <a href="ProfCalendar.php" class="btn back-btn">Back to Calendar View</a>
                <a href="ProfDash.php" class="btn back-btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
