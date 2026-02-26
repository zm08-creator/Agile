<?php
session_start();

// Reset appointment session on first page load
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
    <title>Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Health Matters</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="MakeAppt1.php">

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required
                   value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required
                   value="<?= htmlspecialchars($dob) ?>">
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($address) ?></textarea>
        </div>

        <div class="form-group">
            <span>Preferred Location:</span>

            <label>
                <input type="radio" name="location" value="preston"
                    <?= $location === "preston" ? "checked" : "" ?> required>
                Preston
            </label>

            <label>
                <input type="radio" name="location" value="burnley"
                    <?= $location === "burnley" ? "checked" : "" ?>>
                Burnley
            </label>

            <label>
                <input type="radio" name="location" value="west-lakes"
                    <?= $location === "west-lakes" ? "checked" : "" ?>>
                West Lakes
            </label>
        </div>

        <div class="form-group">
            <label for="discussion">What would you like to discuss?</label>
            <textarea id="discussion" name="discussion" rows="4" required><?= htmlspecialchars($discussion) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Next Step</button>
        </div>

    </form>
</div>
</body>
</html>