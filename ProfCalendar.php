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

// Get month/year
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');

// Load appointment days for practitioner soph_w (staff_id = 3)
$stmt = $pdo->prepare("
    SELECT DISTINCT appointment_date
    FROM appointment
    WHERE staff_id = 3
      AND EXTRACT(YEAR FROM appointment_date) = :year
      AND EXTRACT(MONTH FROM appointment_date) = :month
    ORDER BY appointment_date
");

$stmt->execute([
    'year'  => $year,
    'month' => $month
]);

$apptDays = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Convert to lookup array
$apptDates = [];
foreach ($apptDays as $d) {
    $apptDates[$d] = true;
}

// Calendar logic
$firstDay = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDay);
$dayOfWeek = date('w', $firstDay);
$monthName = date('F Y', $firstDay);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Calendar - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .calendar-container { max-width: 800px; margin: 20px auto; }
        .calendar { width: 100%; border-collapse: collapse; background: white; }
        .calendar th, .calendar td { padding: 15px; text-align: center; border: 1px solid #ddd; height: 100px; }
        .calendar th { background: #156082; color: white; }
        .has-appointment a { font-weight: bold; color: #156082; text-decoration: underline; }
        .today { background: #e8f7e8; }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="ProfDash.php">My Account</a>
    </div>

    <div class="calendar-container">
        <h1 class="page-title">Appointment Calendar</h1>
        <h2 class="page-subtitle"><?= htmlspecialchars($monthName) ?></h2>

        <div class="calendar-nav">
            <a href="?month=<?= $month-1 ?>&year=<?= $year ?>" class="btn">&lt;&lt; Prev</a>
            <a href="?month=<?= $month+1 ?>&year=<?= $year ?>" class="btn">Next &gt;&gt;</a>
        </div>

        <table class="calendar">
            <thead>
                <tr>
                    <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th>
                    <th>Thu</th><th>Fri</th><th>Sat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<tr>";
                for ($i = 0; $i < $dayOfWeek; $i++) echo "<td></td>";

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $isToday = ($currentDate === date('Y-m-d'));
                    $hasAppt = isset($apptDates[$currentDate]);

                    $class = "";
                    if ($isToday) $class .= "today ";
                    if ($hasAppt) $class .= "has-appointment";

                    echo "<td class='$class'>";
                    if ($hasAppt) {
                        echo "<a href='ProfAllAppts.php?date=$currentDate'>$day</a>";
                    } else {
                        echo $day;
                    }
                    echo "</td>";

                    if (($dayOfWeek + $day) % 7 == 0) echo "</tr><tr>";
                }

                echo "</tr>";
                ?>
            </tbody>
        </table>

        <div class="nav-buttons" style="margin-top: 30px;">
            <a href="ProfDash.php" class="btn back-btn">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>