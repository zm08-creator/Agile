<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'doctor') {
    header("Location: Login.php");
    exit;
}

require_once "config/db.php";

$user_id = $_SESSION["user_id"];

// Get the DoctorID linked to this user account
$stmt = $conn->prepare("SELECT DoctorID FROM Doctor WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("No doctor record found for this account. Please contact an administrator.");
}
$stmt->bind_result($doctor_id);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("
    SELECT b.BookingID, b.Date, b.StartTime, b.EndTime, b.Location, b.Discussion, b.created_at,
           p.FirstName, p.LastName, p.PhoneNum,
           r.RoomType
    FROM Bookings b
    JOIN Patient p ON b.PatientID = p.PatientID
    JOIN Room r ON b.RoomID = r.RoomID
    WHERE b.DoctorID = ?
    ORDER BY b.Date DESC, b.StartTime ASC
");
$stmt->bind_param("i", $doctor_id);
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
            <a href="Logout.php" class="prof-logout-link">
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
                        <th>Phone</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Room</th>
                        <th>Reason for Visit</th>
                        <th>Booked At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appt): ?>
                        <tr>
                            <td><?= htmlspecialchars($appt['FirstName'] . ' ' . $appt['LastName']) ?></td>
                            <td><?= htmlspecialchars($appt['PhoneNum'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y', strtotime($appt['Date'])) ?></td>
                            <td><?= date('H:i', strtotime($appt['StartTime'])) ?> – <?= date('H:i', strtotime($appt['EndTime'])) ?></td>
                            <td><?= htmlspecialchars(ucwords(str_replace('-', ' ', $appt['Location']))) ?></td>
                            <td><?= htmlspecialchars($appt['RoomType']) ?></td>
                            <td><?= htmlspecialchars($appt['Discussion']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($appt['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>