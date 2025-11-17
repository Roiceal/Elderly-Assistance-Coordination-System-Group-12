<?php
session_start();
$config = require __DIR__ . '/config.php';

// Connect DB
try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

// User Login
// if (isset($_POST['login'])) {

    // Ensure your login form uses: <input name="username"> and <input name="password">
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');


    if (empty($phone) || empty($password)) {
        die("Both fields are required.");
    }

    // Secure prepared statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        // Save session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['phone'] = $user['phone'];

        echo "Login successful! Welcome, " . htmlspecialchars($user['username']);
        // redirect if needed:
        header("Location: dashboard_elders.php"); exit;

    } else {
        echo "Invalid username or password.";
    }
// }
?>
