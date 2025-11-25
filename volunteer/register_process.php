<?php
session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phone = trim($_POST['phone']);
    $age = intval($_POST['age']);
    $gender = trim($_POST['gender']);
    $username = trim($_POST['uname']);
    $password = $_POST['password'];

    // Handle image safely (optional)
    $imageData = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Check required fields
    if (empty($fname) || empty($lname) || empty($phone) || empty($username) || empty($password)) {
        die("Error: All fields are required.");
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT id FROM volunteers WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        die("Error: Username already taken.");
    }

    // Check if phone already exists
    $stmt = $pdo->prepare("SELECT id FROM volunteers WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($stmt->rowCount() > 0) {
        die("Error: Phone number already used.");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new volunteer including optional image
    $stmt = $pdo->prepare("
        INSERT INTO volunteers 
        (fname, lname, phone, age, gender, username, password, profile_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bindParam(1, $fname);
    $stmt->bindParam(2, $lname);
    $stmt->bindParam(3, $phone);
    $stmt->bindParam(4, $age, PDO::PARAM_INT);
    $stmt->bindParam(5, $gender);
    $stmt->bindParam(6, $username);
    $stmt->bindParam(7, $hashedPassword);
    $stmt->bindParam(8, $imageData, PDO::PARAM_LOB);

    $success = $stmt->execute();

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
