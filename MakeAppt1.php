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

require_once "config/db.php";

if (!isset($_SESSION["appointment"])) {
    $_SESSION["appointment"] = [];
}

$errors = [];

$name = $_POST["name"] ?? "";
$dob = $_POST["dob"] ?? "";
$address = $_POST["address"] ?? "";
$location = $_POST["location"] ?? "";
$discussion = $_POST["discussion"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$name) $errors[] = "Name is required.";
    if (!$dob) $errors[] = "Date of Birth is required.";
    if (!$address) $errors[] = "Address is required.";
    if (!$location) $errors[] = "Preferred location is required.";
    if (!$discussion) $errors[] = "Discussion details are required.";

    if (empty($errors)) {
        // **SAVE TO PATIENTS TABLE** (link to logged-in user)
        $patient_id = $_SESSION["user_id"];
        
        $stmt = $conn->prepare("INSERT INTO patients (patient_id, first_name, last_name, phone_num) VALUES (?, ?, ?, '') ON DUPLICATE KEY UPDATE first_name = ?, last_name = ?");
        $first_last = explode(' ', $name, 2);
        $first_name = $first_last[0] ?? '';
        $last_name = $first_last[1] ?? '';
        $stmt->bind_param("isssi", $patient_id, $first_name, $last_name, $first_name, $last_name);
        $stmt->execute();
        $stmt->close();

        // **STORE ALL YOUR ORIGINAL FIELDS IN SESSION**
        $_SESSION["appointment"] = [
            "patient_id" => $patient_id,
            "full_name" => $name,
            "dob" => $dob,
            "address" => $address,
            "location" => $location,
            "discussion" => $discussion
        ];

        header("Location: MakeAppt2.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make an Appointment - Health Matters</title>
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
        <h1 class="page-title">Make an Appointment</h1>
        <h2 class="page-subtitle">Service Users - Step 1</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="MakeAppt1.php">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name) ?>">
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required value="<?= htmlspecialchars($dob) ?>">
            </div>

            <div class="form-group">
                <label for="address">Home Address:</label>
                <textarea id="address" name="address" required><?= htmlspecialchars($address) ?></textarea>
            </div>

            <div class="form-group">
                <label>Preferred Location:</label>
                <label><input type="radio" name="location" value="preston" <?= $location === "preston" ? "checked" : "" ?> required> Preston</label>
                <label><input type="radio" name="location" value="burnley" <?= $location === "burnley" ? "checked" : "" ?>> Burnley</label>
                <label><input type="radio" name="location" value="west-lakes" <?= $location === "west-lakes" ? "checked" : "" ?>> West Lakes</label>
            </div>

            <div class="form-group">
                <label for="discussion">What would you like to discuss?</label>
                <textarea id="discussion" name="discussion" required><?= htmlspecialchars($discussion) ?></textarea>
            </div>

            <div class="nav-buttons">
                <a href="PatientDash.php" class="btn back-btn">Back</a>
                <button type="submit" class="btn">Next</button>
            </div>
        </form>
    </div>
</body>
</html>
