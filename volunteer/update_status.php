<?php
session_start();
include __DIR__ . '/../db_connect.php';
header('Content-Type: application/json');

if (!isset($_POST['request_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing request ID']);
    exit;
}

$request_id = $_POST['request_id'];

$stmt = $pdo->prepare("UPDATE assistance_requests SET status = 'for_elders_approval' WHERE id = ?");
$success = $stmt->execute([$request_id]);

echo json_encode(['success' => $success]);
?>

