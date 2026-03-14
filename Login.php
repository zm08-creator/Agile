<?php
// Enable error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Please enter username and password.";
    } else {
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
                $role_query = $conn->prepare("SELECT role FROM users WHERE id = ?");
                $role_query->bind_param("i", $id);
                $role_query->execute();
                $role_result = $role_query->get_result();
                
                if ($role_result->num_rows > 0) {
                    $user = $role_result->fetch_assoc();
                    $role = $user['role'] ?? 'unknown';
                } else {
                    switch ($username) {
                        case "Patient": case "patient": $role = "patient"; break;
                        case "Professional": case "professional": $role = "doctor"; break;
                        case "Admin": case "admin": $role = "admin"; break;
                        default: $role = "unknown";
                    }
                }
                $role_query->close();

                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;

                switch ($role) {
                    case "patient":
                        header("Location: PatientDash.php");
                        break;
                    case "doctor":
                        header("Location: ProfDash.php");
                        break;
                    case "admin":
                        header("Location: AdminDash.php");
                        break;
                    default:
                        header("Location: index.php");
                }
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
    <title>Login - Health Matters</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <nav class="patient-navbar">

        <div class="navbar-top">
            <div class="navbar-brand">
                <img src="logo.jpg" alt="UCLan Logo" class="uclan-logo">
                <h1 class="site-title">HEALTH MATTERS</h1>
            </div>

            <div class="navbar-right">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." readonly>
                </div>
                <a href="PatientDash.php" class="my-account-link">
                    My Account
                    <i class="fas fa-user-circle"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="page-wrapper">
        <h1 class="page-title">Login</h1>
        <h2 class="page-subtitle">Access Your Account</h2>

        <div class="login-box">
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="Login.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST["username"] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="nav-buttons">
                    <a href="index.php" class="btn back-btn">Back</a>
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>