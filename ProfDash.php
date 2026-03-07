<?php
session_start();

// MUST be logged in as Professional (user_id = 2)
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 2) {
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
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="ProfDash.php">Dashboard</a>
        <a href="Login.php?logout=1" class="logout-link">Logout</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        <h2 class="page-subtitle">Please select an option</h2>

        <div class="dashboard-actions">
            <a href="MakeAppt1.php" class="btn">
                Make New Appointment
            </a>

            <a href="ProfAllAppts.php" class="btn back-btn">
                View All Appointments
            </a>

            <a href="ProfTodayAppts.php" class="btn back-btn">
                Today's Appointments
            </a>
        </div>
    </div>
</body>
</html>
