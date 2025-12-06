<?php
session_start();

include "db_connect.php"; // adjust path if needed

$user_id = $_SESSION['user_id'];

// Redirect if not logged in
if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch all assistance requests
$stmt2 = $pdo->prepare("
    SELECT ar.*, v.fname AS volunteer_fname, v.lname AS volunteer_lname
    FROM assistance_requests ar
    LEFT JOIN volunteers v ON ar.assigned_volunteer_id = v.id
    WHERE ar.user_id = ?
    ORDER BY ar.requested_at DESC
");
$stmt2->execute([$user_id]);
$requests = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Assistance Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        #sidebar {
            width: 250px;
            background: #1f4f3c;
            color: #fff;
            transition: 0.3s;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
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

        #content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .request-row:hover {
            cursor: pointer;
            background-color: rgba(42, 127, 98, 0.1);
        }

        @media (max-width: 768px) {

            #sidebar,
            #content {
                margin-left: 0 !important;
                width: 100%;
            }
        }

        .btn {
            background-color: #2a7f62;
            border: none;
        }

        .btn:hover {
            background-color: #1f4f3c;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="d-none d-md-block">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-house"></i><span class="text">Home</span></a></li>
            <li class="nav-item"><a href="user_profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="all_events.php" class="nav-link"><i class="bi bi-calendar-event"></i><span class="text">Events</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="content">

        <!-- Dashboard Header -->
        <div class="d-flex align-items-center mb-3 p-4 bg-white rounded shadow-sm dashboard-header"
            style="border-left: 6px solid #1f4f3c;">
            <div class="me-4">
                <img src="<?= $user['profile_image'] ? 'data:image/jpeg;base64,' . base64_encode($user['profile_image']) : 'assets/default-avatar.png' ?>"
                    class="rounded-circle shadow-sm"
                    style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #1f4f3c;">
            </div>
            <div>
                <h1 class="m-0 fw-bold" style="text-transform: uppercase; font-size: 2.5rem; color:#1f4f3c;">
                    Welcome, <?= htmlspecialchars($user['first_name']) ?>!
                </h1>
                <p class="m-0 mt-2" style="color:#3c6e57; font-size: 1.25rem;">
                    Your safety, health, and comfort are our top priority. How can we assist you today?
                </p>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <i class="bi bi-bell fs-2 text-success"></i>
                    <h5 class="mt-2">Request Assistance</h5>
                    <p>Get help whenever you need it</p>
                    <a href="request_assistance.php" class="btn btn-primary btn-sm">Request</a>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="card p-3">
                    <i class="bi bi-heart fs-2 text-success"></i>
                    <h5 class="mt-2">Health & Wellness</h5>
                    <p>Access exercise routines, diet tips, and reminders.</p>
                    <a href="#" class="btn btn-danger btn-sm">Explore</a>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="card p-3">
                    <i class="bi bi-calendar-event fs-2 text-success"></i>
                    <h5 class="mt-2">Events</h5>
                    <p>See upcoming activities for seniors.</p>
                    <a href="all_events.php" class="btn btn-success btn-sm">Check</a>
                </div>
            </div>
        </div>

        <!-- All Requests Table -->
        <div class="mt-5 p-4 bg-white rounded shadow-sm">
            <h4 class="mb-3 fw-bold">All Assistance Requests</h4>

            <?php if (count($requests) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Request Type</th>
                                <th>Description</th>
                                <th>Assigned Volunteer</th>
                                <th>Status</th>
                                <th>Requested At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $index => $req):
                                $statusColors = [
                                    'pending' => 'bg-warning',
                                    'in_progress' => 'bg-info',
                                    'for_elders_approval' => 'bg-secondary',
                                    'completed' => 'bg-success'
                                ];
                                $badgeClass = $statusColors[$req['status']] ?? 'bg-dark';
                            ?>
                                <tr class="request-row" data-id="<?= $req['id'] ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($req['request_type']) ?></td>
                                    <td><?= htmlspecialchars($req['description']) ?></td>
                                    <td>
                                        <?= !empty($req['assigned_volunteer_id'])
                                            ? htmlspecialchars($req['volunteer_fname'] . ' ' . $req['volunteer_lname'])
                                            : '<span class="text-muted">Not assigned yet</span>' ?>
                                    </td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($req['status']) ?></span></td>
                                    <td><?= date('M d, Y H:i', strtotime($req['requested_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">You have no assistance requests yet.</p>
            <?php endif; ?>
        </div>

        <!-- Assistance Confirmation Section -->
        <div id="request-details"></div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Sidebar toggle
        document.getElementById("toggleSidebar").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("content").classList.toggle("expanded");
        });

        // Click row to load details
        // Click row → go to request details page
        document.querySelectorAll('.request-row').forEach(row => {
            row.addEventListener('click', () => {
                const requestId = row.dataset.id;

                // Redirect to your confirmation page
                window.location.href = "confirmation_assistance.php?id=" + requestId;
            });
        });
    </script>

</body>

</html>