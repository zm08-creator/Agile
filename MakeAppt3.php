<?php
session_start();

// Only allow patients
if (!isset($_SESSION["role"]) || strtolower($_SESSION["role"]) !== "patient") {
    header("Location: Login.php");
    exit;
}

if (!isset($_SESSION["appointment"]) || !isset($_SESSION["appointment"]["appt_date"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$errors = [];
$apptTime = $_POST["time_slot"] ?? "";

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
</head>

<body>
    <div class="navbar">
        <a href="PatientDash.php">My Account</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Choose Appointment Time</h1>
        <h2 class="page-subtitle">Step 3</h2>

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