<?php
// Initialize variables to avoid errors
$name = $dob = $address = $location = $discussion = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"] ?? "";
    $dob = $_POST["dob"] ?? "";
    $address = $_POST["address"] ?? "";
    $location = $_POST["location"] ?? "";
    $discussion = $_POST["discussion"] ?? "";
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

    <form method="post" action="">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required value="<?= htmlspecialchars($dob) ?>">
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($address) ?></textarea>
        </div>

        <div class="form-group">
            <span>Preferred Location:</span>
            <label>
                <input type="radio" name="location" value="preston" required <?= $location === "preston" ? "checked" : "" ?>>
                Preston
            </label>
            <label>
                <input type="radio" name="location" value="burnley" <?= $location === "burnley" ? "checked" : "" ?>>
                Burnley
            </label>
            <label>
                <input type="radio" name="location" value="west-lakes" <?= $location === "west-lakes" ? "checked" : "" ?>>
                West Lakes
            </label>
        </div>

        <div class="form-group">
            <label for="discussion">What would you like to discuss?</label>
            <textarea id="discussion" name="discussion" rows="4" required><?= htmlspecialchars($discussion) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit">Submit</button>
        </div>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <h2>Submitted Information</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>DOB:</strong> <?= htmlspecialchars($dob) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
        <p><strong>Discussion:</strong> <?= htmlspecialchars($discussion) ?></p>
    <?php endif; ?>

</div>
</body>
</html>
