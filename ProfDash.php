<?php
session_start();

// Only allow professionals
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "professional") {
    header("Location: Login.php");
    exit();
}

// Include the professional navigation bar
include "professional_navigation.php";
?>
