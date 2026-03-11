<?php
session_start();

// Only allow practitioners
if (!isset($_SESSION["role"]) || strtolower($_SESSION["role"]) !== "practitioner") {
    header("Location: Login.php");
    exit;
}

// PostgreSQL connection
$host   = "localhost";
$port   = "5432";
$dbname = "agile_db";
$dbuser = "postgres";
$dbpass = "YOUR_POSTGRES_PASSWORD_HERE"; // <-- replace this

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check for date filter
$dateFilter = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : null;

// Query based on filter
if ($dateFilter) {
    $stmt = $pdo->prepare("
        SELECT full_name, dob, address, appointment_date, appointment_time, discussion, location, created_at
        FROM appointments
        WHERE DATE(appointment_date) = :date
        ORDER BY appointment_time ASC
    ");
    $stmt->execute(['date' => $dateFilter]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pageTitle = "Appointments for " . date('d/m/Y', strtotime($dateFilter));
} else {
    $stmt = $pdo->prepare("
        SELECT full_name, dob, address, appointment_date, appointment_time, discussion, location, created_at
        FROM appointments
        ORDER BY appointment_date DESC, appointment_time ASC
    ");
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pageTitle = "All Appointments";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Professional</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- PROFESSIONAL NAVBAR -->
    <nav class="patient-navbar">

        <!-- Top Row -->
        <div class="navbar-top">
            <div class="navbar-brand">
                <img src="logo.png" alt="UCLan Logo" class="uclan-logo">
                <h1 class="site-title">HEALTH MATTERS</h1>
            </div>

            <div class="navbar-right">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." readonly>
                </div>
                <a href="ProfDash.php" class="my-account-link">
                    My Account
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="navbar-bottom">
            <div class="navbar-dropdown">
                <a href="#" class="navbar-dropdown-toggle">
                    Appointments <i class="fas fa-chevron-down" style="font-size:11px; margin-left:4px;"></i>
                </a>
                <div class="navbar-dropdown-menu">
                    <a href="ProfCalendar.php">Calendar View</a>
                    <a href="ProfTodayAppts.php">Today's Appointments</a>
                </div>
            </div>

            <a href="#">User Reports</a>
            <a href="#">Referrals</a>
            <a href="#">Advice Sheets</a>
            <a href="#">Notifications</a>
        </div>

    </nav>

    <div class="page-wrapper">
        <div class="container">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>

            <?php if ($dateFilter): ?>
                <p class="page-subtitle">Selected from calendar: <?= date('l, jS F Y', strtotime($dateFilter)) ?></p>
            <?php endif; ?>

            <?php if (empty($appointments)): ?>
                <div class="no-appointments">
                    <?php if ($dateFilter): ?>
                        No appointments found for <?= date('d/m/Y', strtotime($dateFilter)) ?>.
                    <?php else: ?>
                        No appointments found.
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Discussion</th>
                            <th>Location</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td><?= htmlspecialchars($appt['full_name']) ?></td>
                                <td><?= date('d/m/Y', strtotime($appt['appointment_date'])) ?></td>
                                <td><?= date('H:i', strtotime($appt['appointment_time'])) ?></td>
                                <td><?= htmlspecialchars($appt['discussion']) ?></td>
                                <td><?= htmlspecialchars(ucwords(str_replace('-', ' ', $appt['location']))) ?></td>
                                <td><?= date('d/m H:i', strtotime($appt['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="nav-buttons" style="margin-top: 30px;">
                <a href="ProfCalendar.php" class="btn back-btn">Back to Calendar View</a>
                <a href="ProfDash.php" class="btn back-btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>