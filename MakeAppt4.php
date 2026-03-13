<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "config/db.php";

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

$userId = $_SESSION["user_id"] ?? null;

if ($userId !== null) {
    $stmt = $conn->prepare("
        INSERT INTO appointments 
        (user_id, full_name, dob, address, location, discussion, appointment_date, appointment_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "isssssss",
        $userId,
        $name,
        $dob,
        $address,
        $location,
        $discussion,
        $date,
        $time
    );

    $stmt->execute();
    $stmt->close();
}

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
    <!-- PATIENT NAVBAR -->
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
                <a href="Logout.php" class="my-account-link">
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
