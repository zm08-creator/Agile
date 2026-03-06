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
        <a href="?logout=1" style="color: #ff6b6b;">Logout</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Welcome Back, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle"> Please select an option</h2>

        <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;">
            <a href="MakeAppt1.php" class="btn" style="padding: 20px 40px; font-size: 18px;">
                Book New Appointment
            </a>
            
            <a href="my-appointments.php" class="btn back-btn" style="padding: 20px 40px; font-size: 18px;">
                View My Appointments
            </a>
        </div>

        <div style="text-align: center; margin-top: 40px; padding: 20px; background: #f0f7fa; border-radius: 8px;">
            <h3>Your Details</h3>
            <p><strong>User ID:</strong> <?= $_SESSION["user_id"] ?></p>
            <p><strong>Role:</strong> <?= ucfirst($_SESSION["role"]) ?></p>
        </div>
    </div>
</body>
</html>
