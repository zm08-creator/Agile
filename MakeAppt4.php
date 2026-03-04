<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "config/db.php";

// Ensure previous steps completed
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

// Insert into database
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

// Clear appointment data so refresh doesn’t re‑insert
unset($_SESSION["appointment"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Matters - Appointment Confirmed</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
    <h1>Health Matters</h1>

    <div class="confirmation">
        <h2>Appointment Confirmed</h2>

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
        </div>

        <div class="detail-item" style="grid-column: 1 / -1;">
            <strong>Reference Number:</strong>
            <span><?= htmlspecialchars($refNumber) ?></span>
        </div>

        <div class="form-actions" style="margin-top: 30px;">
            <a href="MakeAppt1.php" class="btn-back">Book Another Appointment</a>
        </div>
    </div>
</div>
</body>
</html>
