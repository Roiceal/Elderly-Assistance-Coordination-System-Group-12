<?php
// api_assign_task.php
require_once __DIR__ . '/../db_connect.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$task_id = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;

if (!$task_id) { echo json_encode(['success'=>false,'message'=>'task_id required']); exit; }

try {
  if ($action === 'complete') {
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'Completed' WHERE id = ?");
    $ok = $stmt->execute([$task_id]);
    echo json_encode(['success'=>$ok]);
    exit;
  }
  // add more actions: assign, start, cancel
  echo json_encode(['success'=>false,'message'=>'Unknown action']);
} catch (Exception $e) {
  echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
