<?php
session_start();

// Include DB connection
include 'db_connect.php';

// Read input
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    die("Both fields are required.");
}

// Query user
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Query admin
$stmt2 = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
$stmt2->execute([$username]);
$admin = $stmt2->fetch(PDO::FETCH_ASSOC);

// Query volunteer
$stmt3 = $pdo->prepare("SELECT * FROM volunteers WHERE username = ?");
$stmt3->execute([$username]);
$volunteers = $stmt3->fetch(PDO::FETCH_ASSOC);

if($admin && $admin['password'] === $password) {

    // Set session
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];

    // Redirect to dashboard
    header("Location:admin_page/admin.php");
    exit;

}else if ($user && password_verify($password, $user['password'])) {

    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['username'] = $user['username'];

    // Redirect to dashboard
    header("Location:dashboard_elders.php");
    exit;

}else if ($volunteers && password_verify($password, $volunteers['password'])) {

    // Set session
    $_SESSION['volunteer_id'] = $volunteers['id'];
    $_SESSION['volunteer_phone'] = $volunteers['phone'];
    $_SESSION['volunteer_username'] = $volunteers['username'];

    // Redirect to dashboard
    header("Location:volunteer/volunteer_dashboard.php");
    exit;

} else {
    echo "Invalid phone number or password.";
}


?>
