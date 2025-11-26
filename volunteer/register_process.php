<?php
session_start();
// Correct path to db_connect.php
include __DIR__ . '/../db_connect.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phone = trim($_POST['phone']);
    $age = intval($_POST['age']);
    $gender = trim($_POST['gender']);
    $username = trim($_POST['uname']);
    $password = $_POST['password'];

    // Check required fields
    if (empty($fname) || empty($lname) || empty($phone) || empty($username) || empty($password)) {
        die("Error: All fields are required.");
    }

    // Check username exists
    $stmt = $pdo->prepare("SELECT id FROM volunteers WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        die("Error: Username already taken.");
    }

    // Check phone exists
    $stmt = $pdo->prepare("SELECT id FROM volunteers WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($stmt->rowCount() > 0) {
        die("Error: Phone number already used.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new volunteer
    $stmt = $pdo->prepare("
        INSERT INTO volunteers (fname, lname, phone, age, gender, username, password)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $success = $stmt->execute([
        $fname,
        $lname,
        $phone,
        $age,
        $gender,
        $username,
        $hashedPassword
    ]);

    if ($success) {
        echo "<script>alert('Registration successful! You may now log in.');
        window.location.href='../login.php';
        </script>";
        exit;
    } else {
        die("Error: Registration failed.");
    }

} else {
    die("Invalid request.");
}
?>
