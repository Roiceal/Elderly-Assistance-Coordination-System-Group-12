<?php
session_start();
include 'db_connect.php';// include reusable DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $request_type = trim($_POST['request_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');

    if (!$user_id || !$request_type || !$description || !$location) {
        die("All fields are required.");
    }

    $stmt = $pdo->prepare("
        INSERT INTO assistance_requests (user_id, request_type, description, location)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $request_type, $description, $location]);

    header("Location: request_assistance.php?success=1");
    exit;
}
?>
