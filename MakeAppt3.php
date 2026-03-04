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
    <title>Choose Appointment Time</title>
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

        /* Page Heading */
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
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        .time-slot {
            display: block;
            padding: 10px;
            border: 2px solid #156082;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .time-slot input {
            margin-right: 10px;
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
    <h1 class="page-title">Choose Appointment Time</h1>
    <h2 class="page-subtitle">Step 3</h2>

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

        <!-- Back + Continue Buttons -->
        <div class="nav-buttons">
            <a href="MakeAppt2.php" class="btn back-btn">Back</a>
            <button type="submit" class="btn">Continue</button>
        </div>

    </form>
</div>

</body>
</html>
