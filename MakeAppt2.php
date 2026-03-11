<?php
session_start();

// Allow both "patient" and "service_user"
$role = strtolower($_SESSION["role"] ?? "");

if (!isset($_SESSION["user_id"]) || !in_array($role, ["patient", "service_user"])) {
    header("Location: Login.php");
    exit;
}

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
    <title>Choose Appointment Date - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbar">
        <a href="PatientDash.php">My Account</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Choose Appointment Date</h1>
        <h2 class="page-subtitle">Step 2</h2>

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
                <input type="date"
                       id="appt_date"
                       name="appt_date"
                       required
                       min="<?= date('Y-m-d') ?>"
                       value="<?= htmlspecialchars($apptDate) ?>">
            </div>

            <div class="nav-buttons">
                <a href="MakeAppt1.php" class="btn back-btn">Back</a>
                <button type="submit" class="btn">Continue</button>
            </div>
        </form>
    </div>
</body>
</html>