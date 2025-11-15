<?php
session_start();
include_once '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$user_id = $_SESSION['user_id'];

// Fetch pending assigned requests
$result = $conn->query("
    SELECT va.id AS assign_id, ar.*
    FROM volunteer_assignments va
    JOIN assistance_request ar ON va.request_id = ar.request_id
    WHERE va.volunteer_user_id = $user_id AND va.status = 'pending'
");

// Handle accept/decline
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $assign_id = intval($_GET['id']);

    if ($action === 'accepted' || $action === 'declined') {
        $stmt = $conn->prepare("UPDATE volunteer_assignments SET status=? WHERE id=?");
        $stmt->bind_param("si", $action, $assign_id);
        $stmt->execute();
        $stmt->close();
        header("Location: volunteer_requests.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Assigned Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="volunteer.css">
    
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light fixed-top">
    <button class="toggle-btn" id="toggleSidebar">
        ☰
      </button>
</nav>
    <div class="container">
           <nav class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li class="menu-item"><a href="volunteer_homepage.php" class="menu-link">Home</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Notification</a></li>
        <li class="menu-item"><a href="volunteer_requests.php" class="menu-link">View Available Request</a></li>
        <li class="menu-item"><a href="#" class="menu-link">View Elder Location</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Task History</a></li>
        <li class="menu-item"><a href="volunteer_settings.php" class="menu-link">Settings</a></li>
        
    </ul>
           </nav>

<h2>My Assigned Assistance Requests</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Type</th>
            <th>Details</th>
            <th>Date Requested</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['request_id']; ?></td>
            <td><?= htmlspecialchars($row['fullname']); ?></td>
            <td><?= htmlspecialchars($row['address']); ?></td>
            <td><?= htmlspecialchars($row['contact']); ?></td>
            <td><?= htmlspecialchars($row['type']); ?></td>
            <td><?= htmlspecialchars($row['details']); ?></td>
            <td><?= htmlspecialchars($row['date_requested']); ?></td>
            <td>
                <a href="?action=accepted&id=<?= $row['assign_id']; ?>" class="btn btn-success btn-sm">Accept</a>
                <a href="?action=declined&id=<?= $row['assign_id']; ?>" class="btn btn-danger btn-sm">Decline</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
 <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('hidden');
      content.classList.toggle('full-width');
    });
  </script>
</html>
</html>
