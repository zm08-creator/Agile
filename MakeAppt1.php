<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "patient") {
    header("Location: Login.php");
    exit;
}

require_once "config/db.php";

$errors = [];
$user_id = $_SESSION["user_id"];

// Check if a Patient record already exists for this user
$stmt = $conn->prepare("SELECT PatientID, FirstName, LastName FROM Patient WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

$existingPatient = null;
if ($stmt->num_rows === 1) {
    $stmt->bind_result($existingPatientID, $existingFirstName, $existingLastName);
    $stmt->fetch();
    $existingPatient = [
        "id"   => $existingPatientID,
        "name" => $existingFirstName . " " . $existingLastName
    ];
}
$stmt->close();

$name       = $_POST["name"]       ?? ($existingPatient ? $existingPatient["name"] : "");
$location   = $_POST["location"]   ?? "";
$discussion = $_POST["discussion"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$name)       $errors[] = "Name is required.";
    if (!$location)   $errors[] = "Preferred location is required.";
    if (!$discussion) $errors[] = "Reason for visit is required.";

    if (empty($errors)) {
        $nameParts = explode(' ', trim($name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName  = $nameParts[1] ?? '';

        if ($existingPatient) {
            $stmt = $conn->prepare("UPDATE Patient SET FirstName = ?, LastName = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $firstName, $lastName, $user_id);
            $stmt->execute();
            $stmt->close();
            $patientID = $existingPatient["id"];
        } else {
            $stmt = $conn->prepare("INSERT INTO Patient (FirstName, LastName, PhoneNum, user_id) VALUES (?, ?, '', ?)");
            $stmt->bind_param("ssi", $firstName, $lastName, $user_id);
            $stmt->execute();
            $patientID = $conn->insert_id;
            $stmt->close();
        }

    $_SESSION["appointment"] = [
        "patient_id" => $patientID,
        "full_name"  => $name,
        "location"   => $location,
        "discussion" => $discussion
    ];
    unset($_SESSION["appt_error"]); // ← add this line

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
                <label>Preferred Location:</label>
                <label><input type="radio" name="location" value="preston"    <?= $location === "preston"    ? "checked" : "" ?> required> Preston</label>
                <label><input type="radio" name="location" value="burnley"    <?= $location === "burnley"    ? "checked" : "" ?>> Burnley</label>
                <label><input type="radio" name="location" value="west-lakes" <?= $location === "west-lakes" ? "checked" : "" ?>> West Lakes</label>
            </div>

            <div class="form-group">
                <label for="discussion">Reason for visit:</label>
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