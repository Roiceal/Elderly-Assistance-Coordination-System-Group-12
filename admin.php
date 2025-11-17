<?php
session_start();
$config = require __DIR__ . '/config.php';

// Connect to database
try {
    $pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// New Requests (Pending)
$stmt = $pdo->query("SELECT COUNT(*) as pending_count FROM assistance_requests WHERE status='Pending'");
$pendingRequests = $stmt->fetch(PDO::FETCH_ASSOC)['pending_count'] ?? 0;

// Total Users
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'] ?? 0;

// Fetch assistance requests with user info
$stmt = $pdo->query("
    SELECT ar.id, u.username AS user_name, ar.request_type, ar.description, ar.location, ar.status, ar.requested_at
    FROM assistance_requests ar
    LEFT JOIN users u ON ar.user_id = u.id
    ORDER BY ar.requested_at DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: #fff;
    }
    #sidebar {
      width: 250px;
      background-color: #1c1c1c;
      position: fixed;
      height: 100vh;
      padding-top: 20px;
      transition: width 0.3s;
    }
    #sidebar.collapsed { width: 70px; }
    #sidebar .nav-link { color: #fff; padding: 12px 20px; display: flex; align-items: center; gap: 15px; border-radius: 8px; transition: all 0.2s; }
    #sidebar .nav-link:hover { background-color: #333; padding-left: 25px; }
    #sidebar.collapsed .text { display: none; }
    #content { margin-left: 250px; transition: margin-left 0.3s; padding: 20px; }
    #content.expanded { margin-left: 70px; }
    .card { border-radius: 12px; background-color: #1e1e1e; color: #fff; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); }
    .table-dark th, .table-dark td { color: #fff; }
    .table-dark thead { background-color: #2c2c2c; }
    #topbar { background-color: #1c1c1c; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
    @media(max-width: 768px) {
      #sidebar, #content { margin-left: 0 !important; width: 100%; }
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <div id="sidebar">
    <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
      <i class="bi bi-list"></i>
    </button>
    <ul class="nav flex-column mt-3">
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-people"></i><span class="text">Users</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-bell"></i><span class="text">Requests</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-graph-up"></i><span class="text">Annoucement</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
      <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
    </ul>
  </div>

  <!-- Content -->
  <div id="content">
    <div id="topbar">
      <h4>Admin Dashboard</h4>
      <div>
        <i class="bi bi-person-circle fs-4"></i> Admin
      </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row mt-2 g-2">
      <div class="col-md-5">
        <div class="card p-3 text-center">
          <i class="bi bi-bell fs-2 mb-2 text-warning"></i>
          <h5>New Requests</h5>
          <p><?= htmlspecialchars($pendingRequests) ?> Pending</p>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card p-3 text-center">
          <i class="bi bi-people fs-2 mb-2 text-info"></i>
          <h5>Total Users</h5>
          <p><?= htmlspecialchars($totalUsers) ?> Users</p>
        </div>
      </div>
    </div>

    <!-- Assistance Requests Table -->
    <div class="card mt-4 p-3">
      <h5 class="mb-3">Assistance Requests</h5>
      <table id="requestsTable" class="table table-dark table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
            <th>Requested at</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($requests): ?>
            <?php foreach ($requests as $req): ?>
              <tr>
                <td><?= htmlspecialchars($req['id']) ?></td>
                <td><?= htmlspecialchars($req['user_name']) ?></td>
                <td><?= htmlspecialchars($req['request_type']) ?></td>
                <td><?= htmlspecialchars($req['description']) ?></td>
                <td><?= htmlspecialchars($req['location']) ?></td>
                <td>
                  <?php if ($req['status'] === 'Completed'): ?>
                    <span class="badge bg-success"><?= htmlspecialchars($req['status']) ?></span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark"><?= htmlspecialchars($req['status']) ?></span>
                  <?php endif; ?>
                </td>
                <td><?= isset($req['requested_at']) ? htmlspecialchars(date('Y-m-d H:i', strtotime($req['requested_at']))) : '-' ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No requests found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

  <script>
    // Sidebar toggle
    document.getElementById("toggleSidebar").addEventListener("click", () => {
      const sidebar = document.getElementById("sidebar");
      const content = document.getElementById("content");
      sidebar.classList.toggle("collapsed");
      content.classList.toggle("expanded");
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#requestsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'desc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search requests...",
                paginate: {
                    previous: "<",
                    next: ">"
                }
            }
        });
    });
  </script>
</body>
</html>
