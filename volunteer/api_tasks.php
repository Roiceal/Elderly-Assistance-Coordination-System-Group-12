<?php
// api_tasks.php
require_once __DIR__ . '/../db_connect.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

if ($action === 'list_volunteers') {
    $stmt = $pdo->query("SELECT id, fname, lname FROM volunteers ORDER BY fname");
    echo json_encode($stmt->fetchAll());
    exit;
}
if ($action === 'list_elderly') {
    $stmt = $pdo->query("SELECT id, fname, lname FROM elderly ORDER BY fname");
    echo json_encode($stmt->fetchAll());
    exit;
}
if ($action === 'get' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    echo json_encode($task ?: null);
    exit;
}

// default: return all tasks for DataTable
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($action)) {
    // return tasks array
    $stmt = $pdo->query("
      SELECT t.*,
             CONCAT(v.fname,' ',v.lname) AS assigned_name,
             CONCAT(e.fname,' ',e.lname) AS elder_name
      FROM tasks t
      LEFT JOIN volunteers v ON t.assigned_volunteer_id = v.id
      LEFT JOIN elderly e ON t.elderly_id = e.id
      ORDER BY t.id DESC
    ");
    $rows = $stmt->fetchAll();
    echo json_encode($rows);
    exit;
}

// Create/update/delete actions via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = $_POST;
    $actionPost = $_POST['action'] ?? 'save';

    if ($actionPost === 'delete' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $ok = $stmt->execute([$id]);
        echo json_encode(['success' => $ok]);
        exit;
    }

    // Save (create/update)
    $id = isset($post['id']) && $post['id'] !== '' ? (int)$post['id'] : null;
    $title = $post['title'] ?? '';
    $description = $post['description'] ?? '';
    $priority = $post['priority'] ?? 'Medium';
    $assigned_volunteer_id = $post['assigned_volunteer_id'] ?: null;
    $elderly_id = $post['elderly_id'] ?: null;
    $due_date = $post['due_date'] ?: null;

    if (!$title) {
        echo json_encode(['success' => false, 'message' => 'Title required']); exit;
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE tasks SET title=?, description=?, priority=?, assigned_volunteer_id=?, elderly_id=?, due_date=? WHERE id=?");
            $ok = $stmt->execute([$title, $description, $priority, $assigned_volunteer_id, $elderly_id, $due_date, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority, assigned_volunteer_id, elderly_id, due_date) VALUES (?, ?, ?, ?, ?, ?)");
            $ok = $stmt->execute([$title, $description, $priority, $assigned_volunteer_id, $elderly_id, $due_date]);
        }
        echo json_encode(['success' => (bool)$ok]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid request']);
