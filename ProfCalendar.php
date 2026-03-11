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
$dbpass = "Admin123"; // <-- replace this

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get current month/year or from GET params
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');

// Get days with appointments for this month
$stmt = $pdo->prepare("
    SELECT DISTINCT DATE(appointment_date) AS appt_date
    FROM appointments
    WHERE EXTRACT(YEAR FROM appointment_date) = :year
      AND EXTRACT(MONTH FROM appointment_date) = :month
    ORDER BY appointment_date
");

$stmt->execute([
    'year'  => $year,
    'month' => $month
]);

$apptDays = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build array of dates with appointments
$apptDates = [];
foreach ($apptDays as $day) {
    $apptDates[$day['appt_date']] = true;
}

// Calendar logic
$firstDay     = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth  = date('t', $firstDay);
$dayOfWeek    = date('w', $firstDay);
$monthName    = date('F Y', $firstDay);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Calendar - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .calendar-container { max-width: 800px; margin: 20px auto; }
        .calendar-header { text-align: center; margin-bottom: 20px; }
        .calendar-nav { margin-bottom: 15px; }
        .calendar-nav a { padding: 8px 15px; margin: 0 5px; }
        .calendar { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .calendar th, .calendar td { padding: 15px; text-align: center; border: 1px solid #ddd; vertical-align: top; height: 100px; }
        .calendar th { background: linear-gradient(135deg, #156082 0%, #104a63 100%); color: white; font-weight: 600; }
        .calendar-day.has-appointment a {
            text-decoration: underline;
            color: #156082;
            font-weight: bold;
            cursor: pointer;
        }
        .calendar-day.has-appointment:hover a { color: #104a63; }
        .calendar-day.today { background: #e8f7e8; }
    </style>
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
        <div class="calendar-container">
            <h1 class="page-title">Appointment Calendar</h1>
            <h2 class="page-subtitle"><?= htmlspecialchars($monthName) ?></h2>

            <!-- Navigation -->
            <div class="calendar-header">
                <div class="calendar-nav">
                    <a href="?month=<?= $month-1 ?>&year=<?= $year ?>" class="btn back-btn">&lt;&lt; Prev Month</a>
                    <a href="?month=<?= $month+1 ?>&year=<?= $year ?>" class="btn">&gt;&gt; Next Month</a>
                </div>
            </div>

            <!-- Calendar Table -->
            <table class="calendar">
                <thead>
                    <tr>
                        <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dayCounter = 1;

                    echo '<tr>';
                    for ($i = 0; $i < $dayOfWeek; $i++) {
                        echo '<td></td>';
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $isToday = ($currentDate === date('Y-m-d'));
                        $hasAppt = isset($apptDates[$currentDate]);

                        $dayClass = 'calendar-day';
                        if ($isToday) $dayClass .= ' today';
                        if ($hasAppt) $dayClass .= ' has-appointment';

                        echo '<td class="' . $dayClass . '">';
                        if ($hasAppt) {
                            echo '<a href="ProfAllAppts.php?date=' . $currentDate . '">' . $day . '</a>';
                        } else {
                            echo $day;
                        }
                        echo '</td>';

                        if (($dayOfWeek + $dayCounter) % 7 == 0 && $day < $daysInMonth) {
                            echo '</tr><tr>';
                        }
                        $dayCounter++;
                    }

                    $remainingCells = 7 - (($dayOfWeek + $daysInMonth) % 7);
                    if ($remainingCells > 0 && $remainingCells != 7) {
                        for ($i = 0; $i < $remainingCells; $i++) {
                            echo '<td></td>';
                        }
                    }
                    echo '</tr>';
                    ?>
                </tbody>
            </table>

            <div class="nav-buttons" style="margin-top: 30px;">
                <a href="ProfDash.php" class="btn back-btn">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>