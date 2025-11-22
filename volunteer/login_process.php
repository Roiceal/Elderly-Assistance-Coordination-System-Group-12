<?php
session_start();

// Database connection
$host = 'localhost';
$db   = 'otp';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['uname']);
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $pdo->prepare("SELECT * FROM volunteers WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['volunteer_id'] = $user['id'];
            $_SESSION['volunteer_name'] = $user['fname'] . ' ' . $user['lname'];

            // Redirect to dashboard
            header("Location: volunteer_dashboard.php");
            exit;
        } else {
            header("Location: volunteer_login.php?error=Invalid+password");
            exit;
        }
    } else {
        header("Location: volunteer_login.php?error=User+not+found");
        exit;
    }
}
?>
