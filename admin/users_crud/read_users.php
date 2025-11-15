<?php
session_start();
include_once '../../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:  ../../login/login.php");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT user_id, username, email, phone_number, address, gender, birthday, role, rfid_pin 
        FROM users 
        WHERE role != 'admin'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../adminpannel.css"> 
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        <li class="menu-item"><a href="read_users.php" class="menu-link">Users</a></li>
        <li class="menu-item"><a href="../assistance_crud/read_request.php" class="menu-link">Assistance Request</a></li>
        <li class="menu-item"><a href="#" class="menu-link">Programs</a></li>
        <li class="menu-item"><a href="../rfid/index.php" class="menu-link">Attendance</a></li>
        <li class="menu-item"><a href="" class="menu-link">Activity Log</a></li>
        <li class="menu-item"><a href="../admin_settings.php" class="menu-link">Settings</a></li>
    </ul>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Users List</h2>
        <a href="add_user.php" class="btn btn-success">Add User</a>
    </div>

    <table id="myTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Birthday</th>
                <th>Role</th>
                <th>RFID PIN</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td><?php echo htmlspecialchars($row['rfid_pin']); ?></td>
                        <td>
                            <a href="update_users.php?id=<?php echo $row['user_id']; ?>" class="btn btn-primary btn-sm">Update</a>
                            <a href="delete_users.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable({
        responsive: true
    });

    $('#toggleSidebar').click(function() {
        $('#sidebar').toggleClass('hidden');
    });
});
</script>

</body>
</html>
