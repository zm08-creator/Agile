<?php
session_start();

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
    <title>Patient Dashboard - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <!-- PATIENT NAVBAR -->
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
                <a href="PatientDash.php" class="my-account-link">
                    My Account
                    <i class="fas fa-user-circle"></i>
                </a>
                <a href="Logout.php" class="my-account-link">
                    Logout
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>

        <div class="navbar-bottom">
            <a href="MakeAppt1.php">Make Appointment</a>
            <a href="PatientAppts.php">My Appointments</a>
            <a href="#">Advice Sheets</a>
            <a href="#">Notifications</a>
        </div>
    </nav>

    <div class="page-wrapper">
        <h1 class="page-title">Welcome Back, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle">Please select an option</h2>

        <div class="dashboard-actions">
            <a href="MakeAppt1.php" class="btn">
                Book New Appointment
            </a>
            
            <a href="PatientAppts.php" class="btn back-btn">
                View My Appointments
            </a>

            <a href="ViewDetails.php" class="btn back-btn">
                View My Details
            </a>

            <a href="CancelAppt.php" class="btn cancel-btn">
                Cancel Appointment
            </a>
        </div>
    </div>
</body>
</html>