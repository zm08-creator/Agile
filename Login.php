<?php
// Enable error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// PostgreSQL connection settings
$host   = "localhost";
$port   = "5432";
$dbname = "agile_db";
$dbuser = "postgres";
$dbpass = "Admin123"; // <-- put your real postgres password here

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Please enter username and password.";
    } else {
        // Fetch user from PostgreSQL users table
        $stmt = $pdo->prepare("
            SELECT user_id, username, password_hash, role
            FROM users
            WHERE username = :u
        ");
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the hashed password
            if (password_verify($password, $user['password_hash'])) {

                // Store session data
                $_SESSION["user_id"]  = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"]     = $user["role"];

                // Redirect based on role value in the database
                $role = strtolower($user["role"]);

                if ($role === "service_user") {
                    header("Location: PatientDash.php");
                } elseif ($role === "practitioner") {
                    header("Location: ProfDash.php");
                } elseif ($role === "admin") {
                    header("Location: AdminDash.php");
                } else {
                    // Any other role goes back to home or a generic page
                    header("Location: index.php");
                }

                exit;

            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Health Matters</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
    </div>

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
