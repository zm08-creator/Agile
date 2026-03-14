<?php
session_start();

// Handle logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Check if user is logged in as doctor
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'doctor') {
    header("Location: Login.php");
    exit;
}

require_once "config/db.php";

$doctor_id = $_SESSION["user_id"];

$today = date('Y-m-d');
$stmt = $conn->prepare("
    SELECT b.*, p.FirstName as patient_first, p.LastName as patient_last
    FROM Bookings b 
    JOIN Patient p ON b.PatientID = p.PatientID
    WHERE b.DoctorID = ? AND b.Date = ?
    ORDER BY b.StartTime ASC
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
<!-- PROFESSIONAL NAVBAR -->
<nav class="prof-navbar">
    <div class="prof-navbar-top">
        <div class="navbar-brand">
            <img src="logo.jpg" alt="UCLan Logo" class="uclan-logo">
            <h1 class="site-title">HEALTH MATTERS</h1>
        </div>
        <div class="prof-navbar-right">
            <div class="nav-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search..." readonly>
            </div>
            <a href="ProfDash.php" class="prof-my-account">
                My Account
                <i class="fas fa-user-circle"></i>
            </a>
            <a href="?logout" class="prof-logout-link">
                Logout
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    
    <div class="prof-navbar-bottom">
        <div class="appointments-dropdown prof-nav-item">
            Appointments
            <div class="dropdown-menu">
                <a href="ProfTodayAppts.php" class="dropdown-item">Today's Appointments</a>
                <a href="ProfAllAppts.php" class="dropdown-item">All Appointments</a>
            </div>
        </div>
        
        <a href="#" class="prof-nav-item">User Reports</a>
        <a href="#" class="prof-nav-item">Referrals</a>
        <a href="#" class="prof-nav-item">Advice Sheets</a>
        <a href="#" class="prof-nav-item">Notifications</a>
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