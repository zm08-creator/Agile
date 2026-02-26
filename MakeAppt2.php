<?php
session_start();

// Step 1 must be completed
if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$errors = [];
$apptDate = $_POST["appt_date"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!$apptDate) {
        $errors[] = "Appointment date is required.";
    }

    if (empty($errors)) {
        $_SESSION["appointment"]["appt_date"] = $apptDate;
        header("Location: MakeAppt3.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Matters - Choose Appointment Date</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Health Matters</h1>

    <h2>Step 2: Choose Appointment Date</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="MakeAppt2.php">

        <div class="form-group">
            <label for="appt_date">Preferred appointment date:</label>
            <input
                type="date"
                id="appt_date"
                name="appt_date"
                required
                min="<?= date('Y-m-d') ?>"
                value="<?= htmlspecialchars($apptDate) ?>"
            >
        </div>

        <div class="form-actions">
            <button type="submit">Continue</button>
        </div>

    </form>
</div>
</body>
</html>