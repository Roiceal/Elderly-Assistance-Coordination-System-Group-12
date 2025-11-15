<?php
session_start();
require_once '../../database/db_connection.php';
// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Fetch all assistance requests
$result = $conn->query("SELECT * FROM assistance_request");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Assistance Requests</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- YOUR CUSTOM CSS FILE -->
    <link rel="stylesheet" href="../adminpannel.css"> 
    <!-- Make sure file name and spelling matches exactly -->

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-light fixed-top">
    <div>
        <button class="toggle-btn" id="toggleSidebar">☰</button>
        <span class="navbar-brand ms-2">Admin Panel</span>
    </div>
</nav>

<!-- SIDEBAR -->
<nav class="sidebar hidden" id="sidebar">
    <ul class="sidebar-menu">
        <li class="menu-item"><a href="user_profile.php" class="menu-link">Dashboard</a></li>
        <li class="menu-item"><a href="../homepage_admin.php" class="menu-link">Home</a></li>
        <li class="menu-item"><a href="read_request.php" class="menu-link">Assistance Request</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Programs</a></li>
        <li class="menu-item"><a href="../rfid/index.php" class="menu-link">Attendance</a></li>
        <li class="menu-item"><a href="" class="menu-link">Activity Log</a></li>
        <li class="menu-item"><a href="../admin_settings.php" class="menu-link">Settings</a></li>
    </ul>
</nav>

<!-- CONTENT -->
<div id="content" class="content full-width">
<!-- 
    <a href="add_request.php" class="btn btn-success mb-3">Add New Request</a> -->

    <table id="myTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Type</th>
                <th>Details</th>
                <th>Date Requested</th>
                <th>Actions</th>
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
                    <a href="update_request.php?id=<?= $row['request_id']; ?>" class="btn btn-sm btn-primary">Update</a>
                    <a href="delete_request.php?id=<?= $row['request_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                    <a href="assign_request.php?id=<?= $row['request_id']; ?>" class="btn btn-sm btn-warning">Assign to Volunteers</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#myTable').DataTable();
});
</script>

<!-- SIDEBAR TOGGLE -->
<script>
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');

// Sidebar starts hidden, content full width
toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
    content.classList.toggle('full-width');
});
</script>

</body>
</html>
