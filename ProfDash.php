<?php
session_start();

// Handle logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Check if user is logged in as practitioner/professional
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ['practitioner', 'professional'])) {
    header("Location: Login.php");
    exit;
}

require_once "config/db.php";

// Get professional's doctor_id (links user_id → doctors table)
$doctor_id = $_SESSION["user_id"];  // user_id = doctor_id for test system

// Get today's appointments for this doctor
$today = date('Y-m-d');
$stmt = $conn->prepare("
    SELECT b.*, p.first_name as patient_first, p.last_name as patient_last
    FROM bookings b 
    JOIN patients p ON b.patient_id = p.patient_id
    WHERE b.doctor_id = ? AND b.date = ?
    ORDER BY b.start_time ASC
");
$stmt->bind_param("is", $doctor_id, $today);
$stmt->execute();
$today_result = $stmt->get_result();
$today_count = $today_result->num_rows;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- FIXED NAVBAR (matches patient style) -->
    <nav class="patient-navbar">
        <div class="navbar-top">
            <div class="navbar-brand">
                <img src="logo.jpg" alt="UCLan Logo" class="uclan-logo">
                <h1 class="site-title">HEALTH MATTERS</h1>
            </div>
            <div class="navbar-right">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." readonly>
                </div>
                <a href="ProfDash.php" class="my-account-link">
                    Dashboard
                    <i class="fas fa-tachometer-alt"></i>
                </a>
                <a href="?logout" class="my-account-link">
                    Logout
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
        <div class="navbar-bottom">
            <a href="ProfTodayAppts.php">Today's Appointments</a>
            <a href="ProfAllAppts.php">All Appointments</a>
            <a href="#">Calendar</a>
            <a href="#">Patient Records</a>
        </div>
    </nav>

    <div class="page-wrapper">
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle">Professional Dashboard</h2>

        <!-- TODAY'S APPOINTMENTS SUMMARY -->
        <div class="today-summary">
            <h3>📅 Today (<?= date('l, F jS, Y') ?>)</h3>
            <p>You have <strong><?= $today_count ?></strong> appointments today</p>
            <?php if ($today_count > 0): ?>
                <a href="ProfTodayAppts.php" class="btn">View Today's Schedule →</a>
            <?php endif; ?>
        </div>

        <div class="dashboard-actions">
            <a href="ProfTodayAppts.php" class="btn">
                Today's Appointments
                <i class="fas fa-calendar-day"></i>
            </a>

            <a href="ProfAllAppts.php" class="btn back-btn">
                View All Appointments
                <i class="fas fa-list"></i>
            </a>

            <a href="#" class="btn back-btn" onclick="alert('Calendar coming soon!')">
                Calendar View
                <i class="fas fa-calendar-alt"></i>
            </a>
        </div>
    </div>
</body>
</html>
