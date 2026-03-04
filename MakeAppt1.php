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
    <title>Make an Appointment</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
        }

        /* Top Navigation Bar */
        .navbar {
            background-color: #156082;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        /* Page Container */
        .page-wrapper {
            width: 70%;
            margin: 30px auto;
        }

        /* Page Heading (matches UI doc style) */
        .page-title {
            font-family: 'Adlam Display', sans-serif;
            font-size: 28px;
            color: #156082;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 18px;
            color: #333;
            margin-bottom: 25px;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #156082;
            border-radius: 5px;
            font-size: 15px;
        }

        .error-messages p {
            background: #ffdddd;
            padding: 10px;
            border-left: 4px solid red;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn {
            background-color: #156082;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #104a63;
        }

        .back-btn {
            background-color: #888;
        }
    </style>
</head>

<body>

<!-- Top Navigation Bar -->
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="account.php">My Account</a>
</div>

<div class="page-wrapper">

    <!-- Page Heading -->
    <h1 class="page-title">Make an Appointment</h1>
    <h2 class="page-subtitle">Service Users</h2>

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
            <input type="text" id="name" name="name" required
                   value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required
                   value="<?= htmlspecialchars($dob) ?>">
        </div>

        <div class="form-group">
            <label for="address">Home Address:</label>
            <textarea id="address" name="address" rows="3" required><?= htmlspecialchars($address) ?></textarea>
        </div>

        <div class="form-group">
            <label>Preferred Location:</label>

            <label><input type="radio" name="location" value="preston" <?= $location === "preston" ? "checked" : "" ?> required> Preston</label>
            <label><input type="radio" name="location" value="burnley" <?= $location === "burnley" ? "checked" : "" ?>> Burnley</label>
            <label><input type="radio" name="location" value="west-lakes" <?= $location === "west-lakes" ? "checked" : "" ?>> West Lakes</label>
        </div>

        <div class="form-group">
            <label for="discussion">What would you like to discuss?</label>
            <textarea id="discussion" name="discussion" rows="4" required><?= htmlspecialchars($discussion) ?></textarea>
        </div>

        <!-- Back + Next Buttons -->
        <div class="nav-buttons">
            <a href="index.php" class="btn back-btn">Back</a>
            <button type="submit" class="btn">Next</button>
        </div>

    </form>
</div>

</body>
</html>
