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
    <!-- PROFESSIONAL NAVBAR -->
    <nav class="patient-navbar">

        <!-- Top Row: Logo + Title | Search + My Account -->
        <div class="navbar-top">
            <div class="navbar-brand">
                <img src="logo.png"
                     alt="UCLan Logo"
                     class="uclan-logo">
                <h1 class="site-title">HEALTH MATTERS</h1>
            </div>

            <div class="navbar-right">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." readonly>
                </div>
                <a href="ProfDash.php" class="my-account-link">
                    My Account
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>

        <!-- Bottom Row: Nav Links -->
        <div class="navbar-bottom">

            <!-- Appointments Dropdown -->
            <div class="navbar-dropdown">
                <a href="#" class="navbar-dropdown-toggle">
                    Appointments <i class="fas fa-chevron-down" style="font-size:11px; margin-left:4px;"></i>
                </a>
                <div class="navbar-dropdown-menu">
                    <a href="ProfCalendar.php">Calendar View</a>
                    <a href="ProfTodayAppts.php">Today's Appointments</a>
                </div>
            </div>

            <a href="#">User Reports</a>
            <a href="#">Referrals</a>
            <a href="#">Advice Sheets</a>
            <a href="#">Notifications</a>

        </div>

    </nav>

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
