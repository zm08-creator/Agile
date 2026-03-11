<?php
session_start();

// Allow both "patient" and "service_user"
$role = strtolower($_SESSION["role"] ?? "");

if (!isset($_SESSION["user_id"]) || !in_array($role, ["patient", "service_user"])) {
    header("Location: Login.php");
    exit;
}

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
        $_SESSION["appointment"] = [
            "name" => $name,
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
</head>

<body>
    <div class="navbar">
        <a href="PatientDash.php">My Account</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Make an Appointment</h1>
        <h2 class="page-subtitle">Step 1</h2>

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
                <a href="index.php" class="btn back-btn">Back</a>
                <button type="submit" class="btn">Next</button>
            </div>
        </form>
    </div>
</body>
</html>