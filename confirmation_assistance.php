<?php
include "db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$request_id = $_GET['id'] ?? 0;

// Fetch request details
$stmt = $pdo->prepare("
    SELECT ar.*, v.fname AS volunteer_fname, v.lname AS volunteer_lname
    FROM assistance_requests ar
    LEFT JOIN volunteers v ON ar.assigned_volunteer_id = v.id
    WHERE ar.user_id = ? AND ar.id = ?
");
$stmt->execute([$user_id, $request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    echo "<p class='text-muted text-center mt-5'>Request not found.</p>";
    exit;
}

$statusColors = [
    'pending' => 'bg-warning',
    'in_progress' => 'bg-info',
    'for_elders_approval' => 'bg-secondary',
    'completed' => 'bg-success'
];
$badgeClass = $statusColors[$request['status']] ?? 'bg-dark';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistance Confirmation</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            background: #1f4f3c;
            color: #fff;
            transition: 0.3s;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
            z-index: 10;
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
            background: rgba(255, 255, 255, 0.15);
            padding-left: 25px;
        }

        #sidebar.collapsed .text {
            display: none;
        }

        /* Page content shifts right */
        .content {
            margin-left: 270px;
            padding: 30px;
        }

        #sidebar.collapsed ~ .content {
            margin-left: 100px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light ms-3" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="dashboard_elders.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
            <li class="nav-item"><a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Page Content -->
    <div class="content">

        <div class="share_location p-4 bg-white rounded shadow-sm">
            <h4 class="mb-3 fw-bold">Assistance Confirmation</h4>

            <p><strong>Type of Assistance:</strong> <?= htmlspecialchars($request['request_type']) ?></p>

            <p><strong>Description:</strong> <?= htmlspecialchars($request['description']) ?></p>

            <p><strong>Assigned Volunteer:</strong>
                <?php if ($request['assigned_volunteer_id']): ?>
                    <?= htmlspecialchars($request['volunteer_fname'] . " " . $request['volunteer_lname']) ?>
                <?php else: ?>
                    <span class="text-muted">Not assigned yet</span>
                <?php endif; ?>
            </p>

            <p><strong>Status:</strong>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($request['status']) ?></span>
            </p>

            <!-- Approve Button -->
            <?php if ($request['status'] == 'for_elders_approval'): ?>
                <form action="update_status_approve.php" id="approveForm" method="post">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    <button type="submit" class="btn btn-success btn-sm mt-2">
                        <i class="bi bi-check-circle"></i> Approve Assistance Completion
                    </button>
                </form>
            <?php endif; ?>
        </div>

    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar toggle
        document.getElementById("toggleSidebar").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("collapsed");
        });

        // SweetAlert Confirmation
        const approveForm = document.getElementById("approveForm");

        if (approveForm) {
            approveForm.addEventListener("submit", function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Approve this assistance?",
                    text: "This action will mark the request as completed.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#2a7f62",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, approve it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        approveForm.submit();
                    }
                });
            });
        }
    </script>

</body>
</html>
