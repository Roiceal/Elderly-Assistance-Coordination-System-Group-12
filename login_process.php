<?php
session_start();

// Include DB connection
include 'db_connect.php';

// Read input
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($phone) || empty($password)) {
    die("Both fields are required.");
}

// Query user
$stmt = $pdo->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->execute([$phone]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify password
if ($user && password_verify($password, $user['password'])) {

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['username'] = $user['username'];

    // Redirect to dashboard
    header("Location:dashboard_elders.php");
    exit;

} else {
    echo "Invalid phone number or password.";
}
?>
