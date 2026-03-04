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
    <title>Choose Appointment Date</title>
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
            margin-bottom: 6px;
        }

        input[type="date"] {
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
            <input
                type="date"
                id="appt_date"
                name="appt_date"
                required
                min="<?= date('Y-m-d') ?>"
                value="<?= htmlspecialchars($apptDate) ?>"
            >
        </div>

        <!-- Back + Continue Buttons -->
        <div class="nav-buttons">
            <a href="MakeAppt1.php" class="btn back-btn">Back</a>
            <button type="submit" class="btn">Continue</button>
        </div>

    </form>
</div>

</body>
</html>
