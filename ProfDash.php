<?php
session_start();

// MUST be logged in as Professional (user_id = 2)
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "professional") 
{
    header("Location: Login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <!-- DASHBOARD CONTENT -->
    <div class="page-wrapper">
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle">Please select an option</h2>

        <div class="dashboard-actions">
            <a href="MakeAppt1.php" class="btn">
                Make New Appointment
            </a>

            <a href="ProfCalendar.php" class="btn back-btn">
                View All Appointments
            </a>

            <a href="ProfTodayAppts.php" class="btn back-btn">
                Today's Appointments
            </a>
        </div>
    </div>

</body>
</html>
