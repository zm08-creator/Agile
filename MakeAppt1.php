<?php
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $dob = trim($_POST["dob"]);
    $address = trim($_POST["address"]);
    $location = trim($_POST["location"]);
    $discussion = trim($_POST["discussion"]);

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