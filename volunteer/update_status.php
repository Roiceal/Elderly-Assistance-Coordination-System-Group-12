<?php
session_start();
include __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    
    $requestId = $_POST['request_id'];

    $stmt = $pdo->prepare("UPDATE assistance_requests SET status = 'completed' WHERE id = ?");
    $stmt->execute([$requestId]);

    header("Location: volunteer_dashboard.php");
    exit();
}
?>
