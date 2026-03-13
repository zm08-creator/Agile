<?php
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

if (!isset($_SESSION["appointment"]) || !isset($_SESSION["appointment"]["appt_date"])) {
    header("Location: MakeAppt1.php");
    exit;
}

require_once "config/db.php";

$errors = [];
$apptTime = $_POST["time_slot"] ?? "";
$apptDate = $_SESSION["appointment"]["appt_date"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$apptTime) {
        $errors[] = "Please select a time slot.";
    }

    if (empty($errors)) {
        $_SESSION["appointment"]["appt_time"] = $apptTime;
        header("Location: MakeAppt4.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Appointment Time - Health Matters</title>
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
        <h1 class="page-title">Choose Appointment Time</h1>
        <h2 class="page-subtitle">Step 3 - <?= date('l, F jS, Y', strtotime($apptDate)) ?></h2>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="MakeAppt3.php">
            <div class="form-group">
                <label>Choose your preferred time slot:</label>
                
                <label class="time-slot">
                    <input type="radio" name="time_slot" value="09:00" <?= $apptTime === "09:00" ? "checked" : "" ?> required>
                    9:00 AM – 10:00 AM
                </label>

                <label class="time-slot">
                    <input type="radio" name="time_slot" value="10:00" <?= $apptTime === "10:00" ? "checked" : "" ?>>
                    10:00 AM – 11:00 AM
                </label>

                <label class="time-slot">
                    <input type="radio" name="time_slot" value="11:00" <?= $apptTime === "11:00" ? "checked" : "" ?>>
                    11:00 AM – 12:00 PM
                </label>

                <label class="time-slot">
                    <input type="radio" name="time_slot" value="14:00" <?= $apptTime === "14:00" ? "checked" : "" ?>>
                    2:00 PM – 3:00 PM
                </label>

                <label class="time-slot">
                    <input type="radio" name="time_slot" value="15:00" <?= $apptTime === "15:00" ? "checked" : "" ?>>
                    3:00 PM – 4:00 PM
                </label>

                <label class="time-slot">
                    <input type="radio" name="time_slot" value="16:00" <?= $apptTime === "16:00" ? "checked" : "" ?>>
                    4:00 PM – 5:00 PM
                </label>
            </div>

            <div class="nav-buttons">
                <a href="MakeAppt2.php" class="btn back-btn">Back</a>
                <button type="submit" class="btn">Continue</button>
            </div>
        </form>
    </div>
</body>
</html>