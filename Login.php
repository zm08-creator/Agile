<?php
// Enable error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once "config/db.php";

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Please enter username and password.";
    } else {

        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {

            $stmt->bind_result($id, $password_hash);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {

                // Determine role automatically based on username
                switch ($username) {
                    case "patient":
                        $role = "patient";
                        break;
                    case "professional":
                        $role = "practitioner";
                        break;
                    case "admin":
                        $role = "admin";
                        break;
                    default:
                        $role = "unknown"; // fallback
                }

                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;

                header("Location: MakeAppt1.php");
                exit;

            } else {
                $error = "Invalid password.";
            }

        } else {
            $error = "User not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Matters - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="Login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>