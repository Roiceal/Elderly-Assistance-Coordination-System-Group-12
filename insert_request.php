<?php
session_start();
$config = require __DIR__ . '/config.php';

try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $request_type = trim($_POST['request_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');

    if (!$user_id || !$request_type || !$description || !$location) {
        die("All fields are required.");
    }

    $stmt = $pdo->prepare("INSERT INTO assistance_requests (user_id, request_type, description, location) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $request_type, $description, $location]);

    // Redirect back with success message
    header("Location: request_assistance.php?success=1");
    exit;
}
?>
