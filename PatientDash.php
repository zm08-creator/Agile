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
    <title>Patient Dashboard - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="patient-dashboard.php">Dashboard</a>
        <a href="?logout=1" class="logout-link">Logout</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Welcome Back, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle">Please select an option</h2>

        <div class="dashboard-actions">
            <a href="MakeAppt1.php" class="btn">
                Book New Appointment
            </a>
            
            <a href="my-appointments.php" class="btn back-btn">
                View My Appointments
            </a>
        </div>

        <div class="user-info-card">
            <h3>Your Details</h3>
            <p><strong>User ID:</strong> <?= $_SESSION["user_id"] ?></p>
            <p><strong>Role:</strong> <?= ucfirst($_SESSION["role"]) ?></p>
        </div>
    </div>
</body>
</html>
