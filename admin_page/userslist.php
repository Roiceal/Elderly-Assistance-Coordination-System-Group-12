<?php
session_start();
include __DIR__ . '/../db_connect.php';
// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM volunteers ORDER BY id DESC");
$vol = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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

    th:nth-child(1),
    td:nth-child(1) {
      width: 100px !important;
      /* fits 80px image perfectly */
      min-width: 100px !important;
      max-width: 100px !important;
      text-align: center;
      vertical-align: middle;
    }

    .nav-link.active {
      color: black;
    }

    .nav-link {
      color: black;
    }

    .nav-link:hover {
      color: darkgrey;
    }



    .profile-img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #c5e1dc;
    }

    .container {
      padding: 0px;
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




    <!-- Modal -->
    <div class="modal fade" id="addElderModal" tabindex="-1" aria-labelledby="addElderModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="insert_request.php" enctype="multipart/form-data" id="elderForm">
            <div class="modal-header">
              <h5 class="modal-title" id="addElderModalLabel">Add Elder</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- First Name -->
              <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
              </div>
              <!-- Last Name -->
              <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
              </div>
              <!-- Address -->
              <div class="mb-3">
                <label for="addr" class="form-label">Address</label>
                <input type="text" class="form-control" id="addr" name="address" required>
              </div>
              <!-- Phone -->
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
              </div>
              <!-- Age -->
              <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" required>
              </div>
              <!-- Gender -->
              <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="" disabled selected>Select gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
              <!-- Username -->
              <div class="mb-3">
                <label for="uname" class="form-label">Username</label>
                <input type="text" class="form-control" id="uname" name="uname" required>
              </div>
              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <!-- Profile Image -->
              <div class="mb-3">
                <label for="image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Add Elder</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>








    <div class="container">
      <h5 class="mb-3">Registered Users</h5>
      <!-- TABS -->
      <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
            data-bs-target="#activity" type="button" role="tab">
            Elder Users
          </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="health-tab" data-bs-toggle="tab"
            data-bs-target="#volunteers" type="button" role="tab">
            Volunteer Users
          </button>
        </li>
      </ul>
      <div class="tab-content">
        <!-- ACTIVITY TAB -->
        <div class="tab-pane fade show active" id="activity" role="tabpanel">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"></h5>
            <a href="add_elder.php" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addElderModal">
              <i class="bi bi-plus-circle"></i> Add Elder
            </a>
          </div>

          <table id="usersTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Profile Image</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Created At</th>
              </tr>
            </thead>

            <tbody>
              <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($user['profile_image']) ?>" class="profile-img"></td>
                    <td><?= htmlspecialchars($user['first_name']) ?></td>
                    <td><?= htmlspecialchars($user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($user['created_at']))) ?></td>
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

        <!-- VOLUNTEER TAB -->
        <div class="tab-pane fade" id="volunteers" role="tabpanel">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"></h5>
            <a href="add_elder.php" class="btn btn-success">
              <i class="bi bi-plus-circle"></i> Add Volunteer
            </a>
          </div>

          <table id="volunteerTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>
                <th>Profile Image</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Created At</th>
              </tr>
            </thead>

            <tbody>
              <?php if (!empty($vol)): ?>
                <?php foreach ($vol as $v): ?>
                  <tr>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($v['profile_image']) ?>" class="profile-img"></td>
                    <td><?= htmlspecialchars($v['fname']) ?></td>
                    <td><?= htmlspecialchars($v['lname']) ?></td>
                    <td><?= htmlspecialchars($v['username']) ?></td>
                    <td><?= htmlspecialchars($v['phone']) ?></td>
                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($v['created_at']))) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">No volunteers found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

        </div>
      </div>
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

      $('#volunteerTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
          searchPlaceholder: "Search volunteers...",
          search: ""
        }
      });
    });


    document.getElementById('elderForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('insert_user.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        // Close modal
        const elderModal = bootstrap.Modal.getInstance(document.getElementById('addElderModal'));
        elderModal.hide();

        // SweetAlert success
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: data.message,
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          window.location.reload();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message
        });
      }
    })
    .catch(err => {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Something went wrong!'
      });
    });
});

  </script>

</body>

</html>