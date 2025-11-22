<?php
session_start();
$config = require __DIR__ . '/config.php';

// Connect to database
try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

$uname = $_POST['uname'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->execute([$uname]);
$admin = $stmt->fetch();

// if ($admin && password_verify($password, $admin['password'])) {
if ($admin && $password === $admin['password']) {

    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['fullname'];

    header("Location: admin.php");
    exit();

} else {
    echo "<script>alert('Invalid admin credentials'); window.location='admin_login.php';</script>";
    exit();
}
?>
