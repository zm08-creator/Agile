<?php
session_start();

// Handle logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Check if user is logged in and is a patient
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: Login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- PATIENT NAVBAR -->
    <nav class="patient-navbar">

        <!-- Top Row: Logo + Title | Search + My Account -->
        <div class="navbar-top">
            <div class="navbar-brand">
                <img src="logo.png" alt="Logo" class="uclan-logo">
                <h1 class="site-title">HEALTH MATTERS</h1>
            </div>

            <div class="navbar-right">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." readonly>
                </div>
                <a href="PatientDash.php" class="my-account-link">
                    My Account
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>

        <!-- Bottom Row: Nav Links -->
        <div class="navbar-bottom">
            <a href="MakeAppt1.php">Book an Appointment</a>
            <a href="#">Advice Sheets</a>
            <a href="#">Profile Details</a>
            <a href="#">Notifications</a>
        </div>

    </nav>

    <!-- DASHBOARD CONTENT -->
    <div class="page-wrapper">
        <h1 class="page-title">Welcome Back, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle">Please select an option</h2>

        <div class="dashboard-actions">
            <a href="MakeAppt1.php" class="btn">
                Book New Appointment
            </a>

            <a href="#" class="btn back-btn" onclick="alert('Coming soon!')">
                View My Appointments
            </a>

            <a href="#" class="btn back-btn" onclick="alert('Coming soon!')">
                View My Details
            </a>
        </div>
    </div>

</body>
</html>