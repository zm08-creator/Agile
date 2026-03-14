<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: Login.php");
    exit;
}

require_once "config/db.php";

// ── DISPLAY CONFIRMATION (after successful booking) ───────────────────────────
if (isset($_SESSION["booking_confirmation"])) {
    $conf = $_SESSION["booking_confirmation"];
    unset($_SESSION["booking_confirmation"]);

    $name              = $conf["full_name"];
    $location          = $conf["location"];
    $discussion        = $conf["discussion"];
    $date              = $conf["appt_date"];
    $time              = $conf["appt_time"];
    $end_time          = $conf["end_time"];
    $refNumber         = $conf["ref_number"];
    $locationFormatted = ucwords(str_replace("-", " ", $location));

// ── PROCESS BOOKING ───────────────────────────────────────────────────────────
} elseif (isset($_SESSION["appointment"]["appt_time"])) {
    $appt       = $_SESSION["appointment"];
    $patient_id = $appt["patient_id"];
    $date       = $appt["appt_date"];
    $location   = $appt["location"];
    $discussion = $appt["discussion"];
    $time       = $appt["appt_time"];
    $start_time = $time . ":00";
    $end_time   = date('H:i:s', strtotime($time . ":00 +1 hour"));

    // Check if patient already has a booking at this date and time
    $stmt = $conn->prepare("
        SELECT BookingID FROM Bookings
        WHERE PatientID = ? AND Date = ? AND StartTime = ?
    ");
    $stmt->bind_param("iss", $patient_id, $date, $start_time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION["appt_error"] = "You already have an appointment at this time. Please choose a different slot.";
        $stmt->close();
        header("Location: MakeAppt3.php");
        exit;
    }
    $stmt->close();

    // Find an available doctor for this date and time slot
    $stmt = $conn->prepare("
        SELECT DoctorID FROM Doctor
        WHERE DoctorID NOT IN (
            SELECT DoctorID FROM Bookings
            WHERE Date = ? AND StartTime = ?
        )
        LIMIT 1
    ");
    $stmt->bind_param("ss", $date, $start_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION["appt_error"] = "No doctors are available for this time slot. Please choose a different time.";
        $stmt->close();
        header("Location: MakeAppt3.php");
        exit;
    }
    $doctorID = $result->fetch_assoc()["DoctorID"];
    $stmt->close();

    // Find an available room for this date and time slot
    $stmt = $conn->prepare("
        SELECT RoomID FROM Room
        WHERE RoomID NOT IN (
            SELECT RoomID FROM Bookings
            WHERE Date = ? AND StartTime = ?
        )
        LIMIT 1
    ");
    $stmt->bind_param("ss", $date, $start_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION["appt_error"] = "No rooms are available for this time slot. Please choose a different time.";
        $stmt->close();
        header("Location: MakeAppt3.php");
        exit;
    }
    $roomID = $result->fetch_assoc()["RoomID"];
    $stmt->close();

    // Generate reference number
    $letters   = "ABCDEFGHJKMNPQRSTUVWXYZ";
    $refNumber = "HM" . date("y") . date("m") . date("d") .
                 $letters[random_int(0, strlen($letters) - 1)] .
                 $letters[random_int(0, strlen($letters) - 1)] .
                 random_int(100, 999);

    // Insert the booking
    $stmt = $conn->prepare("
        INSERT INTO Bookings (PatientID, DoctorID, RoomID, StartTime, EndTime, Date, Location, Discussion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiisssss", $patient_id, $doctorID, $roomID, $start_time, $end_time, $date, $location, $discussion);

    if (!$stmt->execute()) {
        die("Booking failed: " . $conn->error);
    }
    $stmt->close();

    // Store confirmation and redirect to prevent duplicate on refresh
    $_SESSION["booking_confirmation"] = [
        "full_name"  => $appt["full_name"],
        "location"   => $location,
        "discussion" => $discussion,
        "appt_date"  => $date,
        "appt_time"  => $time,
        "end_time"   => $end_time,
        "ref_number" => $refNumber
    ];

    unset($_SESSION["appointment"]);
    header("Location: MakeAppt4.php");
    exit;

// ── NOTHING IN SESSION — send back to start ───────────────────────────────────
} else {
    header("Location: MakeAppt1.php");
    exit;
}
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
                    <strong>Location:</strong>
                    <span><?= htmlspecialchars($locationFormatted) ?></span>
                </div>
                <div class="detail-item">
                    <strong>Reason for Visit:</strong>
                    <span><?= htmlspecialchars($discussion) ?></span>
                </div>
                <div class="detail-item">
                    <strong>Appointment Date:</strong>
                    <span><?= date('l, F jS, Y', strtotime($date)) ?></span>
                </div>
                <div class="detail-item">
                    <strong>Time Slot:</strong>
                    <span><?= date('g:i A', strtotime($time)) ?> – <?= date('g:i A', strtotime($end_time)) ?></span>
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