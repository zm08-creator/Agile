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
        <!-- Logo + Title -->
        <div class="patient-logo-section">
            <img src="https://seeklogo.com/images/U/uclan-university-of-central-lancashire-logo-433207.png" 
                 alt="UCLan Logo" 
                 class="uclan-logo"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDUiIGhlaWdodD0iNDUiIHZpZXdCb3g9IjAgMCA0NSA0NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjIuNSIgY3k9IjIyLjUiIHI9IjIyLjUiIGZpbGw9IiM3NDRCMUQiLz4KPHRleHQgeD0iMjIuNSIgeT0iMzAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPkNMQU48L3RleHQ+Cjwvc3ZnPgo='">
            <h1 class="health-matters-title">HEALTH MATTERS</h1>
        </div>

        <!-- Menu Items -->
        <div class="patient-menu">
            <a href="MakeAppt1.php">Book an Appointment</a>
            <a href="#" class="disabled">Advice Sheets</a>
            <a href="#" class="disabled">Profile Details</a>
            <a href="#" class="disabled">Notifications</a>
        </div>

        <!-- Search + Profile -->
        <div class="patient-search-section">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search appointments..." readonly>
                <i class="fas fa-search search-icon"></i>
            </div>
            <div class="profile-section">
                <div class="profile-avatar">
                    <?= strtoupper(substr($_SESSION["username"], 0, 2)) ?>
                </div>
                <span><?= htmlspecialchars($_SESSION["username"]) ?></span>
            </div>
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
