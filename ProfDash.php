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
    <!-- Navigation Bar (same as MakeAppt1.php) -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="ProfDash.php">Dashboard</a>
        <a href="Login.php?logout=1">Logout</a>
    </div>

    <div class="page-wrapper">
        <div class="container">
            <h1>🏥 Professional Dashboard</h1>
            <p>Manage your appointments efficiently.</p>
            
            <!-- Three Big Buttons -->
            <div class="dashboard-buttons">
                <a href="MakeApp1.php" class="dash-btn dash-btn-primary">
                    📅 Make New Appointment
                </a>
                <a href="prof-all-appts.php" class="dash-btn dash-btn-secondary">
                    📋 View All Appointments
                </a>
                <a href="prof-today-appts.php" class="dash-btn dash-btn-secondary">
                    ✨ Today's Appointments
                </a>
            </div>
        </div>
    </div>
</body>
</html>
