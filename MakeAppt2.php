<?php
session_start();

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
