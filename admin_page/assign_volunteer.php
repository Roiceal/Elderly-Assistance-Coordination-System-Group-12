<?php
session_start();

// Redirect if not admin logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include __DIR__ . '/../db_connect.php'; // Adjust path to db_connect.php

// Handle assignment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['volunteer_id'])) {
    $request_id = intval($_POST['request_id']);
    $volunteer_id = intval($_POST['volunteer_id']);

    $stmt = $pdo->prepare("UPDATE assistance_requests SET assigned_volunteer_id = ?, status = 'in_progress' WHERE id = ?");
    $stmt->execute([$volunteer_id, $request_id]);
    $message = "Request ID $request_id assigned successfully.";
}

// Fetch all pending assistance requests
$stmt = $pdo->query("
    SELECT ar.id, u.username AS requester, ar.request_type, ar.description, ar.location, ar.status, ar.requested_at
    FROM assistance_requests ar
    JOIN users u ON ar.user_id = u.id
    WHERE ar.status = 'pending'
    ORDER BY ar.requested_at ASC
");
$pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all volunteers
$stmt = $pdo->query("SELECT id, fname, lname FROM volunteers ORDER BY fname ASC");
$volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Assistance Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: whitesmoke;
            color: black;
        }

        #sidebar {
            width: 250px;
            background: #1c1c1c;
            position: fixed;
            height: 100vh;
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
            gap: 15px;
            border-radius: 8px;
            transition: 0.2s;
        }

        #sidebar .nav-link:hover {
            background: #333;
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

        table.dataTable thead {
            background: white;
            color: black;
        }

        .table-dark th,
        .table-dark td {
            color: black;
        }

        .card {
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-card {
            background: whitesmoke;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
            padding: 12px;
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

        #topbar {
            background-color: white;
            color: black;
            padding: 10px 20px;
            display: flex;
            margin-bottom: 20px;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->

    <div id="sidebar">
        <button class="btn btn-sm btn-outline-light mb-3 ms-3" id="toggleSidebar"><i class="bi bi-list"></i></button>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="admin.php" class="nav-link"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a></li>
            <li class="nav-item"><a href="userslist.php" class="nav-link"><i class="bi bi-people-fill"></i><span class="text">Users</span></a></li>
            <li class="nav-item"><a href="map/location_map.php" class="nav-link"><i class="bi bi-geo-alt-fill"></i><span class="text">Locate Elder</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-list-check"></i><span class="text">Volunteer Assignment</span></a></li>
            <li class="nav-item"><a href="make_announcement.php" class="nav-link"><i class="bi bi-megaphone-fill"></i><span class="text">Announcement</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear-fill"></i><span class="text">Settings</span></a></li>
            <li class="nav-item"><a href="../rfid/" class="nav-link"><i class="bi bi-person-badge-fill"></i><span class="text">Attendance</span></a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->

    <div id="content">
        <!-- <h2 class="mb-4">Assign Assistance Requests</h2> -->
        <div id="topbar">
            <h4>Assign Assistance Requests</h4>
            <div>
                <i class="bi bi-person-circle fs-4"></i> Admin
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card p-3">
            <table id="requestsTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Requester</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Assign Volunteer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($pendingRequests): ?>
                        <?php foreach ($pendingRequests as $req): ?>
                            <tr>
                                <td><?= $req['id'] ?></td>
                                <td><?= htmlspecialchars($req['requester']) ?></td>
                                <td><?= htmlspecialchars($req['request_type']) ?></td>
                                <td><?= htmlspecialchars($req['description']) ?></td>
                                <td><?= htmlspecialchars($req['location']) ?></td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                        <select name="volunteer_id" class="form-select" required>
                                            <option value="" disabled selected>Select volunteer</option>
                                            <?php foreach ($volunteers as $v): ?>
                                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['fname'] . ' ' . $v['lname']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Assign</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No pending requests</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#requestsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'asc']
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search requests..."
                }
            });

            $('#toggleSidebar').click(function() {
                $('#sidebar').toggleClass('collapsed');
                $('#content').toggleClass('expanded');
            });
        });
    </script>

</body>

</html>