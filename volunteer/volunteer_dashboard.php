<?php
session_start();

$vol_id = $_SESSION['volunteer_id'];
$vol_phone = $_SESSION['volunteer_phone'];
$vol_uname = $_SESSION['volunteer_username'];

// Redirect if not logged in
if (!isset($vol_id) && !isset($vol_phone) && !isset($vol_uname)) {
    header("Location: ../login.php");
    exit();
}

include __DIR__ . '/../db_connect.php'; // adjust path if needed

$volunteer_id = $_SESSION['volunteer_id'];

// Get volunteer info
$stmt = $pdo->prepare("SELECT * FROM volunteers WHERE id = ?");
$stmt->execute([$volunteer_id]);
$volunteer = $stmt->fetch(PDO::FETCH_ASSOC);

// Stats: total assigned requests
$stmt = $pdo->prepare("SELECT COUNT(*) as total_assigned FROM assistance_requests WHERE assigned_volunteer_id = ?");
$stmt->execute([$volunteer_id]);
$totalAssigned = $stmt->fetch(PDO::FETCH_ASSOC)['total_assigned'] ?? 0;

// Stats: in progress requests assigned
$stmt = $pdo->prepare("SELECT COUNT(*) as inprogress_assigned FROM assistance_requests WHERE assigned_volunteer_id = ? AND status='in_progress'");
$stmt->execute([$volunteer_id]);
$inprogressAssigned = $stmt->fetch(PDO::FETCH_ASSOC)['inprogress_assigned'] ?? 0;

// Stats: pending requests assigned
$stmt = $pdo->prepare("SELECT COUNT(*) as completed FROM assistance_requests WHERE assigned_volunteer_id = ? AND status='completed'");
$stmt->execute([$volunteer_id]);
$completed = $stmt->fetch(PDO::FETCH_ASSOC)['completed'] ?? 0;


// Volunteer age stats
$stmt = $pdo->query("SELECT MIN(age) as min_age, MAX(age) as max_age, COUNT(*) as total_volunteers FROM volunteers");
$ageStats = $stmt->fetch(PDO::FETCH_ASSOC);

// Volunteer gender stats
$stmt = $pdo->query("SELECT gender, COUNT(*) as count FROM volunteers GROUP BY gender");
$genderStatsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$genderStats = ['Male' => 0, 'Female' => 0, 'Other' => 0];
foreach ($genderStatsRaw as $g) {
    $genderStats[$g['gender']] = $g['count'];
}

// Fetch assigned assistance requests
$stmt = $pdo->prepare("
    SELECT ar.id, u.first_name, u.last_name, ar.request_type, ar.description, ar.location, ar.status, ar.requested_at
    FROM assistance_requests ar
    JOIN users u ON ar.user_id = u.id
    WHERE ar.assigned_volunteer_id = ?
    ORDER BY ar.requested_at DESC
");
$stmt->execute([$volunteer_id]);
$assignedRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 45px;
            margin-bottom: 10px;
            display: inline-block;
            border-radius: 50%;
            padding: 15px;
        }

        .stat-icon.male {
            background: rgba(77, 175, 255, 0.15);
            color: #4dafff;
        }

        .stat-icon.female {
            background: rgba(255, 107, 159, 0.15);
            color: #ff6b9f;
        }

        .stat-icon.other {
            background: rgba(157, 255, 107, 0.15);
            color: #9dff6b;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background: #1f4f3c;
            color: #fff;
            padding-top: 20px;
            transition: 0.3s;
        }

        #sidebar.collapsed {
            width: 70px;
        }

        #sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 6px;
            transition: 0.2s;
        }

        #sidebar .nav-link:hover {
            background: #c5e1dc;
            padding-left: 25px;
        }

        #sidebar.collapsed .text {
            display: none;
        }

        #content {
            margin-left: 250px;
            transition: 0.3s;
            padding: 20px;
        }

        #content.expanded {
            margin-left: 70px;
        }

        @media(max-width:768px) {

            #sidebar,
            #content {
                margin-left: 0;
                width: 100% !important;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="volunteer_dashboard.php" class="nav-link"><i class="bi bi-house"></i><span class="text">Dashboard</span></a></li>
            <li class="nav-item"><a href="volunteer_profile.php" class="nav-link"><i class="bi bi-person"></i><span class="text">Profile</span></a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="content">
        <!-- <div class="mb-4">
            <h2 style="text-transform: uppercase;">Welcome, <?= htmlspecialchars($volunteer['fname']) ?>!</h2>
            <p style="color:#1f4f3c;">Your dashboard to manage assigned assistance requests.</p>
        </div> -->

        <div class="d-flex align-items-center mb-3 p-4 bg-white rounded shadow-sm dashboard-header"
            style="border-left: 6px solid #1f4f3c;">

            <!-- Profile Image -->
            <div class="me-4">
                <img
                    src="data:image/jpeg;base64,<?= base64_encode($volunteer['profile_image']) ?>"
                    alt="Profile"
                    class="rounded-circle shadow-sm"
                    style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #1f4f3c;">
            </div>

            <!-- Text -->
            <div>
                <h1 class="m-0 fw-bold" style="text-transform: uppercase; font-size: 2.5rem; color:#1f4f3c;">
                    Welcome, <?= htmlspecialchars($volunteer['fname']) ?>!
                </h1>
                <p class="m-0 mt-2" style="color:#3c6e57; font-size: 1.25rem;">
                    Your dashboard to manage assigned assistance requests.
                </p>
            </div>

        </div>




        <!-- Stats -->
        <div class="row g-2 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-list-check"></i></div>
                    <div>Total Assigned</div>
                    <div class="h4"><?= $totalAssigned ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div>In Progress</div>
                    <div class="h4"><?= $inprogressAssigned ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div>Done</div>
                    <div class="h4"><?= $completed ?></div>
                </div>
            </div>
        </div>

        <!-- Assigned Requests Table -->
        <div class="card p-3">
            <h5>Your Assignment</h5>
            <table id="assignedRequestsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Requester</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($assignedRequests): ?>
                        <?php foreach ($assignedRequests as $req): ?>
                            <tr>
                                <td><?= $req['id'] ?></td>
                                <td><?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?></td>
                                <td><?= htmlspecialchars($req['request_type']) ?></td>
                                <td><?= htmlspecialchars($req['description']) ?></td>
                                <td><?= htmlspecialchars($req['location']) ?></td>
                                <td><?= ucfirst($req['status']) ?></td>
                                <td><?= date('Y-m-d H:i', strtotime($req['requested_at'])) ?></td>
                                <td>
                                    <form action="update_status.php" method="post">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">

                                        <?php if ($req['status'] !== 'completed'): ?>
                                            <button type="submit" class="btn btn-sm btn-success finish-btn">Finish</button>
                                        <?php else: ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php endif; ?>
                                    </form>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No requests assigned yet</td>
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
        $(document).ready(function() {
            $('#assignedRequestsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search requests..."
                }
            });

            // Sidebar toggle
            $('#toggleSidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#content').toggleClass('expanded');
            });
        });
    </script>

</body>

</html>