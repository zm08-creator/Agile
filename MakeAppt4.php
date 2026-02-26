<?php
session_start();

// Get data from session (with fallbacks)
$name     = $_SESSION["userName"]     ?? "Not provided";
$location = $_SESSION["userLocation"] ?? "Not provided";
$date     = $_SESSION["apptDate"]     ?? "Not provided";
$time     = $_SESSION["apptTime"]     ?? "Not provided";

// Format location nicely
$locationFormatted = ucwords(str_replace("-", " ", $location));

// Generate reference number
$yearShort = date("y");
$month = date("m");
$day = date("d");

$letters = "ABCDEFGHJKMNPQRSTUVWXYZ";
$refNumber =
    "HM" .<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$appt = $_SESSION["appointment"];

$name       = $appt["name"];
$dob        = $appt["dob"];
$address    = $appt["address"];
$location   = $appt["location"];
$discussion = $appt["discussion"];
$date       = $appt["appt_date"];
$time       = $appt["appt_time"];

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

// Insert into database
$userId = $_SESSION["user_id"];

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

// Clear session appointment data
unset($_SESSION["appointment"]);
?>
    $yearShort .
    $month .
    $day .
    $letters[random_int(0, strlen($letters) - 1)] .
    $letters[random_int(0, strlen($letters) - 1)] .
    random_int(100, 999);
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
                <strong>Location:</strong>
                <span><?= htmlspecialchars($locationFormatted) ?></span>
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
            <span><?= $refNumber ?></span>
        </div>

        <div class="form-actions" style="margin-top: 30px;">
            <a href="MakeAppt1.php" class="btn-back">Book Another Appointment</a>
        </div>
    </div>
</div>
</body>
</html>
