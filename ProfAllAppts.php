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

// Get ALL appointments from bookings table (no doctor filter for test system)
$stmt = $conn->prepare("
    SELECT b.*, p.first_name, p.last_name, p.phone_num
    FROM bookings b 
    JOIN patients p ON b.patient_id = p.patient_id
    ORDER BY b.date DESC, b.start_time ASC
");
$stmt->execute();
$result = $stmt->get_result();
$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments - Professional</title>
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
        <div class="container">
            <h1 class="page-title">📋 All Appointments</h1>
            <h2 class="page-subtitle">Complete appointment history</h2>
            
            <div class="dashboard-actions">
                <a href="ProfDash.php" class="btn back-btn">← Back to Dashboard</a>
            </div>

            <?php if (empty($appointments)): ?>
                <div class="no-appointments">
                    <h3>No appointments found</h3>
                    <p>No bookings have been made yet.</p>
                </div>
            <?php else: ?>
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td><?= htmlspecialchars($appt['first_name'] . ' ' . $appt['last_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($appt['date'])) ?></td>
                                <td><?= date('H:i', strtotime($appt['start_time'])) ?> - <?= date('H:i', strtotime($appt['end_time'])) ?></td>
                                <td><?= $appt['room_id'] ? 'Room ' . $appt['room_id'] : 'TBD' ?></td>
                                <td><?= date('d/m H:i', strtotime($appt['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
