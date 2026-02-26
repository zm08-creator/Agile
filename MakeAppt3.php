<?php
session_start();

// Step 1 and 2 must be completed
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
    <title>Health Matters - Choose Time</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
    <h1>Health Matters</h1>

    <h2>Step 3: Choose Appointment Time</h2>

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
                <input type="radio" name="time_slot" value="09:00" required>
                9:00 AM – 10:00 AM
            </label>

            <label class="time-slot">
                <input type="radio" name="time_slot" value="10:00">
                10:00 AM – 11:00 AM
            </label>

            <label class="time-slot">
                <input type="radio" name="time_slot" value="11:00">
                11:00 AM – 12:00 PM
            </label>

            <label class="time-slot">
                <input type="radio" name="time_slot" value="14:00">
                2:00 PM – 3:00 PM
            </label>

            <label class="time-slot">
                <input type="radio" name="time_slot" value="15:00">
                3:00 PM – 4:00 PM
            </label>

            <label class="time-slot">
                <input type="radio" name="time_slot" value="16:00">
                4:00 PM – 5:00 PM
            </label>
        </div>

        <div class="form-actions">
            <button type="submit">Continue</button>
        </div>
    </form>
</div>
</body>
</html>