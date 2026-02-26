<?php
session_start();

if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$errors = [];
$apptDate = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $apptDate = trim($_POST["appt_date"]);

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