<?php
session_start();

if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $apptTime = $_POST["time_slot"] ?? "";

    $_SESSION["appointment"]["appt_time"] = $apptTime;

    header("Location: MakeAppt4.php");
    exit;
}
?>