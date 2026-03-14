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

$today = date('Y-m-d');

$stmt = $conn->prepare("
    SELECT b.BookingID, b.StartTime, b.EndTime, b.Location, b.Discussion,
           p.FirstName, p.LastName, p.PhoneNum,
           r.RoomType
    FROM Bookings b
    JOIN Patient p ON b.PatientID = p.PatientID
    JOIN Room r ON b.RoomID = r.RoomID
    WHERE b.DoctorID = ? AND b.Date = ?
    ORDER BY b.StartTime ASC
");
$stmt->bind_param("is", $doctor_id, $today);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's Appointments - Health Matters</title>
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
    <h1 class="page-title">Today's Appointments</h1>
    <h2 class="page-subtitle"><?= date('l, F jS, Y') ?></h2>

    <div class="dashboard-actions">
        <a href="ProfDash.php" class="btn back-btn">← Back to Dashboard</a>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="appointments-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="appointment-card">
                    <h3><?= htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) ?></h3>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($row['PhoneNum'] ?? 'N/A') ?></p>
                    <p><strong>Time:</strong> <?= date('g:i A', strtotime($row['StartTime'])) ?> – <?= date('g:i A', strtotime($row['EndTime'])) ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars(ucwords(str_replace('-', ' ', $row['Location']))) ?></p>
                    <p><strong>Room:</strong> <?= htmlspecialchars($row['RoomType']) ?></p>
                    <p><strong>Reason for Visit:</strong> <?= htmlspecialchars($row['Discussion']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-appointments">
            <h3>No appointments today</h3>
            <p>Check back later or view all appointments.</p>
            <a href="ProfAllAppts.php" class="btn">View All Appointments</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>