<?php

echo "<h1 style='color:red'>THIS IS THE FILE BEING LOADED</h1>";
exit();

session_start();

// Check login + role
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "professional") {
    header("Location: Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professional Dashboard - Health Matters</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #004b87;
            padding: 12px 25px;
            color: white;
            box-sizing: border-box;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-left img {
            height: 45px;
        }

        .navbar-links a {
            margin: 0 10px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .navbar-links a:hover {
            text-decoration: underline;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-bar input {
            padding: 6px 10px;
            border-radius: 4px;
            border: none;
        }

        .account-btn {
            background: white;
            color: #004b87;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }

        .account-btn:hover {
            background: #e6e6e6;
        }

        .content {
            padding: 30px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="navbar-left">
            <img src="uclan_logo.png" alt="UCLan Logo">

            <h2>HEALTH MATTERS</h2>

            <div class="navbar-links">
                <a href="#">Appointments</a>
                <a href="#">User Reports</a>
                <a href="#">Referrals</a>
                <a href="#">Advice Sheets</a>
                <a href="#">Notifications</a>
            </div>
        </div>

        <div class="navbar-right">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>

            <a href="account.php" class="account-btn">My Account</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <h1>Welcome, Professional</h1>
        <p>Select an option from the navigation bar to get started.</p>
    </div>

</body>
</html>
