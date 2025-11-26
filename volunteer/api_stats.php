<?php
// api_stats.php
require_once __DIR__ . '/../db_connect.php';
header('Content-Type: application/json');

$volunteer_id = isset($_POST['volunteer_id']) ? (int)$_POST['volunteer_id'] : 0;
if (!$volunteer_id) {
    echo json_encode(['error' => 'volunteer_id required']); exit;
}

try {
    // assigned tasks
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_volunteer_id = ?");
    $stmt->execute([$volunteer_id]);
    $assigned = (int)$stmt->fetchColumn();

    // completed tasks by this volunteer
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_volunteer_id = ? AND status = 'Completed'");
    $stmt->execute([$volunteer_id]);
    $completed = (int)$stmt->fetchColumn();

    // elders assisted: count distinct elderly_id in completed tasks by this volunteer
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT elderly_id) FROM tasks WHERE assigned_volunteer_id = ? AND status = 'Completed' AND elderly_id IS NOT NULL");
    $stmt->execute([$volunteer_id]);
    $elderly_assisted = (int)$stmt->fetchColumn();

    echo json_encode([
      'assigned' => $assigned,
      'completed' => $completed,
      'elderly_assisted' => $elderly_assisted
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
