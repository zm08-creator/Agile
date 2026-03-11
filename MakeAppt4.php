<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Only allow patients
if (!isset($_SESSION["role"]) || strtolower($_SESSION["role"]) !== "patient") {
    header("Location: Login.php");
    exit;
}

// Must have appointment data
if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$appt = $_SESSION["appointment"];

$name       = $appt["name"]        ?? "";
$dob        = $appt["dob"]         ?? "";
$address    = $appt["address"]     ?? "";
$location   = $appt["location"]    ?? "";
$discussion = $appt["discussion"]  ?? "";
$date       = $appt["appt_date"]   ?? "";
$time       = $appt["appt_time"]   ?? "";

$locationFormatted = ucwords(str_replace("-", " ", $location));

// Generate reference number
$yearShort = date("y");
$month = date("m");
$day = date("d");
$letters = "ABCDEFGHJKMNPQRSTUVWXYZ";

$refNumber =
    "HM" .
    $yearShort .
    $month .
    $day .
    $letters[random_int(0, strlen($letters) - 1)] .
    $letters[random_int(0, strlen($letters) - 1)] .
    random_int(100, 999);

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

// Insert appointment
$userId = $_SESSION["user_id"] ?? null;

if ($userId !== null) {
    $stmt = $pdo->prepare("
        INSERT INTO appointments 
        (user_id, full_name, dob, address, location, discussion, appointment_date, appointment_time, reference_number)
        VALUES (:user_id, :full_name, :dob, :address, :location, :discussion, :appointment_date, :appointment_time, :ref)
    ");

    $stmt->execute([
        'user_id'          => $userId,
        'full_name'        => $name,
        'dob'              => $dob,
        'address'          => $address,
        'location'         => $location,
        'discussion'       => $discussion,
        'appointment_date' => $date,
        'appointment_time' => $time,
        'ref'              => $refNumber
    ]);
}

// Clear session appointment data
unset($_SESSION["appointment"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Confirmed - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbar">
        <a href="PatientDash.php">My Account</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Appointment Confirmed</h1>
        <h2 class="page-subtitle">Your booking has been successfully created</h2>

        <div class="confirmation">
            <div class="details-grid">
                <div class="detail-item">
                    <strong>Name:</strong>
                    <span><?= htmlspecialchars($name) ?></span>
                </div>

                <div class="detail-item">
                    <strong>Date of Birth:</strong>
                    <span><?= htmlspecialchars($dob) ?></span>
                </div>

                <div class="detail-item">
                    <strong>Address:</strong>
                    <span><?= htmlspecialchars($address) ?></span>
                </div>

                <div class="detail-item">
                    <strong>Location:</strong>
                    <span><?= htmlspecialchars($locationFormatted) ?></span>
                </div>

                <div class="detail-item">
                    <strong>What you want to discuss:</strong>
                    <span><?= htmlspecialchars($discussion) ?></span>
                </div>

                <div class="detail-item">
                    <strong>Appointment Date:</strong>
                    <span><?= htmlspecialchars($date) ?></span>
                </div>

                <div class="detail-item">
                    <strong>Time Slot:</strong>
                    <span><?= htmlspecialchars($time) ?></span>
                </div>

                <div class="detail-item" style="grid-column: 1 / -1; background: #e8f7e8; border-left-color: #27ae60;">
                    <strong>Reference Number:</strong>
                    <span style="font-size: 18px; font-weight: bold; color: #27ae60;"><?= htmlspecialchars($refNumber) ?></span>
                </div>
            </div>

            <div class="nav-buttons" style="justify-content: center; margin-top: 40px;">
                <a href="MakeAppt1.php" class="btn">Book Another Appointment</a>
                <a href="PatientDash.php" class="btn back-btn">Finish</a>
            </div>
        </div>
    </div>
</body>
</html>