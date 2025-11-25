<?php
session_start();
include __DIR__ . '/../db_connect.php';
// Fetch all users
$stmt = $pdo->query("SELECT id, first_name, last_name, username, phone, senior_id, created_at FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Users</title>

  <!-- Bootstrap -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- DataTables -->

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: black;
    }

    #sidebar {
      width: 250px;
      background: #1c1c1c;
      height: 100vh;
      position: fixed;
      padding-top: 20px;
      transition: width 0.3s ease;
    }

    #sidebar.collapsed {
      width: 70px;
    }

    #sidebar .nav-link {
      color: white;
      padding: 12px 20px;
      display: flex;
      gap: 15px;
      border-radius: 8px;
    }

    #sidebar .nav-link:hover {
      background: #333;
    }

    #sidebar.collapsed .text {
      display: none;
    }

    #content {
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }

    #content.expanded {
      margin-left: 70px;
    }

    #topbar {
      background: white;
      color: black;
      padding: 12px 20px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: solid 3px #dee2e6;
    }

    .card {
      background: white;
      border: none;
      color: black;
      box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }

    table thead {
      background-color: #0d6efd;
      color: black;
    }

    table.dataTable tbody tr {
      color: black;
    }

    .btn-action {
      margin-right: 5px;
    }
  </style>

</head>

<body>

  <!-- SIDEBAR -->

  <div id="sidebar">
    <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
      <i class="bi bi-list"></i>
    </button>
    <ul class="nav flex-column">
      <li class="nav-item"><a href="admin.php" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
      <li class="nav-item"><a href="userslist.php" class="nav-link"><i class="bi bi-people-fill"></i><span class="text">Users</span></a></li>
      <li class="nav-item"><a href="map/location_map.php" class="nav-link"><i class="bi bi-geo-alt-fill"></i><span class="text">Locate Elder</span></a></li>
      <li class="nav-item"><a href="assign_volunteer.php" class="nav-link"><i class="bi bi-list-check"></i><span class="text">Volunteer Assignment</span></a></li>
      <li class="nav-item"><a href="make_announcement.php" class="nav-link"><i class="bi bi-megaphone-fill"></i><span class="text">Announcement</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear-fill"></i><span class="text">Settings</span></a></li>
      <li class="nav-item"><a href="../rfid" class="nav-link"><i class="bi bi-person-badge-fill"></i><span class="text">Attendance</span></a></li>
      <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
    </ul>
  </div>

  <!-- CONTENT -->

  <div id="content">
    <div id="topbar">
      <h4>Users Management</h4>
      <div><i class="bi bi-person-circle fs-4"></i> Admin</div>
    </div>
    <div class="card p-3">
      <h5 class="mb-3">Registered Users</h5>

      <table id="usersTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Username</th>
            <th>Phone</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['first_name']) ?></td>
                <td><?= htmlspecialchars($user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['phone']) ?></td>
                <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($user['created_at']))) ?></td>
                <td>
                  <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary btn-action">Edit</a>
                  <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No users found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

  <!-- JS -->

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

  <script>
    document.getElementById("toggleSidebar").addEventListener("click", () => {
      document.getElementById("sidebar").classList.toggle("collapsed");
      document.getElementById("content").classList.toggle("expanded");
    });

    // Initialize DataTable
    $(document).ready(function() {
      $('#usersTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [
          [0, "desc"]
        ],
        language: {
          searchPlaceholder: "Search users...",
          search: ""
        }
      });
    });
  </script>

</body>

</html>