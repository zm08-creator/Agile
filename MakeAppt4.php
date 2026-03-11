<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Allow both "patient" and "service_user"
$role = strtolower($_SESSION["role"] ?? "");

if (!isset($_SESSION["user_id"]) || !in_array($role, ["patient", "service_user"])) {
    header("Location: Login.php");
    exit;
}

// Must have appointment data
if (!isset($_SESSION["appointment"])) {
    header("Location: MakeAppt1.php");
    exit;
}

$appt = $_SESSION["appointment"];

$name       = $appt["name"]        ?? "";
$dob        = $appt["dob"]         ?? "";
$address    = $appt["address"]     ?? "";
$location   = $appt["location"]    ?? "";
$discussion = $appt["discussion"]  ?? "";
$date       = $appt["appt_date"]   ?? "";
$time       = $appt["appt_time"]   ?? "";

// Basic validation (ensure date and time exist)
$errors = [];
if (!$date) $errors[] = "Appointment date missing.";
if (!$time) $errors[] = "Appointment time missing.";

if (!empty($errors)) {
    // Show errors and stop before DB work
    // (You can render these in the HTML below)
} else {
    // Build notes by concatenating the collected fields so practitioners can see context
    $notesParts = [];
    if ($name !== "") $notesParts[] = "Name: $name";
    if ($dob !== "")  $notesParts[] = "DOB: $dob";
    if ($address !== "") $notesParts[] = "Address: $address";
    if ($location !== "") $notesParts[] = "Location: $location";
    if ($discussion !== "") $notesParts[] = "Discussion: $discussion";

    $notes = implode(" | ", $notesParts);
    // Ensure notes fit into varchar(255)
    if (strlen($notes) > 255) {
        $notes = substr($notes, 0, 252) . '...';
    }

    // PostgreSQL connection
    $host   = "localhost";
    $port   = "5432";
    $dbname = "agile_db";
    $dbuser = "postgres";
    $dbpass = "Admin123"; // keep secure in production

    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert into existing appointment table
        $patientId = (int)$_SESSION["user_id"];
        $staffId   = 3;            // default practitioner id (change if needed)
        $status    = true;         // default appointment_status (booked)

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO appointment
            (patient_id, staff_id, appointment_status, appointment_date, appointment_time, notes)
            VALUES (:patient_id, :staff_id, :status, :appointment_date, :appointment_time, :notes)
        ");

        $stmt->execute([
            ':patient_id'       => $patientId,
            ':staff_id'         => $staffId,
            ':status'           => $status,
            ':appointment_date' => $date,
            ':appointment_time' => $time,
            ':notes'            => $notes
        ]);

        $pdo->commit();

        // Generate a reference number for display only (not stored in DB)
        $yearShort = date("y");
        $month = date("m");
        $day = date("d");
        $letters = "ABCDEFGHJKMNPQRSTUVWXYZ";
        $refNumber =
            "HM" .
            $yearShort .
            $month .
            $day .
            $letters[random_int(0, strlen($letters) - 1)] .
            $letters[random_int(0, strlen($letters) - 1)] .
            random_int(100, 999);

        // Clear session appointment data
        unset($_SESSION["appointment"]);

    } catch (PDOException $e) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
        $errors[] = "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
        $errors[] = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Confirmed - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbar">
        <a href="PatientDash.php">My Account</a>
    </div>

    <div class="page-wrapper">
        <h1 class="page-title">Appointment Confirmed</h1>
        <h2 class="page-subtitle">Your booking has been processed</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="confirmation">
                <div class="details-grid">
                    <div class="detail-item">
                        <strong>Name:</strong>
                        <span><?= htmlspecialchars($name) ?></span>
                    </div>

                    <div class="detail-item">
                        <strong>Date of Birth:</strong>
                        <span><?= htmlspecialchars($dob) ?></span>
                    </div>

                    <div class="detail-item">
                        <strong>Address:</strong>
                        <span><?= htmlspecialchars($address) ?></span>
                    </div>

                    <div class="detail-item">
                        <strong>Location:</strong>
                        <span><?= htmlspecialchars(ucwords(str_replace("-", " ", $location))) ?></span>
                    </div>

                    <div class="detail-item">
                        <strong>What you want to discuss:</strong>
                        <span><?= htmlspecialchars($discussion) ?></span>
                    </div>

                    <div class="detail-item">
                        <strong>Appointment Date:</strong>
                        <span><?= htmlspecialchars($date) ?></span>
                    </div>

                    <div class="detail-item">
                        <strong>Time Slot:</strong>
                        <span><?= htmlspecialchars($time) ?></span>
                    </div>

                    <div class="detail-item" style="grid-column: 1 / -1; background: #e8f7e8; border-left-color: #27ae60;">
                        <strong>Reference Number:</strong>
                        <span style="font-size: 18px; font-weight: bold; color: #27ae60;"><?= htmlspecialchars($refNumber ?? '') ?></span>
                    </div>
                </div>

                <div class="nav-buttons" style="justify-content: center; margin-top: 40px;">
                    <a href="MakeAppt1.php" class="btn">Book Another Appointment</a>
                    <a href="PatientDash.php" class="btn back-btn">Finish</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>