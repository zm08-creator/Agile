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
$dbpass = "Admin123"; // replace this

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Today's date
$today = date('Y-m-d');

// Load today's appointments for practitioner soph_w (staff_id = 3)
$stmt = $pdo->prepare("
    SELECT a.appointment_time, a.notes, u.user_name AS patient_name
    FROM appointment a
    JOIN users u ON a.patient_id = u.user_id
    WHERE a.staff_id = 3
      AND a.appointment_date = :today
    ORDER BY a.appointment_time ASC
");

$stmt->execute(['today' => $today]);
$todaysAppts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Today's Appointments - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbar">
        <a href="ProfDash.php">My Account</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Today's Appointments</h1>
        <h2 class="page-subtitle"><?= date('l, F jS, Y') ?></h2>

        <?php if (empty($todaysAppts)): ?>
            <div class="no-appointments">No appointments scheduled for today.</div>
        <?php else: ?>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Time</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todaysAppts as $appt): ?>
                        <tr>
                            <td><?= htmlspecialchars($appt['patient_name']) ?></td>
                            <td><?= date('H:i', strtotime($appt['appointment_time'])) ?></td>
                            <td><?= htmlspecialchars($appt['notes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="nav-buttons" style="margin-top: 30px;">
            <a href="ProfDash.php" class="btn back-btn">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>