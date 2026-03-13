<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

require_once "config/db.php";

if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$appt = $_SESSION["appointment"];

$name       = $appt["full_name"]     ?? "";
$dob        = $appt["dob"]          ?? "";
$address    = $appt["address"]      ?? "";
$location   = $appt["location"]     ?? "";
$discussion = $appt["discussion"]   ?? "";
$date       = $appt["appt_date"]    ?? "";
$time       = $appt["appt_time"]    ?? "";
$patient_id = $appt["patient_id"]   ?? $_SESSION["user_id"];

$locationFormatted = ucwords(str_replace("-", " ", $location));

// Generate reference number (YOUR ORIGINAL CODE)
$yearShort = date("y");
$month = date("m");
$day = date("d");
$letters = "ABCDEFGHJKMNPQRSTUVWXYZ";
$refNumber = "HM" . $yearShort . $month . $day . 
             $letters[random_int(0, strlen($letters) - 1)] . 
             $letters[random_int(0, strlen($letters) - 1)] . 
             random_int(100, 999);

// **NO DEFAULT DOCTOR/ROOM - Just save patient booking data**
$start_time = $time . ":00";  
$end_time = date('H:i:s', strtotime($time . " +1 hour"));

// **INSERT INTO BOOKINGS TABLE WITHOUT DOCTOR/ROOM** (for now)
$stmt = $conn->prepare("
    INSERT INTO bookings (patient_id, start_time, end_time, date) 
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $patient_id, $start_time, $end_time, $date);

if ($stmt->execute()) {
    $success = true;
} else {
    die("Booking failed: " . $conn->error);
}
$stmt->close();

unset($_SESSION["appointment"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Confirmed - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <!-- YOUR NAVBAR EXACTLY AS-IS -->
    <nav class="patient-navbar">
        <div class="navbar-top">
            <div class="navbar-brand">
                <img src="logo.jpg" alt="UCLan Logo" class="uclan-logo">
                <h1 class="site-title">HEALTH MATTERS</h1>
            </div>
            <div class="navbar-right">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." readonly>
                </div>
                <a href="PatientDash.php" class="my-account-link">
                    My Account
                    <i class="fas fa-user-circle"></i>
                </a>
                <a href="?logout" class="my-account-link">
                    Logout
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
        <div class="navbar-bottom">
            <a href="MakeAppt1.php">Make Appointment</a>
            <a href="PatientAppts.php">My Appointments</a>
            <a href="#">Advice Sheets</a>
            <a href="#">Notifications</a>
        </div>
    </nav>

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
                    <span><?= date('l, F jS, Y', strtotime($date)) ?></span>
                </div>

                <div class="detail-item">
                    <strong>Time Slot:</strong>
                    <span><?= date('g:i A', strtotime($time)) ?> - <?= date('g:i A', strtotime($end_time)) ?></span>
                </div>

                <div class="detail-item" style="grid-column: 1 / -1; background: #e8f7e8; border-left-color: #27ae60;">
                    <strong>Reference Number:</strong>
                    <span style="font-size: 18px; font-weight: bold; color: #27ae60;"><?= htmlspecialchars($refNumber) ?></span>
                </div>
            </div>

            <div class="nav-buttons" style="justify-content: center; margin-top: 40px;">
                <a href="PatientAppts.php" class="btn">View My Appointments</a>
                <a href="PatientDash.php" class="btn back-btn">Finish</a>
            </div>
        </div>
    </div>
</body>
</html>
