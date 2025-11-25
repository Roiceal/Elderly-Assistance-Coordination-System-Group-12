<?php
session_start();

$admin_id = $_SESSION['admin_id'];
$admin_uname = $_SESSION['admin_username'];

if (!isset($admin_id) && !isset($admin_uname)) {
  header("Location: ../login.php");
  exit();
}

include __DIR__ . '/../db_connect.php';

// New Requests (Pending)
$stmt = $pdo->query("SELECT COUNT(*) as pending_count FROM assistance_requests WHERE status='Pending'");
$pendingRequests = $stmt->fetch(PDO::FETCH_ASSOC)['pending_count'] ?? 0;

// Total Users
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'] ?? 0;

// Male Users
$stmt = $pdo->query("SELECT COUNT(*) as male_count FROM users WHERE gender='Male'");
$maleUsers = $stmt->fetch(PDO::FETCH_ASSOC)['male_count'] ?? 0;

// Female Users
$stmt = $pdo->query("SELECT COUNT(*) as female_count FROM users WHERE gender='Female'");
$femaleUsers = $stmt->fetch(PDO::FETCH_ASSOC)['female_count'] ?? 0;

$stmt = $pdo->query("
    SELECT 
        MIN(age) AS min_age,
        MAX(age) AS max_age
    FROM users
    WHERE age IS NOT NULL
");
$ageData = $stmt->fetch(PDO::FETCH_ASSOC);

$minAge = $ageData['min_age'] ?? 0;
$maxAge = $ageData['max_age'] ?? 0;


// Fetch assistance requests with user info
$stmt = $pdo->query("
    SELECT ar.id, u.username AS user_name, ar.request_type, ar.description, ar.location, ar.status, ar.requested_at
    FROM assistance_requests ar
    LEFT JOIN users u ON ar.user_id = u.id
    ORDER BY ar.requested_at DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch age counts
$stmt = $pdo->query("SELECT age, COUNT(*) as count FROM users GROUP BY age ORDER BY count DESC LIMIT 5");
$topAges = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ageLabels = [];
$ageValues = [];

foreach ($topAges as $row) {
  $ageLabels[] = $row['age'];
  $ageValues[] = $row['count'];
}



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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {

      font-family: 'Poppins', sans-serif;
      background-color: whitesmoke;
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

    #sidebar.collapsed {
      width: 70px;
    }

    #sidebar .nav-link {
      color: #fff;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      border-radius: 8px;
      transition: all 0.2s;
    }

    #sidebar .nav-link:hover {
      background-color: #333;
      padding-left: 25px;
    }

    #sidebar.collapsed .text {
      display: none;
    }

    #content {
      margin-left: 250px;
      transition: margin-left 0.3s;
      padding: 20px;
    }

    #content.expanded {
      margin-left: 70px;
    }

    .card {
      border-radius: 12px;
      background-color: white;
      color: black;
      box-shadow: 0 8px 22px rgba(0, 0, 0, 0.35);
    }

    .table-dark th,
    .table-dark td {
      color: black;
    }

    .table-dark thead {
      background-color: white;
    }

    #topbar {
      background-color: white;
      color: black;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #333;
    }

    @media(max-width: 768px) {

      #sidebar,
      #content {
        margin-left: 0 !important;
        width: 100%;
      }
    }

    .stat-card {
      background: white;
      border-radius: 14px;
      padding: 20px;
      color: black;
      text-align: center;
      box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
      transition: 0.3s ease;
    }

    .stat-icon {
      font-size: 45px;
      margin-bottom: 12px;
      padding: 15px;
      border-radius: 50%;
      display: inline-block;
    }

    .stat-icon.male {
      background: rgba(77, 175, 255, 0.15);
      color: #4dafff;
    }

    .stat-icon.female {
      background: rgba(255, 107, 159, 0.15);
      color: #ff6b9f;
    }

    .stat-icon.age {
      background: rgba(157, 255, 107, 0.15);
      color: #9dff6b;
    }

    .stat-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 6px;
    }

    .stat-value {
      font-size: 22px;
      font-weight: bold;
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

  <!-- Content -->
  <div id="content">
    <div id="topbar">
      <h4>Admin Dashboard</h4>
      <div>
        <i class="bi bi-person-circle fs-4"></i> Admin
      </div>
    </div>

    <!-- Dashboard Cards -->
    <!-- Dashboard Cards -->
    <div class="row mt-2 g-3">

      <!-- New Requests -->
      <div class="col-md-6 col-sm-6">
        <div class="card shadow-sm p-3 text-center h-100 border-0">
          <i class="bi bi-bell fs-1 mb-2 text-warning"></i>
          <h5 class="fw-bold">New Requests</h5>
          <p class="fs-5 mb-0"><?= htmlspecialchars($pendingRequests) ?> Pending</p>
        </div>
      </div>

      <!-- Total Users -->
      <div class="col-md-6 col-sm-6">
        <div class="card shadow-sm p-3 text-center h-100 border-0">
          <i class="bi bi-people fs-1 mb-2 text-info"></i>
          <h5 class="fw-bold">Total Users</h5>
          <p class="fs-5 mb-0"><?= htmlspecialchars($totalUsers) ?> Users</p>
        </div>
      </div>

    </div>

    <div class="row mt-2 g-2">

      <!-- Male Users Card -->
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-icon male">
            <i class="bi bi-gender-male"></i>
          </div>
          <div class="stat-title">Male Users</div>
          <div class="stat-value"><?= htmlspecialchars($maleUsers) ?></div>
        </div>
      </div>

      <!-- Female Users Card -->
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-icon female">
            <i class="bi bi-gender-female"></i>
          </div>
          <div class="stat-title">Female Users</div>
          <div class="stat-value"><?= htmlspecialchars($femaleUsers) ?></div>
        </div>
      </div>

      <!-- Age Range Card -->
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-icon age">
            <i class="bi bi-graph-up"></i>
          </div>
          <div class="stat-title">Age Range</div>
          <div class="stat-value">
            <?= htmlspecialchars($minAge) ?> - <?= htmlspecialchars($maxAge) ?>
          </div>
        </div>
      </div>

    </div>

    <!-- Graph Row -->
    <div class="row mt-2 g-4">

      <div class="card p-3 shadow-sm col-md-6">
        <h5 class="text-center mb-3 fw-bold">Gender Distribution</h5>
        <div style="height: 260px;">
          <canvas id="genderChart"></canvas>
        </div>
      </div>

      <div class="card p-3 shadow-sm col-md-6">
        <h5 class="text-center mb-3 fw-bold">Most Common Age Count</h5>
        <div style="height: 260px;">
          <canvas id="ageChart"></canvas>
        </div>
      </div>

    </div>




    <!-- Assistance Requests Table -->
    <div class="card mt-4 p-3">
      <h5 class="mb-3">Assistance Requests</h5>
      <table id="requestsTable" class="table  table-striped table-bordered dt-responsive nowrap" style="width:100%">
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
                  <?php
                  $status = strtolower($req['status']);
                  if ($status === 'pending') {
                    $badgeClass = 'bg-warning text-dark';
                  } elseif ($status === 'in_progress') {
                    $badgeClass = 'bg-primary';
                  } elseif ($status === 'completed') {
                    $badgeClass = 'bg-success';
                  } else {
                    $badgeClass = 'bg-secondary';
                  }
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($status)) ?></span>
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
        order: [
          [0, 'desc']
        ],
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

    //for graphs


    document.addEventListener('DOMContentLoaded', () => {
      // Basic safety checks


      const genderCanvas = document.getElementById('genderChart');
      const ageCanvas = document.getElementById('ageChart');

      if (!genderCanvas || !ageCanvas) {
        console.error('One or both canvas elements missing: genderChart, ageChart');
        return;
      }

      // Helper: detect prefers-reduced-motion
      const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (prefersReducedMotion) {
        console.info('User prefers reduced motion — Chart animations may be disabled by the OS/browser.');
      }

      // Create gradients (recreate after each context to avoid stale gradient when resizing)
      const gCtx = genderCanvas.getContext('2d');
      const aCtx = ageCanvas.getContext('2d');

      const genderGradient = gCtx.createLinearGradient(0, 0, 0, genderCanvas.height || 300);
      genderGradient.addColorStop(0, 'rgba(54, 162, 235, 0.45)');
      genderGradient.addColorStop(1, 'rgba(54, 162, 235, 0)');

      const ageGradient = aCtx.createLinearGradient(0, 0, 0, ageCanvas.height || 300);
      ageGradient.addColorStop(0, 'rgba(75, 192, 192, 0.45)');
      ageGradient.addColorStop(1, 'rgba(75, 192, 192, 0)');

      // Build chart configs with explicit animation settings
      const genderConfig = {
        type: 'line',
        data: {
          labels: ['Male', 'Female'],
          datasets: [{
            data: [<?= (int)$maleUsers ?>, <?= (int)$femaleUsers ?>],
            fill: true,
            backgroundColor: genderGradient,
            borderColor: '#1d9bf0',
            borderWidth: 3,
            tension: 0.35,
            pointRadius: 6,
            pointHoverRadius: 10,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: {
            duration: prefersReducedMotion ? 0 : 1500,
            easing: 'easeInOutQuart',
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: 'rgba(0,0,0,0.78)',
              titleColor: '#fff',
              bodyColor: '#fff',
              padding: 10,
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          }
        }
      };

      const ageConfig = {
        type: 'line',
        data: {
          labels: <?= json_encode($ageLabels) ?>,
          datasets: [{
            data: <?= json_encode($ageValues) ?>,
            fill: true,
            backgroundColor: ageGradient,
            borderColor: '#0fb9b1',
            borderWidth: 3,
            tension: 0.35,
            pointRadius: 6,
            pointHoverRadius: 10,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: {
            duration: prefersReducedMotion ? 0 : 1800,
            easing: 'easeOutExpo',
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: 'rgba(0,0,0,0.78)',
              titleColor: '#fff',
              bodyColor: '#fff',
              padding: 10,
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          }
        }
      };

      // Create charts
      let genderChart, ageChart;
      try {
        genderChart = new Chart(gCtx, genderConfig);
        ageChart = new Chart(aCtx, ageConfig);
      } catch (err) {
        console.error('Chart creation error:', err);
        return;
      }

      // Force a reset + update to play the animation even when chart was rendered offscreen previously
      try {
        // reset() sets internal elements to initial state; update() triggers animation
        genderChart.reset();
        genderChart.update();

        ageChart.reset();
        ageChart.update();
      } catch (err) {
        // Some Chart.js builds may not expose reset() in older versions — try fallback
        console.warn('Reset/update fallback:', err);
        try {
          genderChart.update();
          ageChart.update();
        } catch (e) {
          console.error('Chart update error:', e);
        }
      }

      // Debug logs
      console.info('Charts created. Animation duration (gender):', genderConfig.options.animation.duration,
        ' age:', ageConfig.options.animation.duration);
    });
  </script>
</body>

</html>